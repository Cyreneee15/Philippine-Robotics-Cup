<?php
/**
 * PRC 2026 – Contact Form Handler
 * Uses PHPMailer (installed via Composer: composer require phpmailer/phpmailer)
 * Place this file in the same directory as contact.html
 */

// ── Allow only POST ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// ── Load PHPMailer ───────────────────────────────────────────────────────────
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ── Sanitise & validate inputs ───────────────────────────────────────────────
function clean(string $val): string {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

$firstName = clean($_POST['firstName'] ?? '');
$lastName  = clean($_POST['lastName']  ?? '');
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone     = clean($_POST['phone']   ?? '');
$school    = clean($_POST['school']  ?? '');
$role      = clean($_POST['role']    ?? '');
$subject   = clean($_POST['subject'] ?? '');
$message   = clean($_POST['message'] ?? '');
$consent   = isset($_POST['consent']) && $_POST['consent'] === 'on';

// Required fields
$errors = [];
if (empty($firstName))                          $errors[] = 'First name is required.';
if (empty($lastName))                           $errors[] = 'Last name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
if (empty($role))                               $errors[] = 'Role is required.';
if (empty($subject))                            $errors[] = 'Subject is required.';
if (empty($message))                            $errors[] = 'Message is required.';
if (!$consent)                                  $errors[] = 'You must accept the Privacy Policy.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

$fullName   = "$firstName $lastName";
$subjectMap = [
    'registration' => 'Registration & Team Sign-Up',
    'categories'   => 'Competition Categories & Rules',
    'materials'    => 'Ordering Materials & Kits',
    'schedule'     => 'Schedule & Venue',
    'makex'        => 'MakeX International Qualifier',
    'sponsorship'  => 'Sponsorship & Partnership',
    'media'        => 'Media & Press Inquiry',
    'other'        => 'Other / General Question',
];
$subjectLabel = $subjectMap[$subject] ?? ucfirst($subject);

// ── Shared font stack (readable, no Courier New) ─────────────────────────────
// Georgia for small labels (elegant, highly readable), Segoe UI for body text
$fontBody  = "'Segoe UI', 'Helvetica Neue', Arial, sans-serif";
$fontLabel = "'Segoe UI', 'Helvetica Neue', Arial, sans-serif";

// ── Helper: builds a field row (label + value) ───────────────────────────────
function fieldRow(string $label, string $value, string $borderColor, string $bgColor, string $valueColor, string $fontBody, string $fontLabel): string {
    return <<<HTML
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                <tr>
                  <td style="border-left:3px solid $borderColor;padding:14px 18px;background:$bgColor;">
                    <p style="margin:0 0 5px 0;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:#9A90CC;font-family:$fontLabel;font-weight:bold;">$label</p>
                    <p style="margin:0;font-size:16px;color:$valueColor;font-weight:600;font-family:$fontBody;line-height:1.5;">$value</p>
                  </td>
                </tr>
              </table>
HTML;
}

// ── Shared header/footer HTML fragments ──────────────────────────────────────
function emailHeader(string $eyebrow, string $badgeText, string $badgeColor, string $fontBody, string $fontLabel): string {
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin:0;padding:0;background-color:#F0EEF8;font-family:'Segoe UI','Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F0EEF8;padding:32px 16px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

        <!-- TOP ACCENT BAR -->
        <tr><td height="3" style="background:linear-gradient(90deg,#7733FF,#44D9FF,#8B7EFF,#44D9FF,#7733FF);font-size:0;line-height:0;">&nbsp;</td></tr>

        <!-- HEADER -->
        <tr><td style="background-color:#06051A;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);padding:34px 40px 26px;">
          <p style="margin:0 0 12px 0;font-size:12px;letter-spacing:0.14em;text-transform:uppercase;color:#44D9FF;font-family:$fontLabel;font-weight:bold;">$eyebrow</p>
          <h1 style="margin:0 0 5px 0;font-size:26px;font-weight:900;color:#ffffff;font-family:$fontBody;line-height:1.1;">
            Philippine <span style="color:#8B7EFF;">Robotics</span> Cup
          </h1>
          <p style="margin:0;font-size:12px;color:#9A90CC;letter-spacing:0.08em;text-transform:uppercase;font-family:$fontLabel;">By Creotec Philippines</p>
          <!-- Divider -->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:22px;">
            <tr>
              <td width="60" height="1" style="background:linear-gradient(90deg,transparent,rgba(68,217,255,0.50));font-size:0;">&nbsp;</td>
              <td width="8" height="8" style="background:#44D9FF;font-size:0;">&nbsp;</td>
              <td height="1" style="background:linear-gradient(90deg,rgba(68,217,255,0.50),transparent);font-size:0;">&nbsp;</td>
            </tr>
          </table>
        </td></tr>

        <!-- BADGE -->
        <tr><td style="background-color:#0A0820;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);border-top:1px solid rgba(139,126,255,0.22);padding:14px 40px;">
          <span style="display:inline-block;background:rgba(139,126,255,0.10);border:1px solid rgba(139,126,255,0.35);padding:7px 16px;font-family:$fontLabel;font-size:11px;font-weight:bold;letter-spacing:0.10em;text-transform:uppercase;color:$badgeColor;">
            &#9679;&nbsp; $badgeText
          </span>
        </td></tr>

HTML;
}

function emailFooter(string $note, string $fontBody, string $fontLabel): string {
    return <<<HTML
        <!-- FOOTER -->
        <tr><td style="background-color:#03020D;border:1px solid rgba(139,126,255,0.22);border-top:none;padding:22px 40px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>
                <p style="margin:0 0 3px 0;font-size:14px;font-weight:700;color:#8B7EFF;font-family:$fontBody;">Philippine Robotics Cup 2026</p>
                <p style="margin:0;font-size:12px;color:#7068A8;letter-spacing:0.06em;text-transform:uppercase;font-family:$fontLabel;">By Creotec Philippines Inc.</p>
              </td>
              <td align="right" style="vertical-align:middle;">
                <p style="margin:0;font-size:11px;color:#7068A8;font-family:$fontLabel;">&copy; 2026 PRC</p>
              </td>
            </tr>
            <tr><td colspan="2" style="padding-top:12px;">
              <p style="margin:0;font-size:11px;color:#3D3660;font-family:$fontLabel;">$note</p>
            </td></tr>
          </table>
        </td></tr>

        <!-- BOTTOM ACCENT BAR -->
        <tr><td height="3" style="background:linear-gradient(90deg,#7733FF,#44D9FF,#8B7EFF,#44D9FF,#7733FF);font-size:0;line-height:0;">&nbsp;</td></tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
}

// ════════════════════════════════════════════════════════════════════════════
// EMAIL 1 — Admin notification (sent to PRC team)
// ════════════════════════════════════════════════════════════════════════════
$adminHtml  = emailHeader('PRC 2026 — New Contact Inquiry', 'New Form Submission Received', '#FFE930', $fontBody, $fontLabel);
$adminHtml .= <<<HTML
        <!-- FIELDS -->
        <tr><td style="background-color:#06051A;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);padding:28px 40px 8px;">
HTML;
$adminHtml .= fieldRow('Full Name',     $fullName, '#8B7EFF', 'rgba(139,126,255,0.06)', '#F2EEFF', $fontBody, $fontLabel);
$adminHtml .= fieldRow('Email Address', "<a href=\"mailto:$email\" style=\"color:#44D9FF;text-decoration:none;\">$email</a>", '#44D9FF', 'rgba(68,217,255,0.04)', '#44D9FF', $fontBody, $fontLabel);
if ($phone)  $adminHtml .= fieldRow('Phone / Viber',       $phone,  '#8B7EFF', 'rgba(139,126,255,0.06)', '#F2EEFF', $fontBody, $fontLabel);
if ($school) $adminHtml .= fieldRow('School / Organisation', $school, '#8B7EFF', 'rgba(139,126,255,0.06)', '#F2EEFF', $fontBody, $fontLabel);
$adminHtml .= <<<HTML
          <!-- ROLE + SUBJECT: two columns -->
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
            <tr>
              <td width="47%" style="border-left:3px solid #7733FF;padding:14px 18px;background:rgba(119,51,255,0.06);vertical-align:top;">
                <p style="margin:0 0 5px 0;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:#9A90CC;font-family:$fontLabel;font-weight:bold;">Role</p>
                <p style="margin:0;font-size:16px;color:#C4EEFF;font-weight:600;font-family:$fontBody;text-transform:capitalize;">$role</p>
              </td>
              <td width="6%">&nbsp;</td>
              <td width="47%" style="border-left:3px solid #FFA030;padding:14px 18px;background:rgba(255,160,48,0.05);vertical-align:top;">
                <p style="margin:0 0 5px 0;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:#9A90CC;font-family:$fontLabel;font-weight:bold;">Subject</p>
                <p style="margin:0;font-size:16px;color:#FFA030;font-weight:700;font-family:$fontBody;">$subjectLabel</p>
              </td>
            </tr>
          </table>
        </td></tr>

        <!-- MESSAGE -->
        <tr><td style="background-color:#06051A;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);padding:0 40px 28px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr><td height="1" style="background:linear-gradient(90deg,transparent,rgba(68,217,255,0.30),transparent);font-size:0;">&nbsp;</td></tr>
            <tr><td style="background:rgba(68,217,255,0.04);border:1px solid rgba(68,217,255,0.18);border-top:none;padding:20px 22px;">
              <p style="margin:0 0 10px 0;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#44D9FF;font-family:$fontLabel;font-weight:bold;">Message</p>
              <p style="margin:0;font-size:15px;color:#C8C0F0;line-height:1.80;white-space:pre-wrap;word-break:break-word;font-family:$fontBody;">$message</p>
            </td></tr>
          </table>
        </td></tr>

        <!-- REPLY HINT -->
        <tr><td style="background-color:#0A0820;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);border-top:1px solid rgba(139,126,255,0.14);padding:16px 40px;">
          <p style="margin:0;font-size:13px;color:#9A90CC;font-family:$fontBody;">
            Hit <strong style="color:#8B7EFF;">Reply</strong> to respond directly to <strong style="color:#F2EEFF;">$fullName</strong> at $email
          </p>
        </td></tr>

HTML;
$adminHtml .= emailFooter('Sent via prc2026.creotec.ph &mdash; This is an automated admin notification.', $fontBody, $fontLabel);

// ════════════════════════════════════════════════════════════════════════════
// EMAIL 2 — Confirmation copy sent back to the person who submitted the form
// ════════════════════════════════════════════════════════════════════════════
$confirmHtml  = emailHeader("PRC 2026 — Message Received, $firstName!", 'Your message has been sent', '#44FF88', $fontBody, $fontLabel);
$confirmHtml .= <<<HTML
        <!-- THANK YOU MESSAGE -->
        <tr><td style="background-color:#06051A;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);padding:32px 40px 24px;">
          <p style="margin:0 0 16px 0;font-size:17px;color:#F2EEFF;font-family:$fontBody;line-height:1.7;">
            Hi <strong style="color:#8B7EFF;">$firstName</strong>,
          </p>
          <p style="margin:0 0 14px 0;font-size:15px;color:#C8C0F0;font-family:$fontBody;line-height:1.80;">
            Thank you for reaching out to the <strong style="color:#F2EEFF;">Philippine Robotics Cup 2026</strong> team. We have received your message and will get back to you within <strong style="color:#44D9FF;">one business day</strong>.
          </p>
          <p style="margin:0;font-size:15px;color:#C8C0F0;font-family:$fontBody;line-height:1.80;">
            For urgent concerns, you may also reach us at <a href="tel:+639177713961" style="color:#8B7EFF;text-decoration:none;">+63 917 771 3961</a> (Mon–Fri, 9 AM–5 PM).
          </p>
        </td></tr>

        <!-- COPY OF SUBMISSION -->
        <tr><td style="background-color:#0A0820;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);border-top:1px solid rgba(139,126,255,0.22);padding:20px 40px 8px;">
          <p style="margin:0 0 16px 0;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#9A90CC;font-family:$fontLabel;font-weight:bold;">Your Submission — Copy for Your Records</p>
HTML;
$confirmHtml .= fieldRow('Full Name', $fullName, '#8B7EFF', 'rgba(139,126,255,0.06)', '#F2EEFF', $fontBody, $fontLabel);
$confirmHtml .= fieldRow('Email Address', $email, '#44D9FF', 'rgba(68,217,255,0.04)', '#C4EEFF', $fontBody, $fontLabel);
if ($phone)  $confirmHtml .= fieldRow('Phone / Viber',        $phone,  '#8B7EFF', 'rgba(139,126,255,0.06)', '#F2EEFF', $fontBody, $fontLabel);
if ($school) $confirmHtml .= fieldRow('School / Organisation', $school, '#8B7EFF', 'rgba(139,126,255,0.06)', '#F2EEFF', $fontBody, $fontLabel);
$confirmHtml .= <<<HTML
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
            <tr>
              <td width="47%" style="border-left:3px solid #7733FF;padding:14px 18px;background:rgba(119,51,255,0.06);vertical-align:top;">
                <p style="margin:0 0 5px 0;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:#9A90CC;font-family:$fontLabel;font-weight:bold;">Role</p>
                <p style="margin:0;font-size:16px;color:#C4EEFF;font-weight:600;font-family:$fontBody;text-transform:capitalize;">$role</p>
              </td>
              <td width="6%">&nbsp;</td>
              <td width="47%" style="border-left:3px solid #FFA030;padding:14px 18px;background:rgba(255,160,48,0.05);vertical-align:top;">
                <p style="margin:0 0 5px 0;font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:#9A90CC;font-family:$fontLabel;font-weight:bold;">Subject</p>
                <p style="margin:0;font-size:16px;color:#FFA030;font-weight:700;font-family:$fontBody;">$subjectLabel</p>
              </td>
            </tr>
          </table>
        </td></tr>

        <!-- MESSAGE COPY -->
        <tr><td style="background-color:#0A0820;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);padding:0 40px 28px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr><td height="1" style="background:linear-gradient(90deg,transparent,rgba(68,217,255,0.30),transparent);font-size:0;">&nbsp;</td></tr>
            <tr><td style="background:rgba(68,217,255,0.04);border:1px solid rgba(68,217,255,0.18);border-top:none;padding:20px 22px;">
              <p style="margin:0 0 10px 0;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#44D9FF;font-family:$fontLabel;font-weight:bold;">Your Message</p>
              <p style="margin:0;font-size:15px;color:#C8C0F0;line-height:1.80;white-space:pre-wrap;word-break:break-word;font-family:$fontBody;">$message</p>
            </td></tr>
          </table>
        </td></tr>

        <!-- NOTE -->
        <tr><td style="background-color:#0A0820;border-left:1px solid rgba(139,126,255,0.22);border-right:1px solid rgba(139,126,255,0.22);border-top:1px solid rgba(139,126,255,0.14);padding:16px 40px;">
          <p style="margin:0;font-size:13px;color:#7068A8;font-family:$fontBody;font-style:italic;">
            Please keep this email as proof of your submission. Do not reply to this message — it is automatically generated.
          </p>
        </td></tr>

HTML;
$confirmHtml .= emailFooter('Philippine Robotics Cup 2026 &mdash; prc2026.creotec.ph', $fontBody, $fontLabel);

// ── Plain-text fallback ──────────────────────────────────────────────────────
$adminText = "PRC 2026 – New Contact Form Submission\n"
    . str_repeat('=', 42) . "\n"
    . "Name    : $fullName\n"
    . "Email   : $email\n"
    . ($phone  ? "Phone   : $phone\n"  : '')
    . ($school ? "School  : $school\n" : '')
    . "Role    : $role\n"
    . "Subject : $subjectLabel\n\n"
    . "Message:\n$message\n";

$confirmText = "Hi $firstName,\n\n"
    . "Thank you for contacting Philippine Robotics Cup 2026!\n"
    . "We have received your message and will respond within one business day.\n\n"
    . "--- YOUR SUBMISSION COPY ---\n"
    . "Name    : $fullName\n"
    . "Email   : $email\n"
    . ($phone  ? "Phone   : $phone\n"  : '')
    . ($school ? "School  : $school\n" : '')
    . "Role    : $role\n"
    . "Subject : $subjectLabel\n\n"
    . "Message:\n$message\n\n"
    . "---\n"
    . "Please keep this email as proof of your submission.\n"
    . "Philippine Robotics Cup 2026 — By Creotec Philippines Inc.\n";

// ── PHPMailer setup ──────────────────────────────────────────────────────────
$mail = new PHPMailer(true);

try {
    // ── SMTP settings ────────────────────────────────────────────────────────
    // For Gmail: host = smtp.gmail.com, port = 587
    //            Username = your Gmail, Password = 16-char App Password (with spaces)
    //            Generate at: https://myaccount.google.com/apppasswords
    // ─────────────────────────────────────────────────────────────────────────
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'cyrenealmeda@gmail.com';   // ← your Gmail address
    $mail->Password   = 'zyxr acop wuwx pvne';    // ← Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ════════════════════════════════════════════════════════════════════
    // SEND EMAIL 1 — Admin notification to PRC team
    // ════════════════════════════════════════════════════════════════════
    $mail->setFrom('cyrenealmeda@gmail.com', 'PRC 2026 Website');
    $mail->addAddress('cyrenealmeda@gmail.com', 'PRC Team'); // ← receives inquiries
    $mail->addReplyTo($email, $fullName);                        // reply goes to sender
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = "[PRC 2026] $subjectLabel – $fullName";
    $mail->Body    = $adminHtml;
    $mail->AltBody = $adminText;
    $mail->send();

    // ════════════════════════════════════════════════════════════════════
    // SEND EMAIL 2 — Confirmation copy to the person who submitted
    // ════════════════════════════════════════════════════════════════════
    $mail->clearAddresses();
    $mail->clearReplyTos();
    $mail->addAddress($email, $fullName);                        // send to submitter
    $mail->Subject = "[PRC 2026] We received your message, $firstName!";
    $mail->Body    = $confirmHtml;
    $mail->AltBody = $confirmText;
    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => "Your message has been sent! A confirmation copy has been sent to $email. We'll get back to you within one business day.",
    ]);

} catch (Exception $e) {
    error_log('PHPMailer error: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, your message could not be sent right now. Please try again or email us directly.',
        // ↓ Uncomment temporarily to debug:
        // 'debug' => $mail->ErrorInfo,
    ]);
}
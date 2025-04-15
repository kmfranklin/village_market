<?php
require_once '../private/initialize.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize variables
$errors = [];
$contact = [
  'name' => '',
  'business_name' => '',
  'email' => '',
  'reason' => '',
  'message' => ''
];

if (is_post_request()) {
  // Sanitize inputs
  $contact['name'] = trim($_POST['name'] ?? '');
  $contact['business_name'] = trim($_POST['business_name'] ?? '');
  $contact['email'] = trim($_POST['email'] ?? '');
  $contact['reason'] = trim($_POST['reason'] ?? '');
  $contact['message'] = trim($_POST['message'] ?? '');

  // Validate required fields
  if ($contact['name'] === '') {
    $errors[] = "Name is required.";
  }

  if ($contact['email'] === '' || !filter_var($contact['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email is required.";
  }

  if ($contact['reason'] === '') {
    $errors[] = "Please select a reason for your message.";
  }

  if ($contact['message'] === '') {
    $errors[] = "Message is required.";
  }

  $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

  if (empty($recaptcha_response)) {
    $errors[] = "Please complete the CAPTCHA challenge.";
  } else {
    $recaptcha_secret = $_ENV['RECAPTCHA_SECRET_KEY'];
    $verify_response = file_get_contents(
      "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}"
    );
    $captcha_data = json_decode($verify_response, true);

    if (!$captcha_data['success']) {
      $errors[] = "CAPTCHA verification failed. Please try again.";
    }
  }


  // Send message if no errors
  if (empty($errors)) {
    $admin_email = 'admin@villagemarkethub.com';
    $admin_name = 'Village Market Admin';

    $subject = "Contact Form Submission: " . $contact['reason'];

    $body_html = "
      <p><strong>Name:</strong> " . h($contact['name']) . "</p>
      <p><strong>Business Name:</strong> " . h($contact['business_name'] ?: 'N/A') . "</p>
      <p><strong>Email:</strong> " . h($contact['email']) . "</p>
      <p><strong>Reason:</strong> " . h($contact['reason']) . "</p>
      <p><strong>Message:</strong><br>" . nl2br(h($contact['message'])) . "</p>
    ";

    $body_plain =
      "Name: {$contact['name']}\n" .
      "Business Name: " . ($contact['business_name'] ?: 'N/A') . "\n" .
      "Email: {$contact['email']}\n" .
      "Reason: {$contact['reason']}\n\n" .
      "Message:\n{$contact['message']}";

    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = $_ENV['SMTP_HOST'];
      $mail->SMTPAuth = true;
      $mail->Username = $_ENV['SMTP_USER'];
      $mail->Password = $_ENV['SMTP_PASS'];
      $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
      $mail->Port = $_ENV['SMTP_PORT'];

      $mail->setFrom($_ENV['SMTP_USER'], 'Village Market');
      $mail->addAddress($admin_email, $admin_name);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $body_html;
      $mail->AltBody = $body_plain;

      $mail->send();

      $_SESSION['message'] = "Thanks for reaching out! Your message has been sent.";
      redirect_to('contact.php');
    } catch (Exception $e) {
      $errors[] = "Message could not be sent. Please try again later.";
      error_log("Contact Form Mailer Error: " . $mail->ErrorInfo);
    }
  }
}

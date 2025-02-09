<?php

require_once('../private/initialize.php');
require_once('../private/shared/public_header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Invalid email format.";
    redirect_to("forgot_password.php");
  }

  $sql = "SELECT user_id FROM user WHERE email_address = ?";
  $stmt = $database->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user) {
    $_SESSION['message'] = "No account found with that email.";
    redirect_to("forgot_password.php");
  }

  // Generate secure reset token and set its expiration
  $token = bin2hex(random_bytes(32)); // Random, 64-character string
  $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in one hour

  // Insert/update token in the database
  $sql = "INSERT INTO password_reset (user_id, token, expires_at)
  VALUES (?, ?, ?)
  ON DUPLICATE KEY UPDATE token = ?, expires_at = ?";
  $stmt = $database->prepare($sql);
  $stmt->bind_param("issss", $user['user_id'], $token, $expires_at, $token, $expires_at);

  $stmt->execute();

  // Generate reset link with the token
  $reset_link = "http://villagemarkethub.com/public/reset_password.php?token=" . $token;

  // Send the email
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
    $mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());
    $mail->addCustomHeader('List-Unsubscribe', '<mailto:unsubscribe@villagemarkethub.com>');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Password Reset Request";
    $mail->Body = "
      <p>Hello,</p>
      <p>We received a request to reset your password. Click the link below to reset it:</p>
      <p><a href='" . $reset_link . "'>Reset Password</a></p>
      <p>If you did not make this request, please ignore this email.</p>
      <p>Thank you,<br>Village Market</p>
      ";

    $mail->AltBody = "Hello,\n\nWe received a request to reset your password. Click the link below to reset it:\n\n" . $reset_link . "\n\nIf you did not request this, please ignore this email.\n\nThank you,\nVillage Market";

    $mail->send();

    $_SESSION['message'] = "Password reset email sent! Please check your inbox.";
    redirect_to("forgot_password.php");
  } catch (Exception $e) {
    $_SESSION['message'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    redirect_to("forgot_password.php");
  }
}

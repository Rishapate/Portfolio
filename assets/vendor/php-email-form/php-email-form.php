<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php'; // Ensure this path is correct
require __DIR__ . '/vendor/autoload.php';


class PHP_Email_Form {
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $ajax = false;
    public $messages = [];
    public $smtp_host = 'smtp.gmail.com';  // Default SMTP Host
    public $smtp_username = 'patelrisha1906@gmail.com';  // SMTP Username (Gmail address)
    public $smtp_password = 'yqgz jiuv ywzv yetx';  // SMTP Password (App Password from Gmail)
    public $smtp_secure = PHPMailer::ENCRYPTION_STARTTLS;  // Encryption method
    public $smtp_port = 587;  // SMTP Port for TLS

    // Method to add messages
    public function add_message($message, $subject = null, $priority = 10) {
        $this->messages[] = [
            'message' => $this->sanitize_input($message),
            'subject' => $this->sanitize_input($subject),
            'priority' => $priority
        ];
    }

    // Method to send email using PHPMailer
    public function send() {
        $mail = new PHPMailer(true);

        try {
            // Validate email address
            if (!filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
                return json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
            }

            //Server settings for SMTP
            $mail->isSMTP();
            $mail->Host       = $this->smtp_host;
            $mail->SMTPAuth   = true;
            // $mail->SMTPDebug  = 2; // or 1 for less verbose output
            $mail->Username   = $this->smtp_username;
            $mail->Password   = $this->smtp_password;
            $mail->SMTPSecure = $this->smtp_secure;
            $mail->Port       = $this->smtp_port;

            // Recipients
            $mail->setFrom($this->from_email, $this->from_name);
            $mail->addAddress($this->to);

            // Content of the email
            $mail->isHTML(true);
            $mail->Subject = $this->subject;

            // Build the message body
            $message_body = "You have received a new message:\n\n";
            foreach ($this->messages as $msg) {
                $message_body .= ($msg['subject'] ? $msg['subject'] . ": " : "") . $msg['message'] . "\n\n";
            }
            $mail->Body = nl2br($message_body);  // Convert newlines to <br> for HTML
            $mail->AltBody = strip_tags($message_body);  // Plain text version

            // Send the email
            if ($mail->send()) {
                return json_encode(['status' => 'success', 'message' => 'Email sent successfully.']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Email sending failed.']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
    }

    // Sanitize input to prevent injection attacks
    private function sanitize_input($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }
}
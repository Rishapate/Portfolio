<?php
ini_set('display_errors', 1);  // Enable error reporting
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED); // Hide deprecated warnings

/**
 * Requires the "PHP Email Form" library
 * The "PHP Email Form" library is available only in the pro version of the template
 * The library should be uploaded to: vendor/php-email-form/php-email-form.php
 * For more info and help: https://bootstrapmade.com/php-email-form/
 */

// Database credentials
$servername = "sql210.infinityfree.com";  // Correct MySQL hostname
$username = "if0_37598796";              // Your MySQL username
$password = "Cu3MSFCkxPi";               // Your MySQL password
$dbname = "if0_37598796_contact";        // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Replace contact@example.com with your real receiving email address
$receiving_email_address = 'patelrisha1906@gmail.com';

// Include the PHP Email Form library
if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = $_POST['name'];
$contact->from_email = $_POST['email'];
$contact->subject = $_POST['subject'];

// SMTP settings (the correct way would be to use a setter method, but for now, we will keep this)
$contact->smtp = array(
    'host' => 'smtp.gmail.com',
    'username' => 'patelrisha1906@gmail.com',
    'password' => 'yqgz jiuv ywzv yetx', // Use your actual app password
    'port' => 587,
    'encryption' => 'tls'
);

$contact->add_message($_POST['name'], 'From');
$contact->add_message($_POST['email'], 'Email');
$contact->add_message($_POST['message'], 'Message', 10);

// Insert form data into the database
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

// Execute the query and check for success
if ($stmt->execute()) {
    // If the data was inserted successfully into the database, send the email
    if ($contact->send()) {
        echo 'Your message has been sent successfully! Thank you for contacting us.';
    } else {
        echo 'Error: Unable to send email. Please try again later.';
    }
} else {
    echo 'Error: Unable to store your message in the database. Please try again later.';
}


// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>

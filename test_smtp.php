<?php
require 'vendor/autoload.php';
$transport = new Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport('smtp.gmail.com', 587, false);
$transport->setUsername('ibrargulzar2004@gmail.com');
$transport->setPassword('ffycvjrkhcnlqior');
try {
    $transport->start();
    echo 'Connected successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

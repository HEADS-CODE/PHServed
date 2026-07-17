<?php

//Confirmation email

require_once(__DIR__ . "/app.php");
require_once(__DIR__ . "/mail_credentials.php");

require_once(
    __DIR__ . "/../libraries/phpmailer/src/Exception.php"
);

require_once(
    __DIR__ . "/../libraries/phpmailer/src/PHPMailer.php"
);

require_once(
    __DIR__ . "/../libraries/phpmailer/src/SMTP.php"
);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_confirmation_email(
    $to_email,
    $complete_name,
    $confirm_token
) {
    global $smtp_username;
    global $smtp_password;
    global $base_url;

    $mail = new PHPMailer(true);

    //Gmail settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom(
        $smtp_username,
        "PHServed Registration"
    );

    $mail->addAddress(
        $to_email,
        $complete_name
    );

    $confirmation_link =
        $base_url .
        "/buyer/confirm.php?token=" .
        urlencode($confirm_token);

    $mail->isHTML(true);
    $mail->Subject = "Confirm your PHServed account";

    $mail->Body = "
        <p>Dear <strong>$complete_name</strong>,</p>

        <p>
            Thank you for registering at PHServed.
            Please click the button below to confirm your account.
        </p>

        <p>
            <a
                href='$confirmation_link'
                style='
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #113767;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                '
            >
                Confirm PHServed Account
            </a>
        </p>

        <p>
            If the button does not work, copy this address:
        </p>

        <p>$confirmation_link</p>

        <p>
            This email was sent for the PHServed educational project.
        </p>
    ";

    $mail->send();
}

?>
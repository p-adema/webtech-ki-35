<?php
function mail_acc_verify(string $tag, string $to): bool
{
    $subject = 'Verify your EduGrove account';
    $from = 'noreply@webtech-ki35.webtech-uva.nl';
    $headers = ['From' => $from, 'Content-type' => 'text/html; charset=utf-8'];
    $body = "
<!DOCTYPE html>
<html lang='en'>
<body>
    <h1> Verify your account </h1>
    <p> 
        Welcome to EduGrove! <br />
        To continue, please click on 
        <a href = 'https://webtech-ki35.webtech-uva.nl/verify.php?tag=$tag'>this link</a> <br />
    </p> 
    <p>
        If that doesn't work, paste this link into your browser: <br />
        https://webtech-ki35.webtech-uva.nl/verify.php?tag=$tag
    </p>
</body>
</html>";

    return mail($to, $subject, $body, $headers);
}

function mail_forgot_password(string $tag, string $to): bool
{
    $subject = 'Change your EduGrove password';
    $from = 'noreply@webtech-ki35.webtech-uva.nl';
    $headers = ['From' => $from, 'Content-type' => 'text/html; charset=utf-8'];
    $body = "
<!DOCTYPE html>
<html lang='en'>
<body>
    <h1> Change your password </h1>
    <p> 
        You have requested a password reset for your account <br />
        To continue, please click on 
        <a href = 'https://webtech-ki35.webtech-uva.nl/verify.php?tag=$tag'>this link</a> <br />
    </p> 
    <p>
        If that doesn't work, paste this link into your browser: <br />
        https://webtech-ki35.webtech-uva.nl/verify.php?tag=$tag
    </p>
    <p>
        If this wan't you, you can safely ignore this email.
    </p>
</body>
</html>";

    return mail($to, $subject, $body, $headers);
}
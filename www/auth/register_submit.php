<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-to-Day - Create an account</title>
</head>
<body class="form">
    
<?php

    /**
     * register_submit.php
     * 
     * create an account and send the verification email to the user
     * @since 20201224
     * @author jimmybear217
     */

function display_error($message) {
    die("<h1>Error</h1><p>$message</p><a href=\"javascript:history.go(-1)\">Back</a></body></html>");
}

// verify presence of all required fields
$requred_fields = array("name", "username", "email", "password");
foreach($requred_fields as $f) {
    if (empty($_POST[$f])) display_error("Please fill in all requred fields. $f is missing.");
}

// validate fields
$name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
$username = strtolower(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
if (!empty($_POST["password2"])) {
    if ($_POST["password2"] == $_POST["password"]) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    } else {
        display_error("Passwords do not match");
    }
} else {
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
}

// insert new user in database
require("pdo.php");
$stmS = $db->prepare("SELECT count(username) FROM users WHERE username = ?");
try {
    $stmS->execute(array($username));
    $result = $stmS->fetch(PDO::FETCH_NUM);
    if (isset($result[0])) {
        if ($result[0] > 0) {
            display_error("This username is already in use, please try again");
        }
    }
} catch (PDOException $e) {
    error_log("Unable to query existance of username $uesrname: " . $e->getMessage(), 1, "logs@jimmybear217.com");
}
$stmI = $db->prepare("INSERT INTO users(`username`, `email`, `password`, `status`, `created`) VALUES (:username, :email, :password, :status, :timestamp);");
try {
    $status = intval($stmI->execute(array(
        ':username' => $username,
        ':email' => $email,
        ':password' => $password,
        ':status' => 'new',
        ':timestamp' => time()
    )));
} catch (PDOException $e) {
    error_log("Unable to add user $username to DB: " . $e->getMessage(), 1, "logs@jimmybear217.com");
    display_error("Something went wrong while creating your account. Please try again. (" . $e->getMessage() . ")");
}
if ($status == 0) {
    error_log("Unable to add user $username to DB: " . json_encode($stm->errorinfo()), 1, "logs@jimmybear217.com");
    display_error("Something went wrong while creating your account. Please try again.");
}

require_once("Token.class.php");
$t = new Token($username);
if ($t->setAccess("verify_email")) {
    $t = $t->getToken();
    mail($email, "Welcome to Day-to-Day, $name",  "Welcome to Day-to-Day, \r\n\r\nplease use the following link to activate your account:\r\n" . ((empty($_SERVER["HTTPS"])) ? "http://" : "https://") . $_SERVER["SERVER_NAME"] . "/auth/verify_email.php?token=$t&username=$username\r\n\r\nThank you\r\nThe Day-to-Day Team", "From: Day-to-Day <noreply@jimmybear217.com>");
} else {
    error_log("unable configure token properly");
}

?>
    <h1>Thank you</h1>
    <p>Please verify your email address by opening the link we sent you. if you don't receive it in the next 15 minutes, check you junk and spam mail and try to log into your account to get another activation email.</p>
    <a href="/auth/login.html">Sign in</a>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-to-Day - Create an account</title>
</head>
<body>
    
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
$stm = $db->prepare("INSERT INTO users(`username`, `email`, `password`, `status`, `created`) VALUES (:username, :email, :password, :status, :timestamp);");
try {
    $status = intval($stm->execute(array(
        ':username' => $username,
        ':email' => $email,
        ':password' => $password,
        ':status' => 'new',
        ':timestamp' => time()
    )));
} catch (PDOException $e) {
    error_log("Unable to add user to DB: " . $e->getMessage(), 1, "logs@jimmybear217.com");
    display_error("Something went wrong while creating your account. Please try again. (" . $e->getMessage() . ")");
}
if ($status == 0) {
    error_log("Unable to add user to DB: " . json_encode($stm->errorinfo()), 1, "logs@jimmybear217.com");
    display_error("Something went wrong while creating your account. Please try again. (0)");
}

echo "account was created";

require_once("Token.class.php");
$t = new Token();
$t->username;


?>
</body>
</html>
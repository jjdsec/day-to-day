<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Day-to-Day - email verification</title>
</head>
<body class="form">

<?php

    /**
     * verify_email.php
     * 
     * uses the Tokens class to verify the token link that was sent via email to the user
     * @author JimmyBear217
     * @since 20201225
     * @uses Tokens.class.php
     */

    function display_error($message) {
        die("<h1>Error</h1><p>$message</p>");
    }

    require_once("Token.class.php");
    require_once("pdo.php");

    // gather input
    if (!empty($_GET["token"]) && !empty($_GET["username"])) {
        $username = filter_var($_GET["username"], FILTER_SANITIZE_STRING);
        $token = filter_var($_GET["token"], FILTER_SANITIZE_STRING);
    } else {
        display_error("Missing a required field. Please try again.");
    }

    // verify username
    $stmS = $db->prepare("SELECT username, status FROM users WHERE username = ?;");
    try {
        $stmS->execute(array($username));
        $result = $stmS->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if ($result["username"] != $username) {
                display_error("The provided username doesn't match with our records.");
            }
            if ($result["status"] != "new") {
                display_error("Your account is already activated, please go ahead and <a href='/auth/login.html#u:$username'>log in.</a>");
            }
        } else {
            display_error("Something went wrong. Please <a href='/auth/login.html#u:$username'>Sign In</a> or <a href='/auth/register.html'>Create an account</a>");
        }
    } catch (PDOException $e) {
        error_log("Unable to query user $username for activation: " . $e->getMessage(), 1, "logs@jimmybear217.com");
        display_error("Something went wrong. Please try again or <a href='/auth/login.html#u:$username'>Sign In</a>.");
    }

    // verify token
    $t = new Token($username, filter_var($_GET["token"], FILTER_SANITIZE_STRING));
    if ($t->isValid() && $t->getAccess() == "verify_email") {
        $stm = $db->prepare("UPDATE users SET status = 'active' WHERE username = ?");
        try {
            if (!$stm->execute(array($username))) {
                display_error("Unable to activate your account. it may already be active. <a href='/auth/login.html#u:$username'>Please log in</a>");
            }
            $t->consume();
        } catch (PDOException $e) {
            display_error("Something went wrong. Please try again or <a href='/auth/login.html#u:$username'>log in</a>");
            error_log("error while setting an account active for $username using $token: " . $e->getMessage(), 1, "logs@jimmybear217.com");
        }
    } else {
        display_error("This token is not valid. Please try again or <a href='/auth/login.html#u:$username'>log in</a>");
    }


?>
    <h1>Welcome to Day to Day</h1>
    <p>Your account is now valid, you can go ahead and log in to enjoy this app<p>
    <a href="/auth/login.html#u:<?php echo $username; ?>">Sign in</a>
</body>
</html>
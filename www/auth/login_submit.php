<?php


    /**
     * login_submit.php
     * 
     * handle the login of users and provided a cookied token to be used for all authenticated requests
     * @author JimmyBear217
     * @since 20201225
     * @uses Token.class.php
     */

    function display_error($message) {
        finalOutput("<h1>Error</h1><p>$message</p><a href='javascript:history.go(-1)'>Back</a>");
    }
    function finalOutput($message) {
        echo '<!DOCTYPE html>' .
        '<html lang="en">' .
        '<head>' .
            '<meta charset="UTF-8">' .
            '<meta name="viewport" content="width=device-width, initial-scale=1.0">' .
            '<title>Day-to-Day - login</title>' .
        '</head>' .
        '<body>' . $message . '</body></html>';
        exit(0);
    }

    require_once("Token.class.php");
    require_once("pdo.php");

    // gather input
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        $password = $_POST["password"];
    } else {
        display_error("Missing fields. Please try again");
    }

    // load user from DB
    $stm = $db->prepare("SELECT username, password, status FROM users WHERE username = ?");
    try {
        $stm->execute(array($username));
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) display_error("Invalid Username or Password");
        if (filter_var($result["username"], FILTER_SANITIZE_STRING) != $username) display_error("Invalid Username or Password");
        if (!password_verify($password, $result["password"])) display_error("Invalid Username or Password");
    } catch (PDOException $e) {
        error_log("Something went wrong while user $username was trying to log in: " . $e->getMessage(), 1, "logs@jimmybear217.com");
        display_error("Something went wrong, please try again later.");
    }

    // password has been verified
    // verifying account validity
    $t = new Token($username);
    switch($result["status"]) {
        case "active":
            // all good: generate token
            if ($t->setAccess("app_access")) {
                $t = $t->getToken();
                mail($email, "Login Notification",  "Somebody logged into your account with your password. \r\nIf that wasn't you, consider changing your password here: https://" . $_SERVER["HTTP_HOST"] . "/auth/recoverPassword.html\r\n\r\nThank you for using our services.\r\nThe Day-to-Day Team", "From: Day-to-Day <noreply@jimmybear217.com>");
                setcookie("token", base64_encode($username . ":" . $t), time()+(60*60*24), "/", $_SERVER["HTTP_HOST"], true, false);
                setcookie("username", $username, time()+(60*60*24), "/", $_SERVER["HTTP_HOST"], true, false);
                header("Location: /app", true, 307);
                finalOutput("You are successfully logged in, redirecting you to <a href='/app'>the app</a>");
            } else {
                display_error("Something went wrong while logging you in. Our people are working on it. Please try again later.");
            }
            break;
        
        case "new":
            // send activation email: generate email with token
            if ($t->setAccess("verify_email")) {
                $t = $t->getToken();
                mail($email, "Welcome to Day-to-Day",  "Welcome to Day-to-Day, \r\n\r\nplease use the following link to activate your account:\r\n" . ((empty($_SERVER["HTTPS"])) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . "/auth/verify_email.php?token=$t&username=$username\r\n\r\nThank you\r\nThe Day-to-Day Team", "From: Day-to-Day <noreply@jimmybear217.com>");
                finalOutput("Please check your email to activate your account");
            } else {
                display_error("Something went wrong while logging you in. Our people are working on it. Please try again later.");
            }
            break;

        case "disabled":
            // send ask to reactivate account: generate email with token
            if ($t->setAccess("account_reactivation")) {
                $t = $t->getToken();
                mail($email, "Welcome back to Day-to-Day",  "Welcome back to Day-to-Day, \r\n\r\nplease use the following link to restiore your account:\r\n" . ((empty($_SERVER["HTTPS"])) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . "/auth/restoreAccount.php?token=$t&username=$username\r\n\r\nThank you\r\nThe Day-to-Day Team", "From: Day-to-Day <noreply@jimmybear217.com>");
                finalOutput("Please check your email to restore your account");
            } else {
                display_error("Something went wrong while logging you in. Our people are working on it. Please try again later.");
            }
            break;

        default:
            error_log("User $username cannot log in because the status of his account is unknown: " . $result["status"], 1, "logs@jimmybear217.com");
            display_error("Something went wrong while logging you in. Our people are working on it. Please try again later.");
            break;
    }

?>
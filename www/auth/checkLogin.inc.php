<?php

// force https
echo "HTTPS: " . ((!empty($_SERVER["HTTPS"])) ? "True" : "False") . "\n";
if (empty($_SERVER["HTTPS"])) {
    $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    header("Location: " . $url . "\n", true, 302);
    echo "<script type='text/javascript'>setTimeout( () => { window.location.replace('$url');}, 500);</script>";
    die("Redirecting to the secure version of this page. click <a href='$url'>here</a> if you are not redirected.");

}

// ensure user is logged in properly
if (isset($_COOKIE["username"]) && isset($_COOKIE["token"])) {
    $tokenC = base64_decode($_COOKIE["token"]);
    $usernameC = filter_var($_COOKIE["username"], FILTER_SANITIZE_STRING);
    $token = explode(":", $tokenC);
    $username = $token[0];
    $token = $token[count($token)-1];

    /*echo "Token: " . $token . "\n";
    echo "User: " . $username;*/

    require_once(__DIR__ . "/Token.class.php");
    $t = new Token($username, $token);
    if (!$t->isValid() || $t->getAccess() != "app_access") {
        $url = "https://" . $_SERVER["HTTP_HOST"] . "/auth/login.html";
        header("Location: " . $url . "\n", true, 302);
        echo "<script type='text/javascript'>setTimeout( () => { window.location.replace('$url');}, 500);</script>";
        die("Redirecting you to the login page. click <a href='$url'>here</a> if you are not redirected.");
    } /* else {
        $url = "https://" . $_SERVER["HTTP_HOST"] . "/app/index.html";
        header("Location: " . $url . "\n", true, 302);
        echo "<script type='text/javascript'>setTimeout( () => { window.location.replace('$url');}, 500);</script>";
        die("Redirecting you to the application. click <a href='$url'>here</a> if you are not redirected.");
    } */

} else {
    // no cookies found, go login
    $url = "https://" . $_SERVER["HTTP_HOST"] . "/auth/login.html";
    header("Location: " . $url . "\n", true, 302);
    echo "<script type='text/javascript'>setTimeout( () => { window.location.replace('$url');}, 500);</script>";
    die("Redirecting you to the login page. click <a href='$url'>here</a> if you are not redirected.");
}
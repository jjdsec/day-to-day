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

    require_once(__DIR__ . "/Token.class.php");
    $t = new Token($username, $token);
    if (!$t->isValid() || $t->getAccess() != "app_access") {
        $url = "https://" . $_SERVER["HTTP_HOST"] . "/auth/login.html";
        header("Location: " . $url . "\n", true, 302);
        echo "<script type='text/javascript'>setTimeout( () => { window.location.replace('$url');}, 500);</script>";
        die("Redirecting you to the login page. click <a href='$url'>here</a> if you are not redirected.");
    } 

    // untested
    if ($t->getTimeTilExpire() < (60*60*24*7)) { // renew token 7 days before expiration
        $mailBody = "Token has been renewed: \r\nToken: " . $t->getToken() . "\r\nTime: " . $t->getTimeTilExpire() . "\r\n\r\n";
        $nt = new Token($username);
        if ($nt->setAccess("app_access")) {
            setcookie("token", base64_encode($username . ":" . $nt->getToken()), time()+(60*60*24*30), "/", $_SERVER["HTTP_HOST"], true, false);
            setcookie("username", $username, time()+(60*60*24*30), "/", $_SERVER["HTTP_HOST"], true, false);
            $t->consume();
            $mailBody .= "Renewed Token: \r\nToken: " . $nt->getToken() . "Time: " . $nt->getTimeTilExpire();
        }
        mail("jimmybear217@gmail.com", "Token renewal", $mailBody);
    }

} else {
    // no cookies found, go login
    $url = "https://" . $_SERVER["HTTP_HOST"] . "/auth/login.html";
    header("Location: " . $url . "\n", true, 302);
    echo "<script type='text/javascript'>setTimeout( () => { window.location.replace('$url');}, 500);</script>";
    die("Redirecting you to the login page. click <a href='$url'>here</a> if you are not redirected.");
}
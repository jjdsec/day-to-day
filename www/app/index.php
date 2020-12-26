<?php

    /**
     * router.php
     * 
     * read cookies
     * verify authentification
     * redirect to app.html or to ../auth/login.html
     */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>router.php</title>
</head>
<body>
    <h1>router.php</h1>
    <a href="/app/app.html">App</a>
    <a href="/auth/login.html">Login</a>

    <pre><?php


    require_once("../auth/checkLogin.inc.php");


        ?></pre>
</body>
</html>
<?php

    /**
     * index.php
     * 
     * holds the first part of this application
     * @author JimmyBear217
     * @since 20201225
     * @uses ../auth/checkLogin.inc.php
     * @uses ../auth/Token.class.php
     * 
     */


    require_once("../auth/checkLogin.inc.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="manifest.webmanifest">
    <title>router.php</title>
</head>
<body>
    <header>
        <img src="/app/assets/img/menu.png" alt="menu">
        <h1 id="title">Day-to-Day</h1>
    </header>
    <nav id="side-menu">
        <a href="/app/app.html">App</a>
        <a href="/auth/login.html">Login</a>
    </nav>
    <div id="page-view">
        
    </div>
    <footer id="buttom-tabs">
        <a href="/app/#tasks"><img src="/app/assets/img/tasks.png" alt="tasks"></a>
        <a href="/app/#requests"><img src="/app/assets/img/requests.png" alt="requests"></a>
        <a href="/app/#notes"><img src="/app/assets/img/notes.png" alt="notes"></a>
        <a href="/app/#settings"><img src="/app/assets/img/settings.png" alt="settings"></a>
    </footer>
</body>
<!-- global scripts -->
<script src="pages.js"></script>
<script src="serviceWorkerMgr.js"></script>

<!-- page scripts  -->
<script src="tasks.page.js"></script>

<!-- startup command and scripts -->
<script type="text/javascript">
    changePage('tasks.js');
</script>
</html>
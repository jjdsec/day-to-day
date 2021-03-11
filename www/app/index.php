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
    <link rel="manifest" href="/app.webmanifest">
    <title>router.php</title>
</head>
<body>
    <header>
        <img src="/app/assets/img/menu.png" alt="menu">
        <h1 id="title">Day-to-Day</h1>
    </header>
    <nav id="side-menu">
        <a href="/app/">App</a>
        <a href="/auth/">Login</a>
        <a href="/app/#settings">Settings</a>
    </nav>
    <div id="page-view">
        
    </div>
    <footer id="buttom-tabs">
        <a href="javascript:changePage('Tasks');"><img src="/app/assets/img/tasks.png" alt="tasks"></a>
        <a href="javascript:changePage('Requests');"><img src="/app/assets/img/requests.png" alt="requests"></a>
        <a href="javascript:changePage('Notes');"><img src="/app/assets/img/notes.png" alt="notes"></a>
        <a href="javascript:changePage('Affirmations');"><img src="/app/assets/img/affirmations.png" alt="affirmations"></a>
    </footer>   
    <!-- global scripts -->
    <script src="pages.js"></script>
    <script src="serviceWorkerMgr.js"></script>

    <!-- page scripts  -->
    <script src="tasks.page.js"></script>
    <script src="affirmations.pages.js"></script>

    <!-- startup command and scripts -->
    <script type="text/javascript">
        changePage('Tasks');
    </script>
    </body>
</html>
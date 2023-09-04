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
    <link rel="stylesheet" type="text/css" href="/css/app-page.css">
    <link rel="stylesheet" type="text/css" href="/css/app-menus.css">
    <title>Day to Day</title>
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
    <div id="page-view"></div>
    <footer id="bottom-tabs">
        <!-- <img onClick="changePage('Weekly');" src="/app/assets/img/goal.png" alt="Weekly Goal">
        <img onClick="changePage('Tasks');" src="/app/assets/img/tasks.png" alt="tasks">
        <img onClick="changePage('Requests');" src="/app/assets/img/requests.png" alt="requests">
        <img onClick="changePage('Notes');" src="/app/assets/img/notes.png" alt="notes">
        <img onClick="changePage('Affirmations');" src="/app/assets/img/affirmations.png" alt="affirmations"> -->
    </footer>   
    <!-- global scripts -->
    <script src="pages.js"></script>
    <script src="serviceWorkerMgr.js"></script>

    <!-- page scripts  -->
    <script src="weekly.page.js"></script>
    <script src="tasks.page.js"></script>
    <script src="affirmations.page.js"></script>

    <!-- startup command and scripts -->
    <script type="text/javascript">
        changePage('Weekly');
    </script>
    </body>
</html>
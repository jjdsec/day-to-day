<?php

    session_start();

    /**
     * @file index.php
     * @brief This file is the main entry point for the website.
     * @details This file is the main entry point for the website. It includes the header and footer files, and the main content file. It also redirects to setup and login if needed
     */

    // check if all required files exist
    if (!file_exists("config.php")) {
        // header("Location: setup.php");
        // exit;
    } else {
        require_once("config.php");
    }

    // resolve page
    $pagePath = "index";
    if (!empty($_GET["page"]))
        $pagePath = $_GET["page"];
    $pagePath = str_replace([".", "'", "\""], "", $pagePath);
    $pagePath = htmlspecialchars($pagePath);
    require_once("inc/resolve.php");

    // check if the user is logged in, logging in, setting up, or registering
    if (!isset($_SESSION["user"]) && $page["require_login"]) {
        header("Location: login.php");
        exit;
    }

    // check if the user is an admin
    if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
        $ADMIN = true;
    } else {
        $ADMIN = false;
    }

    // check if headers and footers and main exist
    if (!file_exists("inc/header.php")) {
        die("Error: header.php not found");
    }
    if (!file_exists("inc/footer.php")) {
        die("Error: footer.php not found");
    }
    if (!file_exists("pages/" . $page["file"] . ".php")) {
        die("Error: content file not found");
    }

    // include header
    require_once("inc/header.php");

    // include main content if it exists
    require_once("pages/" . $page["file"] . ".php");

    // include footer
    require_once("inc/footer.php");

?>
<?php

    /**
     * @file resolve.php
     * @brief This file resolves the page to be displayed.
     * @details This file resolves the page to be displayed. It is included in index.php.
     */

    // list of pages
    $pageList = [
        "index" => [
            "title" => "Home",
            "file" => "index",
            "require_login" => false,
            "require_admin" => false,
            "require_setup" => false
        ],
    ];

    if (empty($pagePath)){
        $page = $pageList["index"];
    } else {
        $page = $pageList[$pagePath];
    }
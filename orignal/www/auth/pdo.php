<?php
    try {
        $db = new PDO("mysql:host=localhost;dbname=u947544758_day2day;charset=utf8;port=3306","u947544758_day2day","uA&5y17*r1");
    } catch (PDOException $e) {
        if (function_exists(display_error)) {
            display_error("Unable to connect to the database");
        } else {
            die("unable to connect to the database");
        }
        error_log("Unable to connect to DB: " . $e->getMessage());
    }
<?php

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'password');
define('DBNAME', 'easy_crud');
$link = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

    /* If connection fails for some reason */
    if ($link->connect_error) {
        die('Error, Database connection failed: (' . $link->connect_errno . ') ' . $link->connect_error);
    }
$base = 'http://'.$_SERVER['HTTP_HOST'].'/EasyCRUD/';
require 'EasyCRUD.php';
    
    ?>
    
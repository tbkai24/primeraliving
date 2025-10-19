<?php


    session_start();
    session_unset();
    session_destroy();
    header("location: http://localhost/primeraliving/admin-panel/auth/login.php");
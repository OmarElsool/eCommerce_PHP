<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL); // display all kind of errors

    include("admin/connect.php");

    $sessionUser = ''; // if user doesn't login session = '' else sessionUser = name of user
    if(isset($_SESSION['member'])){
        $sessionUser = $_SESSION['member'];
    }

    $tpl = 'include/templates/';
    $lang = 'include/lang/';
    $func = 'include/functions/';

    include( $func . "func.php");
    include( $tpl . "header.php");
    include( $lang . "eng.php");
    include( $tpl . "navbar.php");


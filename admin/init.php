<?php

    include("connect.php");

    $tpl = 'include/templates/';
    $lang = 'include/lang/';
    $func = 'include/functions/';

    include( $func . "func.php");
    include( $tpl . "header.php");
    include( $lang . "eng.php");


    if(!isset($noNavbar)){
        include( $tpl . "navbar.php");
    }

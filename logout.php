<?php

    session_start();

    session_unset(); // unset the data

    session_destroy(); // destroy the data

    header('location: index.php');

    exit();
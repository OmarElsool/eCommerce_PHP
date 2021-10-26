<?php

    session_start();
    $pageTitle = '';
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){

        include('init.php');

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){
            echo 'welcome to manage page';
        }elseif($do == 'Add'){

        }elseif($do == 'insert'){

        }elseif($do == 'Edit'){

        }elseif($do == 'Update'){

        }elseif($do == 'Delete'){

        }elseif($do == 'Approve'){

        }

        include($tpl . 'footer.php');
    }else{
        header('location: index.php');
        exit();
    }
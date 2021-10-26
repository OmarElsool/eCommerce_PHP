<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@200&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@200&family=Kaisei+Tokumin&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./layout/css/front.css">
    <title>
        <?php Title(); ?>
    </title>
</head>
<body>
<div class="upper-bar">
    <div class="container">
        <?php 
            if(isset($_SESSION['member'])){

                
                echo '<span class=" hello-center"><img src="layout/img/avatar.png" alt="..." class="img-thumbnail rounded-circle img-fluid"> Welcome ' . $_SESSION['member'] . ' </span>';
                
                echo '<div class="nav-btn btn btn-danger float-end"><a href="logout.php">Logout</a></div>';
                
                echo '<div class="nav-btn btn btn-success float-end"><a href="profile.php">My Profile</a></div>';

                echo '<div class="nav-btn btn btn-info float-end"><a href="newad.php">New Ad</a></div>';

                $userStatus = checkStatus($_SESSION['member']);
                if($userStatus == 1){ // if userStatus == 1 that mean the user is not active ( RegStatus = 0 )
                    
                }
            }else{
        ?>
        <a href="login.php">
            <div class="btn btn-success">
                <span>Login / Signup</span>
            </div>
        </a>
        <?php } ?>
    </div>
</div>
    
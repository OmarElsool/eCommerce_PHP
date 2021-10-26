<?php 
    session_start();

    $pageTitle = 'Profile';

    include("init.php");

    if(isset($_SESSION['member'])){
    $getUser = $con->prepare('SELECT * FROM users WHERE username = ?');

    $getUser->execute(array($sessionUser));

    $info = $getUser->fetch();
    
?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header">
                My Information
            </div>
            <div class="card-body">
                <ul class="list-unstyled list-users">
                    <li> <i class="bi bi-unlock"></i>  <span>Name</span> : <?php echo $info['username'] ?> </li>
                    <li> <i class="bi bi-envelope"></i>  <span>Email</span> : <?php echo $info['Email'] ?> </li>
                    <li> <i class="bi bi-person"></i>  <span>Full Name</span> : <?php echo $info['FullName'] ?> </li>
                    <li> <i class="bi bi-calendar-check"></i>  <span>Register Date</span> : <?php echo $info['Date'] ?> </li>
                </ul>
                <a href="#">
                    <div class="btn btn-success">
                        Edit Information
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="my-Ads block">
    <div class="container">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header">
                My Ads
            </div>
            <div class="card-body">
                <div class="row md-3">
                    <?php
                        $items = getItems('Member_ID',$info['UserID'],1);
                        foreach($items as $item){ 
                            echo'<div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card item-box text-white bg-dark mb-3">
                                <img src="layout/img/avatar.png" alt="..." class="card-img-top img-thumbnail img-fluid">
                                    <div class="caption card-body">';
                                    if($item['Approve'] == 0){ echo '<span class="btn btn-danger">Not Approved</span>';}
                                    echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>
                                    <p>'.$item['Description'].'</p>
                                    <span class="price">$'.$item['Price'].'</span>
                                    <div class="date text-muted">'.$item['Add_Date'].'</div>
                                    </div>
                                </div>
                            </div>';
                        }
                    ?>
                </div>
            </div>
            <div class="card-footer text-muted">
                <a href="newad.php" class="btn btn-primary">Add New Ad</a>
            </div>
        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header">
                My Comments
            </div>
            <div class="card-body">
                <?php
                $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id =  ?");

                $stmt->execute(array($info['UserID']));

                $comments = $stmt->fetchAll();
                
                foreach($comments as $comment){
                    echo '<p class=profile-comment>'. $comment['comment'] .'</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    }else{
        header('Location: login.php');
        exit();
    }
    include("include/templates/footer.php");
?>
<?php
    session_start();
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){
        $pageTitle = 'Dashboard';
        include('init.php');

        $numUsers = 5; // num of latest users
        $latestUsers = latestItems('*','users','UserID',$numUsers); // latest users array

        $numItems = 5; // num of latest items
        $latestItems = latestItems('*','items','Item_ID',$numItems); // latest items array
        
        ?>

        <div class="container home-stats text-center">
            <h1>DashBoard</h1>
            <div class="row mb-3">
                <div class="col-md-6 col-lg-6 col-xlg-3">
                    <div class="stat st-members">
                        <i class="bi bi-people-fill"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo numItems('UserID','users') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xlg-3">
                    <div class="stat st-pending">
                        <i class="bi bi-person-plus-fill"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=Pending">
                                <?php echo checkItems('RegStatus' , 'users' , 0) ?>
                            </a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xlg-3">
                    <div class="stat st-items">
                        <i class="bi bi-tags-fill"></i>
                        <div class="info">
                            Total Items 
                            <span><a href="items.php"><?php echo numItems('Item_ID','items') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xlg-3">
                    <div class="stat st-comments">
                        <i class="bi bi-chat-left-fill"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo numItems('c_id','comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container last">
            <div class="row mb-3">
                <!-- Latest Users -->
                <div class="col-sm-6 mb-3">
                    <div class="card border-dark">
                        <div class="card-header">
                            <i class="bi bi-people"></i> Last <?php echo $numUsers; ?> Registered Users
                            <span class="toggle-info float-end">
                                <i class="bi bi-dash-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-users"> 
                            <?php                           
                                foreach($latestUsers as $user){

                                echo '<li>'  . $user['username'] . 
                                    '<a href="members.php?do=Edit&userid= '. $user['UserID'] .' ">
                                        <span class="btn btn-success float-end">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </span>
                                    </a>';
                                    if($user['RegStatus'] == 0){
                                        echo "<a href='members.php?do=Approve&userid=" . $user['UserID'] . "' class='btn btn-info float-end approve'><i class='bi bi-check2-square'></i> Approve </a>";
                                    }
                                '</li>';
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Latest Items -->
                <div class="col-sm-6 mb-3">
                    <div class="card border-dark">
                        <div class="card-header">
                            <i class="bi bi-tags"></i> Last <?php echo $numItems; ?> Items
                            <span class="toggle-info float-end">
                                <i class="bi bi-dash-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-users"> 
                            <?php                           
                                foreach($latestItems as $item){

                                echo '<li>'  . $item['Name'] . 
                                    '<a href="items.php?do=Edit&itemid= '. $item['Item_ID'] .' ">
                                        <span class="btn btn-success float-end">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </span>
                                    </a>';
                                    if($item['Approve'] == 0){
                                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info float-end approve'><i class='bi bi-check2-square'></i> Approve </a>";
                                    }
                                '</li>';
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Latest Comments -->
                <div class="col-sm-6">
                    <div class="card border-dark">
                        <div class="card-header">
                            <i class="bi bi-chat-left"></i> Last <?php echo $numItems; ?> Comments
                            <span class="toggle-info float-end">
                                <i class="bi bi-dash-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <?php                           
                                $stmt = $con->prepare("SELECT 
                                                                comments.*,users.username AS Member
                                                        FROM 
                                                                comments
                                                            INNER JOIN 
                                                                users
                                                            ON
                                                                users.UserID = comments.user_id
                                                            ORDER BY
                                                                c_id DESC
                                                            LIMIT 5");

                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                                foreach($comments as $comment){
                                    echo '<div class="comment-box row">';
                                        echo '<span class="member-m col-md-4 col-lg-2 text-center"><a href="members.php?do=Edit&userid='. $comment['user_id'] .'">'
                                        . $comment['Member'] . '</a></span>';
                                        echo '<p class="member-c col-md-8 col-lg-10">'. $comment['comment'] .'</p>';
                                    echo '</div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        include($tpl . 'footer.php');
    }else{
        header('location: index.php');
        exit();
    }
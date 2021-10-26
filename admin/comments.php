<?php

    session_start();
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){
        $pageTitle = 'Comments';
        include('init.php');

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){

            
            $stmt = $con->prepare("SELECT 
                                        comments.*,items.Name AS Item_Name,users.username AS Member
                                   FROM 
                                        comments
                                    INNER JOIN
                                        items
                                    ON
                                        items.Item_ID = comments.item_id
                                    INNER JOIN 
                                        users
                                    ON
                                        users.UserID = comments.user_id");

            $stmt->execute();

            $rows = $stmt->fetchAll();


            
            ?>
            
            <h1 class="text-center">Management Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="text-center table table-bordered main-table">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" . $row['c_id'] . "</td>";
                                    echo "<td>" . $row['comment'] . "</td>";
                                    echo "<td>" . $row['Item_Name'] . "</td>";
                                    echo "<td>" . $row['Member'] . "</td>";
                                    echo "<td>" . $row['comment_date'] . "</td>";
                                    echo "<td >
                                        <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='bi bi-x-square'></i> Delete </a>";

                                    if($row['status'] == 0){
                                        echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info approve'><i class='bi bi-check2-square'></i> Approve </a>";
                                    }

                                    echo "</td>";
                                echo "</tr>";
                                
                        }
                        ?>                        
                    </table>
                </div>    
            </div>

        <?php 
    
    }elseif($do == 'Delete'){

        echo "<div class='container'>";
        echo "<h1 class='text-center'>Delete Comment</h1>";

        // check if the req is GET and the comid is number 
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        $check = checkItems('c_id','comments',$comid);

        if($check > 0){
            
            // :then any name to bind it with $userid
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :cid");

            $stmt->bindParam(":cid" , $comid);

            $stmt->execute();

            $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Deleted</div>';
            redirectHome($theMsg,'back');

        }else{
            echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
                redirectHome($theMsg,'back');    
            echo "</div>"; 
        }

        echo "</div>";

    }elseif($do == 'Approve'){
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Approve comment</h1>";

         // check if the req is GET and the comid is number 
         $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

         $check = checkItems('c_id','comments',$comid);
 
        if($check > 0){
             
            // :then any name to bind it with $comid
            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

            $stmt->execute(array($comid));

            $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Updated</div>';
            redirectHome($theMsg,'back');
 
        }else{
            echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
                redirectHome($theMsg,'back');    
            echo "</div>"; 
        }

        echo "</div>";
    }

        include($tpl . 'footer.php');
    }else{
        header('location: index.php');
        exit();
    }
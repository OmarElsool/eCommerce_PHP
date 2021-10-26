<?php

    session_start();
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){
        $pageTitle = 'Member';
        include('init.php');

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){

            $query = '';

            if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
                $query = 'AND RegStatus = 0';
            }
            
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID != 1 $query");

            $stmt->execute();

            $rows = $stmt->fetchAll();


            
            ?>
            
            <h1 class="text-center">Management Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="text-center table table-bordered main-table manage-members">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registerd Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" . $row['UserID'] . "</td>";
                                    if(!empty($row['avatar'])){
                                        echo "<td> <img src='uploads/". $row['avatar'] . "' alt='...'></td>";
                                    }else{
                                        echo "<td> <img src='uploads/avatar.png' alt='...'></td>";
                                    }
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['Email'] . "</td>";
                                    echo "<td>" . $row['FullName'] . "</td>";
                                    echo "<td>" . $row['Date'] . "</td>";
                                    echo "<td >
                                        <a href='members.php?do=Edit&userid=" . $row['UserID'] ."' class='btn btn-success'><i class='bi bi-pencil-square'></i> Edit</a>
                                        <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='bi bi-x-square'></i> Delete </a>";

                                    if($row['RegStatus'] == 0){
                                        echo "<a href='members.php?do=Approve&userid=" . $row['UserID'] . "' class='btn btn-info approve'><i class='bi bi-check2-square'></i> Approve </a>";
                                    }

                                    echo "</td>";
                                echo "</tr>";
                                
                        }
                        ?>                        
                    </table>
                </div>    
                <a href="members.php?do=Add" class="add-btn btn btn-primary" ><i class="bi bi-plus"></i>Add Member</a>

            </div>

        <?php }elseif($do == 'Add'){

            ?>  <h1 class="text-center">Add New Member</h1>
                <div class="container">
                    <form action="?do=insert" method="POST" enctype="multipart/form-data">
                        <!-- Username -->
                        <div class="row mb-3 form-pos">
                            <label for="Username" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Username</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="username" class="form-control" id="Username" autocomplete="off" required="required" placeholder="Please Type username">
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="row mb-3 form-pos">
                            <label for="Password" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Password</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="password" name="password" class="password form-control" id="Password" required="required" autocomplete="new-password" placeholder="Please Type Password">
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="row mb-3 form-pos">
                            <label for="Email" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Email</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="email" name="email" class="form-control" id="Email" required="required" placeholder="Please Type Valid Email">
                            </div>
                        </div>
                        <!-- Full Name -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Full Name</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="FullName" class="form-control" id="FullName" required="required" placeholder="Your Full Name">
                            </div>
                        </div>
                        <!-- Image -->
                        <div class="row mb-3 form-pos">
                            <label for="Avatar" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">User Avatar</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="file" name="avatar" class="form-control" id="Avatar" required="required">
                            </div>
                        </div>
                        <!-- submit -->
                        <div class="row mb-3">
                            <div class="text-center col-sm-12">
                                <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>

            <?php

        }elseif($do == 'insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<div class='container'>";
                echo "<h1 class='text-center'>Add New Member</h1>";

                // avatar upload
                $avatar = $_FILES['avatar'];

                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];
                
                // list of allowed types to upload
                $avatarAllowExtension = array('jpeg','jpg','png','gif');

                $avatarExplode = explode('.',$avatarName);
                $avatarExtension = strtolower(end( $avatarExplode)); // end get the last element

                $username = $_POST['username'];
                $FullName = $_POST['FullName'];
                $email = $_POST['email'];
                $pass = $_POST['password'];

                $hashpass = sha1($_POST['password']);
    
                $formerrors = array();
    
                if(empty($username)){
                    $formerrors[] = 'Username can not be empty';
                }elseif(strlen($username < 4)){
                    $formerrors[] = 'Username can not be lower than 4 char';
                }
                if(empty($FullName)){
                    $formerrors[] = 'FullName can not be empty';
                }
                if(empty($pass)){
                    $formerrors[] = 'Password can not be empty';
                }
                if(empty($email)){
                    $formerrors[] = 'Email can not be empty';
                }
                if(! empty($avatarName) && ! in_array($avatarExtension,$avatarAllowExtension)){
                    $formerrors[] = 'This Extension Is Not Allowed';
                }
                if(empty($avatarName)){
                    $formerrors[] = 'Image Is Required';
                }
                if($avatarSize > 4194304){
                    $formerrors[] = 'Image Can Not Be More Than 4MB';
                }
    
                foreach($formerrors as $errors){
                    echo '<div class="alert alert-danger">' . $errors .'</div>';
                }
                
                // if there is no error insert the new user in database
                if(empty($formerrors)){

                    $check = checkItems("username","users",$username);
                    if($check == 1 ){
                        $theMsg = '<div class="alert alert-danger">This Username Is Taken</div>';
                        redirectHome($theMsg,'back');
                    }else{
    
                        $avatar = rand(0,10000) . '_' . $avatarName;
                        
                        move_uploaded_file($avatarTmp,"uploads\\" . $avatar);

                        $stmt = $con->prepare("INSERT INTO
                                            users(username,password,Email,FullName,RegStatus,Date,avatar) 
                                            VALUES(:user,:pass,:email,:fullname, 1 ,now(),:avatar)");

                        $stmt->execute(array(
                            "user"      => $username,
                            "pass"      => $hashpass,
                            "email"     => $email,
                            "fullname"  => $FullName,
                            "avatar"  => $avatar
                        ));

                        $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Inserted</div>';
                        redirectHome($theMsg,"back");
                    }
                }
    
            }else{
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>You Should Come From POST Method</div>";
                redirectHome($theMsg);
                echo "</div>";
            }
            echo "</div>";
        }

    elseif($do == 'Edit'){ // Edit Page 
        
        // check if the req is GET and the userid is number 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        
        // select all data with this id || we use ? to execute the variable next 
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? ");
        // search on userid in database
        $stmt->execute(array($userid));
        // get the data in array form
        $row = $stmt->fetch(); // Array ( [UserID] => 1 [0] => 1 [username] => omar [1] => omar [password] => 601f1889667efaebb33b8c12572835da3f027f78 [2] => 601f1889667efaebb33b8c12572835da3f027f78 )
        // check if the id in database
        $count = $stmt->rowCount();

        if($count > 0){

        ?>    <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form action="?do=Update" method="POST">
                        <!-- hidden input to send the value to database -->
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <!-- Username -->
                        <div class="row mb-3 form-pos">
                            <label for="Username" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Username</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="username" class="form-control" id="Username" autocomplete="off" value="<?php echo $row['username'] ?>" required="required">
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="row mb-3 form-pos">
                            <label for="Password" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Password</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>">
                                <input type="password" name="newpassword" class="password form-control" id="Password" autocomplete="new-password" placeholder="Leave Empty If You Don't Want To Change">
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="row mb-3 form-pos">
                            <label for="Email" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Email</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="email" name="email" class="form-control" id="Email" value="<?php echo $row['Email'] ?>" required="required">
                            </div>
                        </div>
                        <!-- Full Name -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Full Name</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="FullName" class="form-control" id="FullName" value="<?php echo $row['FullName'] ?>" required="required">
                            </div>
                        </div>
                        <!-- submit -->
                        <div class="row mb-3">
                            <div class="text-center col-sm-12">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>

            <?php
        }else{
            echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
                redirectHome($theMsg);
            echo "</div>"; 
        }
    }elseif($do == 'Update'){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo "<div class='container'>";
            echo "<h1 class='text-center'>Update Member</h1>";
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $FullName = $_POST['FullName'];
            $email = $_POST['email'];

            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            $formerrors = array();

            if(empty($username)){
                $formerrors[] = 'Username can not be empty';
            }elseif(strlen($username < 4)){
                $formerrors[] = 'Username can not be lower than 4 char';
            }
            if(empty($FullName)){
                $formerrors[] = 'FullName can not be empty';
            }
            if(empty($email)){
                $formerrors[] = 'Email can not be empty';
            }

            foreach($formerrors as $errors){
                echo '<div class="alert alert-danger">' . $errors .'</div>';
            }
            
            // if there is no error update the database
            if(empty($formerrors)){
                
                $stmt2 = $con->prepare("SELECT * FROM users WHERE username = ? AND UserID != ?"); // UserID not the same of that user

                $stmt2->execute(array($username,$id));

                $count = $stmt2->rowCount();

                if($count == 1){
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-danger">This User Is Exist</div>';
                    redirectHome($theMsg,'back');
                    echo "</div>"; 
                }else{
                    $stmt = $con->prepare("UPDATE users SET username = ? ,Email = ? ,FullName = ? ,password = ? WHERE UserID = ?");
                    $stmt->execute(array($username,$email,$FullName,$pass,$id));

                    $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back');
                }


            }

        }else{
            echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">You Should Come From POST Method</div>';
                redirectHome($theMsg);    
            echo "</div>"; 
        }
        echo "</div>";
    }elseif($do == 'Delete'){

        echo "<div class='container'>";
        echo "<h1 class='text-center'>Delete Member</h1>";

        // check if the req is GET and the userid is number 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // select all data with this id || we use ? to execute the variable next 
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? ");
        // search on userid in database
        $stmt->execute(array($userid));
        // check if the id in database
        $count = $stmt->rowCount();

        if($count > 0){
            
            // :then any name to bind it with $userid
            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :userid");

            $stmt->bindParam(":userid" , $userid);

            $stmt->execute();

            $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Deleted</div>';
            redirectHome($theMsg,'back');

        }else{
            echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
                redirectHome($theMsg);    
            echo "</div>"; 
        }

        echo "</div>";

    }elseif($do == 'Approve'){
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Approve Member</h1>";

         // check if the req is GET and the userid is number 
         $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

         // select all data with this id || we use ? to execute the variable next 
         $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? ");
         // search on userid in database
         $stmt->execute(array($userid));
         // check if the id in database
         $count = $stmt->rowCount();
 
         if($count > 0){
             
             // :then any name to bind it with $userid
             $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
 
             $stmt->execute(array($userid));
 
             $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Updated</div>';
             redirectHome($theMsg,'back');
 
         }else{
             echo "<div class='container'>";
                 $theMsg = '<div class="alert alert-danger">There Is No Such ID</div>';
                 redirectHome($theMsg);    
             echo "</div>"; 
         }

        echo "</div>";
    }

        include($tpl . 'footer.php');
    }else{
        header('location: index.php');
        exit();
    }
<?php
    session_start();
    $pageTitle = 'Login/signup';
    if(isset($_SESSION['member'])){
        header('location: index.php'); // go to dashboard.php page
    }
    include('init.php');

        // check if user come from post request
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['login'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hashpass = sha1($password);

            // check if the user in database || we use ? to execute the variable next 
            $stmt = $con->prepare("SELECT
                                        UserID, username, password 
                                    FROM 
                                        users 
                                    WHERE 
                                        username = ? 
                                    AND 
                                        password = ?");

            // search on user and pass in database
            $stmt->execute(array($username,$hashpass));

            $get = $stmt->fetch();
            // check how many row in database for member
            $count = $stmt->rowCount();

            // if the user in database
            if($count > 0){
                $_SESSION['member'] = $username; // registere the username in session 
                $_SESSION['uid'] = $get['UserID']; // registere the ID in session 
                header('location: index.php'); // go to dashboard.php page
                exit();
            }

        }else{

            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $email = $_POST['email'];

            $successMsg;
            $formErrors = array();
            // check if the username is valid and more than 4 char
            if(isset($username)){
                $filterUser = filter_var($username,FILTER_SANITIZE_STRING);
                if(strlen($filterUser) < 4){
                    $formErrors[] = 'Username Must Be More Than 4 Characters';
                }
            }
            // check if the first password == second one
            if(isset($password) && isset($password2)){
                
                if(empty($password)){

                    $formErrors[] = 'Password Can Not Be Empty';
                }
                
                if(sha1($password) !== sha1($password2)){
                    $formErrors[] = 'Password Is Not Match';
                }
            }
            // check if the email is valid
            if(isset($email)){

                $filterEmail = filter_var($email,FILTER_SANITIZE_EMAIL);

                if(!filter_var($filterEmail,FILTER_VALIDATE_EMAIL)){
                    $formErrors[] = 'This Email Is Not Valid';
                }
            }

            // if there is no error insert the new user in database
            if(empty($formErrors)){
                //check if user exist in database
                $check = checkItems("username","users",$username);
                if($check == 1 ){
                    $formErrors[] = 'This User Is Exist';
                }
                
                if(empty($formErrors)){
                    $stmt = $con->prepare("INSERT INTO
                                            users(username,password,Email,RegStatus,Date) 
                                            VALUES(:user,:pass,:email, 1 ,now())");

                    $stmt->execute(array(
                        "user"      => $username,
                        "pass"      => sha1($password),
                        "email"     => $email,
                    ));
                    $successMsg = 'The Account Have Been Created successfully';
                }
            }
        }
    }
?>

    <div class="container login-page">
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
        <!-- login form -->
        <form class="login eye-relative" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="input-container">
                <input class="form-control" type="text" placeholder="Username" required name="username" autocomplete="off">
            </div>
            <div class="input-container">
                <input class="form-control password" type="password" placeholder="Password" required name="password" autocomplete="new-password">
            </div>
            <div class="d-grid gap-2">
            <input class="btn btn-primary" type="submit" name="login" value="login">
            </div>
        </form>
        <!-- signup form -->
        <form class="signup eye-relative" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> 
            <div class="input-container">
                <input class="form-control" pattern=".{4,}" title="username must be > 4 chars" type="text" placeholder="Username"  name="username" autocomplete="off">
            </div>
            <div class="input-container">
                <input class="form-control password" minlength="4" type="password" placeholder="Password"  name="password" autocomplete="new-password">
            </div>
            <div class="input-container">
                <input class="form-control password" minlength="4" type="password" placeholder="Rewrite The Password"  name="password2" autocomplete="new-password">
            </div>
            <div class="input-container">
                <input class="form-control" type="email" placeholder="Email"  name="email">
            </div>
            <div class="d-grid gap-2">
            <input class="btn btn-success" type="submit" name="signup" value="Singup">
            </div>
        </form>
        <div class="text-center errs-massage">
            <?php
            if(!empty($formErrors)){
                foreach($formErrors as $error){
                    echo '<div style="margin-bottom:10px" class="btn btn-danger">'. $error .'</div> <br>';
                }
            }
            if(isset($successMsg)){
                echo '<div class="btn btn-success">' . $successMsg . '</div>';
            }
            ?>
        </div>
    </div>

<?php
    include('include/templates/footer.php');
?>
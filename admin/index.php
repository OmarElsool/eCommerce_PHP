<?php 
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){
        header('location: dashboard.php'); // go to dashboard.php page
    }
    
    include("init.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = $_POST['user'];
        $password = $_POST['password'];
        $hashpass = sha1($password);

        // check if the user in database || we use ? to execute the variable next 
        $stmt = $con->prepare("SELECT
                                    UserID ,username,password 
                                FROM 
                                    users 
                                WHERE 
                                    username = ? 
                                AND 
                                    password = ?
                                AND 
                                    GroupID = 1
                                LIMIT 1");
        // search on user and pass in database
        $stmt->execute(array($username,$hashpass));
        // get the data in array form
        $row = $stmt->fetch(); // Array ( [UserID] => 1 [0] => 1 [username] => omar [1] => omar [password] => 601f1889667efaebb33b8c12572835da3f027f78 [2] => 601f1889667efaebb33b8c12572835da3f027f78 )
        // check how many row in database for admin
        $count = $stmt->rowCount();

        // if the user in database
        if($count > 0){
            $_SESSION['username'] = $username; // registere the username in session 
            $_SESSION['ID'] = $row['UserID']; // registere the id  
            header('location: dashboard.php'); // go to dashboard.php page
            exit();
        }

    }


?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="container eye-relative">
        <h3 class="text-center">Login</h3>
        <input class="form-control" type="text" placeholder="Username" name="user" autocomplete="off">
        <input class="form-control password" type="password" placeholder="Password" name="password" autocomplete="new-password">
        <div class="d-grid gap-2">
            <input class="btn btn-primary" type="submit" value="login">
        </div>
    </div>
</form>

<?php 
include("include/templates/footer.php");
?>
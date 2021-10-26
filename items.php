<?php 
    session_start();

    $pageTitle = 'Show Items';

    include("init.php");

    // check if the req is GET and the itemid is number 
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    // select all data with this id || we use ? to execute the variable next 
    $stmt = $con->prepare("SELECT 
                                items.* , categories.Name AS Cat_Name , users.username 
                            FROM 
                                items 
                            INNER JOIN 
                                categories 
                            ON 
                                categories.ID = items.Cat_ID 
                            INNER JOIN 
                                users 
                            ON 
                                users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ?
                            AND 
                                Approve = 1");
    // search on itemid in database
    $stmt->execute(array($itemid));

    $count = $stmt->rowCount();

    if($count > 0){

    // get the data in array form
    $item = $stmt->fetch();

?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row md-3">
            <div class="col-md-3">
                <img src="layout/img/avatar.png" alt="..." class="card-img-top img-thumbnail img-fluid">
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-calendar-check"></i> <span>Added In</span> : <?php echo $item['Add_Date'] ?></li>
                    <li><i class="bi bi-cash"></i> <span>Price</span> : $<?php echo $item['Price'] ?></li>
                    <li><i class="bi bi-bank"></i> <span>Made In</span> : <?php echo $item['Country_Made'] ?></li>
                    <li><i class="bi bi-tags"></i> <span>Category</span> : <a href="categories.php?pageid=<?php $item['Cat_ID'] ?>"><?php echo $item['Cat_Name'] ?></a></li>
                    <li><i class="bi bi-person"></i> <span>Added By</span> : <a href="#"><?php echo $item['username'] ?></a></li>
                    <li class="tags-items"><i class="bi bi-tags">
                    </i> <span>Tags</span> : 
                    <?php
                        $allTags = explode(",",$item['tags']);
                        foreach($allTags as $tag){
                            $tag = str_replace(' ', '',$tag);
                            $lowertag = strtolower($tag);
                            if(! empty($tag)){
                                echo "<a href='tags.php?name={$lowertag}'> " . $tag . '</a>';
                            }
                        }
                    ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <?php if(isset($_SESSION['member'])){ ?>
        <div class="row md-3">
            <div class="col-md-3 offset-md-3">
                <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                        <textarea required name="comment"></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment">
                    </form>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST'){
                            $comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                            $userid = $_SESSION['uid'];
                            $itemid =  $item['Item_ID'];

                            if(! empty($comment)){
                                $stmt = $con->prepare("INSERT INTO
                                                        comments(comment, status, comment_date, item_id, user_id)
                                                        VALUES(:zcomment, 0, NOW(), :itemid, :userid)");
                                $stmt->execute(array(
                                    'zcomment' => $comment,
                                    'userid' => $userid,
                                    'itemid' => $itemid
                                ));
                                if($stmt){
                                    echo '<div class="alert alert-success">Comment Added</div>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php }else{
            echo '<a href="login.php">Login</a> Or <a href="login.php">Registered</a> To Add Comment';
        }?>
        <hr>
        <?php 
        $stmt = $con->prepare("SELECT 
                                comments.*,users.username AS Member
                            FROM 
                                comments
                            INNER JOIN 
                                users
                            ON
                                users.UserID = comments.user_id
                            WHERE
                                item_id = ?
                            AND
                                Status = 1");

        $stmt->execute(array($itemid));

        $comments = $stmt->fetchAll();

        ?>
        
        <?php
            foreach($comments as $comment){
            ?>
            <div class="comment-box">
                <div class="row md-3">
                    <div class="col-sm-2 text-center">
                        <img src="layout/img/avatar.png" alt="..." class="card-img-top img-thumbnail rounded-circle img-fluid">
                        <?php echo $comment['Member'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['comment']?></p>
                    </div>
                </div>
            </div>
            <hr>
            <?php  } ?>
        </div>
    </div>
<?php
    }else{
        echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">There Is No Such ID Or This Item Is Waiting Approval</div>';
            redirectHome($theMsg);
        echo "</div>"; 
    }
?>
<?php
    include("include/templates/footer.php");
?>
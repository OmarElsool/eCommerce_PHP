<?php 
    session_start();
    include("init.php");
?>

<div class="container cat-edit">
    <div class="row md-3">
        <?php
            if(isset($_GET['name'])){
                echo '<h1 class="text-center">'. $_GET['name'].'</h1>';
                $tag = $_GET['name'];
                $tagItems = getAllFrom('*','items',"WHERE tags LIKE '%$tag%'",'AND Approve = 1', 'Item_ID');
                foreach($tagItems as $item){ // loop for items in category with the page id
                    echo 
                    '<div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card item-box">
                        <img src="layout/img/avatar.png" alt="..." class="card-img-top img-thumbnail img-fluid">
                            <div class="caption card-body dis-cat">
                            <h3><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>
                            <p>'.$item['Description'].'</p>
                            <span class="price">$'.$item['Price'].'</span>
                            <div class="date text-muted">'.$item['Add_Date'].'</div>
                            </div>
                        </div>
                    </div>';
                }
            }
        ?>
    </div>
</div>

<?php
    include("include/templates/footer.php");
?>
<?php 
    session_start();
    include("init.php");
?>

<div class="container cat-edit">
    <h1 class="text-center">Show Category</h1>
    <div class="row md-3">
        <?php
            $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
            $allitems = getAllFrom('*','items',"WHERE Cat_ID = {$category}",'AND Approve = 1', 'Item_ID');
            foreach($allitems as $item){ // loop for items in category with the page id
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
        ?>
    </div>
</div>

<?php
    include("include/templates/footer.php");
?>
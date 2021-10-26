<?php 
    session_start();

    $pageTitle = 'New Ad';

    include("init.php");

if(isset($_SESSION['member'])){
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $formError = array();
        
        $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_FLOAT);
        $country = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $cat = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
        $tags = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

        if(empty($name)){
            $formError[] = 'Name Can Not Be Empty';
        }
        if(empty($desc)){
            $formError[] = 'Description Can Not Be Empty';
        }
        if(empty($price)){
            $formError[] = 'Price Can Not Be Empty';
        }
        if(empty($country)){
            $formError[] = 'Country Can Not Be Empty';
        }
        if($status == 0){
            $formError[] = 'You Must Choose Status';
        }
        if($cat == 0){
            $formError[] = 'You Must Choose Category';
        }

        // if there is no error insert the new user in database
        if(empty($formerrors)){

            $stmt = $con->prepare("INSERT INTO
                                items(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags) 
                                VALUES(:name,:desc,:price,:country, :status ,now(),:cat,:member,:tags)");

            $stmt->execute(array(
                "name"      => $name,
                "desc"      => $desc,
                "price"     => $price,
                "country"   => $country,
                "status"    => $status,
                "cat"       => $cat,
                "member"    => $_SESSION['uid'],
                "tags"      => $tags
            ));
            if($stmt){
                echo '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Inserted</div>';
            } 
        }
}

?>
<h1 class="text-center">Creat New Ad</h1>
<div class="creat-ad block">
    <div class="container">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header">
                Creat New Ad
            </div>
            <div class="card-body">
                <div class="row md-3">
                    <div class="col-md-8">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <!-- Name -->
                        <div class="row mb-3 form-pos">
                            <label for="Name" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Name</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="name" class="form-control live-name" id="Name"  placeholder="Name Of The Item">
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="row mb-3 form-pos">
                            <label for="Description" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Description</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="description" class="form-control live-desc" id="Description"  placeholder="Description Of The Item">
                            </div>
                        </div>
                        <!-- Price -->
                        <div class="row mb-3 form-pos">
                            <label for="Price" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Price</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="price" class="form-control live-price" id="Price"  placeholder="Price Of The Item">
                            </div>
                        </div>
                        <!-- Country -->
                        <div class="row mb-3 form-pos">
                            <label for="Country" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Country</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="country" class="form-control" id="Country"  placeholder="Made in Country">
                            </div>
                        </div>
                        <!-- Status -->
                        <div class="row mb-3 select-space form-pos input">
                            <label for="Status" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Status</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <select class="form-select" name="status" id="Status">
                                    <option value="0">...</option>
                                    <option value="1">New</option>
                                    <option value="2">Like New</option>
                                    <option value="3">Used</option>
                                    <option value="4">Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- Categories -->
                        <div class="row mb-3 select-space form-pos">
                            <label for="Category" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Category</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <select class="form-select col-sm-9 col-md-10" name="category" id="Category">
                                    <option value="0">...</option>
                                <?php
                                    $cats = getAllstatic('categories');
                                    foreach($cats as $cat){
                                        echo '<option value="'. $cat['ID'] .'">'. $cat['Name'] .'</option>';
                                    }

                                ?>
                                </select>
                            </div>
                        </div>
                        <!-- Tags -->
                        <div class="row mb-3 select-space form-pos">
                            <label for="Tags" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Tags</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <input type="text" name="tags" class="form-control" id="Tags" placeholder="Separate Tags With Comma (,)" >
                            </div>
                        </div>
                        <!-- submit -->
                        <div class="row mb-3">
                            <div class="text-center col-sm-12">
                                <input type="submit" value="Add Item" class="btn btn-primary ">
                            </div>
                        </div>
                        <!-- error display -->
                        <div>
                            <?php
                                if(!empty($formError)){
                                    foreach($formError as $error){
                                        echo '<div class="alert alert-danger">'. $error .'</div>';
                                    }
                                }
                            ?>
                        </div>
                    </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card item-box text-white bg-dark mb-3 live-prev">
                        <img src="layout/img/avatar.png" alt="..." class="card-img-top img-thumbnail img-fluid">
                            <div class="caption card-body">
                            <h3>Title</h3>
                            <p>Description</p>
                            <span class="price">$0</span>
                            </div>
                        </div>
                    </div>
                </div>
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
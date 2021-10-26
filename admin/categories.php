<?php

    session_start();
    $pageTitle = 'Categories';
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){

        include('init.php');

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage'){

            $sort = 'ASC';

            $sort_array = array('ASC','DESC');

            if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");

            $stmt2->execute();

            $cats = $stmt2->fetchAll();

            ?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container">
                <div class="card border-dark mb-3">
                    <div class="card-header">
                        Manage Categories
                        <div class="ordering float-end">
                            <i class="bi bi-filter-square-fill"></i> Ordering :
                            <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">Asc</a> |
                            <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">Desc</a>
                        </div>
                    </div>
                    <div class="card-body categories">
                        <?php
                            foreach($cats as $cat){
                                echo '<div class="cat">';
                                    echo "<div class='hidden-buttons'>
                                    <a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-sm btn-success'><i class='bi bi-pencil-square'></i> Edit</a>
                                    <a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class='btn btn-sm btn-danger confirm'><i class='bi bi-x-square'></i> Delete </a>
                                    </div>";
                                    echo '<h3>' .$cat['Name'] . '</h3>';
                                    echo '<p>';
                                    if($cat['Description'] == ''){echo "This Category Has No Descripition";}else{echo $cat['Description'];}  
                                    echo '</p>';
                                    echo '<div class="d-flex flex-wrap">';
                                    if($cat['Visibility'] == 1){echo '<span class="hidden"><i class="bi bi-eye-slash"></i> Hidden</span>';}else{echo '<span class="visibility"><i class="bi bi-eye"></i> Visible</span>';}
                                    if($cat['Allow_Comment'] == 1){echo '<span class="com-dis"><i class="bi bi-x-lg"></i> Comment Disabled</span>';}else{echo '<span class="com-allow"><i class="bi bi-check-lg"></i> Comment Allowed</span>';}
                                    if($cat['Allow_Ads'] == 1){echo '<span class="ads-dis"><i class="bi bi-x-lg"></i> Ads Disabled</span>';}else{echo '<span class="ads-allow"><i class="bi bi-check-lg"></i> Ads Allowed</span>';}
                                    echo '</div>';
                                echo '</div>';
                                // get child cat
                                $childCat = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}", "", "ID", "ASC");
                                if(! empty($childCat)){
                                    echo '<h4 class="cats-head">Child Categories</h4>';
                                    echo '<ul class"list-group">';
                                    foreach($childCat as $cat){
                                        echo '<li class="list-group-item d-flex justify-content-between">
                                        <a href="categories.php?do=Edit&catid='. $cat['ID'] .'">' . $cat['Name'] . '</a> 
                                        <a href="categories.php?do=Delete&catid='. $cat['ID'] .'" class="btn btn-sm btn-danger confirm"> Delete </a>
                                        </li>';
                                    }
                                    echo '</ul>';
                                }
                                echo '<hr>';

                            }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class="add-btn btn btn-primary" ><i class="bi bi-plus"></i>Add Category</a>
            </div>
            <?php
        }elseif($do == 'Add'){
        ?>
                <h1 class="text-center">Add New Category</h1>
                <div class="container">
                    <form action="?do=insert" method="POST">
                        <!-- Name -->
                        <div class="row mb-3 form-pos">
                            <label for="Name" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Name</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="name" class="form-control" id="Name" autocomplete="off" required="required" placeholder="Name Of The Categories">
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="row mb-3 form-pos">
                            <label for="Description" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Description</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="description" class="form-control" id="Description" placeholder="Describe The Categories">
                            </div>
                        </div>
                        <!-- Ordering -->
                        <div class="row mb-3 form-pos">
                            <label for="Ordering" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Ordering</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="ordering" class="form-control" id="Ordering" placeholder="Please Type The Order Of Categories">
                            </div>
                        </div>
                        <!-- Start Category Type -->
                        <div class="row mb-3 select-space form-pos">
                            <label for="parent" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Parent?</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <select class="form-select" name="parent" id="parent">
                                    <option value="0">None</option>
                                    <?php 
                                    $allCat = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "DESC");
                                    foreach($allCat as $cate){
                                        echo '<option value="'. $cate['ID'] .'">'. $cate['Name'] .'</option>';
                                    }
                                    ?>  
                                </select>
                            </div>
                        </div>
                        <!-- Visibility -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Visible</label>
                            <div class="col-sm-9 col-md-10">
                                <div>
                                    <input id="Visible" type="radio" name="visible" value="0" checked >
                                    <label for="Visible">Yes</label>
                                </div>
                                <div>
                                    <input id="non-Visible" type="radio" name="visible" value="1" >
                                    <label for="non-Visible">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Commenting -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Allow Commenting</label>
                            <div class="col-sm-9 col-md-10">
                                <div>
                                    <input id="Commenting" type="radio" name="commenting" value="0" checked >
                                    <label for="Commenting">Yes</label>
                                </div>
                                <div>
                                    <input id="non-Commenting" type="radio" name="commenting" value="1" >
                                    <label for="non-Commenting">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Ads -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Allow Ads</label>
                            <div class="col-sm-9 col-md-10">
                                <div>
                                    <input id="Ads" type="radio" name="ads" value="0" checked >
                                    <label for="Ads">Yes</label>
                                </div>
                                <div>
                                    <input id="non-Ads" type="radio" name="ads" value="1" >
                                    <label for="non-Ads">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- submit -->
                        <div class="row mb-3">
                            <div class="text-center col-sm-12">
                                <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>

        <?php
        }elseif($do == 'insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<div class='container'>";
                echo "<h1 class='text-center'>Add New Category</h1>";

                $name = $_POST['name'];
                $desc = $_POST['description'];
                $parent = $_POST['parent'];
                $order = $_POST['ordering'];
                $visible = $_POST['visible'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];

                // check if name is exist
                $check = checkItems("Name","categories",$name);

                if($check == 1 ){

                    $theMsg = '<div class="alert alert-danger">This Category Is Exist</div>';
                    redirectHome($theMsg,'back');

                }else{

                    $stmt = $con->prepare("INSERT INTO
                                        categories(Name,Description,parent,ordering,Visibility,Allow_Comment,Allow_Ads) 
                                        VALUES(:name,:desc,:parent,:order,:visible, :comment ,:ads)"); // :anyName then use it in execute

                    $stmt->execute(array(
                        "name"      => $name,
                        "desc"      => $desc,
                        "parent"      => $parent,
                        "order"     => $order,
                        "visible"  => $visible,
                        "comment"  => $comment,
                        "ads"  => $ads
                    ));

                    $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Inserted</div>';
                    redirectHome($theMsg,"back");
                }


            }else{
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>You Should Come From POST Method</div>";
                redirectHome($theMsg);
                echo "</div>";
            }
            echo "</div>";

        }elseif($do == 'Edit'){

        // check if the req is GET and the catid is number 
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        
        // select all data with this id || we use ? to execute the variable next 
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
        // search on userid in database
        $stmt->execute(array($catid));
        // get the data in array form
        $cat = $stmt->fetch(); 
        // check if the id in database
        $count = $stmt->rowCount();

        if($count > 0){
        ?> 
            <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form action="?do=Update" method="POST">
                        <!-- hidden input to send the value to database -->
                        <input type="hidden" name="catid" value="<?php echo $catid ?>">
                        <!-- Name -->
                        <div class="row mb-3 form-pos">
                            <label for="Name" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Name</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="name" class="form-control" id="Name" required="required" value="<?php echo $cat['Name'] ?>" placeholder="Name Of The Categories">
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="row mb-3 form-pos">
                            <label for="Description" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Description</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="description" class="form-control" id="Description" value="<?php echo $cat['Description'] ?>" placeholder="Describe The Categories">
                            </div>
                        </div>
                        <!-- Ordering -->
                        <div class="row mb-3 form-pos">
                            <label for="Ordering" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Ordering</label>
                            <div class="col-sm-9 col-md-10">
                                <input type="text" name="ordering" class="form-control" id="Ordering" value="<?php echo $cat['Ordering'] ?>" placeholder="Please Type The Order Of Categories">
                            </div>
                        </div>
                        <!-- Start Category Type -->
                        <div class="row mb-3 select-space form-pos">
                            <label for="parent" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Parent?</label>
                            <div class="col-sm-9 col-md-10 align-box">
                                <select class="form-select" name="parent" id="parent">
                                    <option value="0">None</option>
                                    <?php 
                                    $allCat = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "DESC");
                                    foreach($allCat as $cate){
                                        echo '<option value="'. $cate['ID'] .'"';
                                        
                                        if($cat['parent'] == $cate['ID']){echo 'selected';}
                                        
                                        echo '>' . $cate['Name'] . '</option>';
                                    }
                                    ?>  
                                </select>
                            </div>
                        </div>
                        <!-- Visibility -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Visible</label>
                            <div class="col-sm-9 col-md-10">
                                <div>
                                    <input id="Visible" type="radio" name="visible" value="0" <?php if($cat['Visibility'] == '0'){echo 'checked';} ?> >
                                    <label for="Visible">Yes</label>
                                </div>
                                <div>
                                    <input id="non-Visible" type="radio" name="visible" value="1" <?php if($cat['Visibility'] == '1'){echo 'checked';} ?> >
                                    <label for="non-Visible">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Commenting -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Allow Commenting</label>
                            <div class="col-sm-9 col-md-10">
                                <div>
                                    <input id="Commenting" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == '0'){echo 'checked';} ?> >
                                    <label for="Commenting">Yes</label>
                                </div>
                                <div>
                                    <input id="non-Commenting" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == '1'){echo 'checked';} ?> >
                                    <label for="non-Commenting">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Ads -->
                        <div class="row mb-3 form-pos">
                            <label for="FullName" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Allow Ads</label>
                            <div class="col-sm-9 col-md-10">
                                <div>
                                    <input id="Ads" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == '0'){echo 'checked';} ?>>
                                    <label for="Ads">Yes</label>
                                </div>
                                <div>
                                    <input id="non-Ads" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == '1'){echo 'checked';} ?> >
                                    <label for="non-Ads">No</label>
                                </div>
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
                echo "<h1 class='text-center'>Update Category</h1>";

                $id = $_POST['catid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $order = $_POST['ordering'];
                $parent = $_POST['parent'];

                $visible = $_POST['visible'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];
                
                $stmt = $con->prepare("UPDATE 
                                            categories 
                                        SET
                                            Name = ? ,
                                            Description = ? ,
                                            Ordering = ? ,
                                            parent = ? ,
                                            Visibility = ?, 
                                            Allow_Comment = ?, 
                                            Allow_Ads = ? 
                                        WHERE 
                                            ID = ?");

                $stmt->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));

                $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back');

            }else{
                echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-danger">You Should Come From POST Method</div>';
                    redirectHome($theMsg);    
                echo "</div>"; 
            }
            echo "</div>";

        }elseif($do == 'Delete'){

        echo "<div class='container'>";
        echo "<h1 class='text-center'>Delete Category</h1>";

        // check if the req is GET and the userid is number 
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        // select all data with this id || we use ? to execute the variable next 
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
        // search on userid in database
        $stmt->execute(array($catid));
        // check if the id in database
        $count = $stmt->rowCount();

        if($count > 0){
            
            // :then any name to bind it with $userid
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");

            $stmt->bindParam(":zid" , $catid);

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

        }

        include($tpl . 'footer.php');
    }else{
        header('location: index.php');
        exit();
    }
<?php

    session_start();
    $pageTitle = 'Items';
    // if the user already registered go to the location direct
    if(isset($_SESSION['username'])){

        include('init.php');

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
            
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
                                        users.UserID = items.Member_ID");

            $stmt->execute();

            $items = $stmt->fetchAll();
            
            ?>
            
            <h1 class="text-center">Management Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="text-center table table-bordered main-table">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Add_Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>

                        <?php
                            foreach($items as $item){
                                echo "<tr>";
                                    echo "<td>" . $item['Item_ID'] . "</td>";
                                    echo "<td>" . $item['Name'] . "</td>";
                                    echo "<td>" . $item['Description'] . "</td>";
                                    echo "<td>" . $item['Price'] . "</td>";
                                    echo "<td>" . $item['Add_Date'] . "</td>";
                                    echo "<td>" . $item['Cat_Name'] . "</td>";
                                    echo "<td>" . $item['username'] . "</td>";
                                    echo "<td  class='tr-nowrap'>
                                        <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] ."' class='btn btn-success'><i class='bi bi-pencil-square'></i> Edit</a>
                                        <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='bi bi-x-square'></i> Delete </a>";
                                        if($item['Approve'] == 0){
                                            echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info approve'><i class='bi bi-check2-square'></i> Approve </a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                                
                        }
                        ?>
                    </table>
                </div>    
                <a href="items.php?do=Add" class="add-btn btn btn-primary" ><i class="bi bi-plus"></i>Add item</a>
            </div>
            <?php 
    }elseif($do == 'Add'){

        ?>
            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form action="?do=insert" method="POST">
                    <!-- Name -->
                    <div class="row mb-3 form-pos">
                        <label for="Name" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Name</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="name" class="form-control" id="Name" required="required" placeholder="Name Of The Item">
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="row mb-3 form-pos">
                        <label for="Description" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Description</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="description" class="form-control" id="Description" required="required" placeholder="Description Of The Item">
                        </div>
                    </div>
                    <!-- Price -->
                    <div class="row mb-3 form-pos">
                        <label for="Price" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Price</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="price" class="form-control" id="Price" required="required" placeholder="Price Of The Item">
                        </div>
                    </div>
                    <!-- Country -->
                    <div class="row mb-3 form-pos">
                        <label for="Country" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Country</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="country" class="form-control" id="Country" required="required" placeholder="Made in Country">
                        </div>
                    </div>
                    <!-- Status -->
                    <div class="row mb-3 select-space form-pos">
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
                    <!-- Member -->
                    <div class="row mb-3 select-space form-pos">
                        <label for="Member" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Member</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <select class="form-select col-sm-9 col-md-10" name="member" id="Member">
                                <option value="0">...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    echo '<option value="'. $user['UserID'] .'">'. $user['username'] .'</option>';
                                }

                            ?>
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
                                $allcats = getAllFrom('*', 'categories', 'WHERE parent = 0', '', 'ID');
                                foreach($allcats as $cat){
                                    echo '<option value="'. $cat['ID'] .'">'. $cat['Name'] .'</option>';
                                    $childcats = getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}", '', 'ID');
                                    foreach($childcats as $child){
                                        echo '<option value="'. $child['ID'] .'"> --- ' . $child['Name'] .'</option>';
                                    }
                                }

                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- Tags -->
                    <div class="row mb-3 form-pos">
                        <label for="Tags" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Tags</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="tags" class="form-control" id="Tags" placeholder="Separate Tags With Comma (,)" data-role="tagsinput" >
                        </div>
                    </div>
                    <!-- submit -->
                    <div class="row mb-3">
                        <div class="text-center col-sm-12">
                            <input type="submit" value="Add Item" class="btn btn-primary ">
                        </div>
                    </div>
                </form>
            </div>

        <?php

    }elseif($do == 'insert'){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<div class='container'>";
                echo "<h1 class='text-center'>Add New Member</h1>";

                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $member = $_POST['member'];
                $cat = $_POST['category'];
                $tags = $_POST['tags'];
    
                $formerrors = array();
    

                if(strlen($name) < 4){
                    $formErrors[] = 'Username Must Be More Than 4 Characters';
                }
                if(empty($desc)){
                    $formerrors[] = 'Description Can Not Be Empty';
                }
                if(empty($price)){
                    $formerrors[] = 'Price Can Not Be Empty';
                }
                if(empty($country)){
                    $formerrors[] = 'Country Can Not Be Empty';
                }
                if($status == 0){
                    $formerrors[] = 'You Must Choose Status';
                }
                if($member == 0){
                    $formerrors[] = 'You Must Choose Member';
                }
                if($cat == 0){
                    $formerrors[] = 'You Must Choose Category';
                }
    
                foreach($formerrors as $errors){
                    echo '<div class="alert alert-danger">' . $errors .'</div>';
                }
                
                // if there is no error insert the new user in database
                if(empty($formerrors)){

                    $stmt = $con->prepare("INSERT INTO
                                        items(Name,Description,Price,Country_Made,Status,Add_Date,Member_ID,Cat_ID,tags) 
                                        VALUES(:name,:desc,:price,:country, :status ,now(),:member,:cat,:tags)");

                    $stmt->execute(array(
                        "name"      => $name,
                        "desc"      => $desc,
                        "price"     => $price,
                        "country"   => $country,
                        "status"    => $status,
                        "member"    => $member,
                        "cat"       => $cat,
                        "tags"       => $tags
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

        // check if the req is GET and the itemid is number 
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        
        // select all data with this id || we use ? to execute the variable next 
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        // search on itemid in database
        $stmt->execute(array($itemid));
        // get the data in array form
        $item = $stmt->fetch();

        $count = $stmt->rowCount();

        if($count > 0){

            ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                    <!-- Name -->
                    <div class="row mb-3 form-pos">
                        <label for="Name" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Name</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="name" class="form-control" id="Name" required="required" value="<?php echo $item['Name'] ?>" placeholder="Name Of The Item">
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="row mb-3 form-pos">
                        <label for="Description" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Description</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="description" class="form-control" id="Description" required="required" value="<?php echo $item['Description'] ?>" placeholder="Description Of The Item">
                        </div>
                    </div>
                    <!-- Price -->
                    <div class="row mb-3 form-pos">
                        <label for="Price" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Price</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="price" class="form-control" id="Price" required="required" value="<?php echo $item['Price'] ?>" placeholder="Price Of The Item">
                        </div>
                    </div>
                    <!-- Country -->
                    <div class="row mb-3 form-pos">
                        <label for="Country" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Country</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="country" class="form-control" id="Country" required="required" value="<?php echo $item['Country_Made'] ?>" placeholder="Made in Country">
                        </div>
                    </div>
                    <!-- Status -->
                    <div class="row mb-3 select-space form-pos">
                        <label for="Status" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Status</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <select class="form-select" name="status" id="Status">
                                <option value="1" <?php if($item['Status'] == 1){echo 'selected';} ?>>New</option>
                                <option value="2" <?php if($item['Status'] == 2){echo 'selected';} ?>>Like New</option>
                                <option value="3" <?php if($item['Status'] == 3){echo 'selected';} ?>>Used</option>
                                <option value="4" <?php if($item['Status'] == 4){echo 'selected';} ?>>Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- Member -->
                    <div class="row mb-3 select-space form-pos">
                        <label for="Member" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Member</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <select class="form-select col-sm-9 col-md-10" name="member" id="Member">
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    echo '<option value="'. $user['UserID'] .'"';
                                    if($item['Member_ID'] == $user['UserID']){echo 'selected';} 
                                    echo'>'. $user['username'] .'</option>';
                                }

                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- Categories -->
                    <div class="row mb-3 select-space form-pos">
                        <label for="Category" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Category</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <select class="form-select col-sm-9 col-md-10" name="category" id="Category">
                            <?php
                                $stmt2 = $con->prepare("SELECT * FROM categories");
                                $stmt2->execute();
                                $cats = $stmt2->fetchAll();
                                foreach($cats as $cat){
                                    echo '<option value="'. $cat['ID'] .'"';
                                    if($item['Cat_ID'] == $cat['ID']){echo 'selected';} 
                                    echo'>'. $cat['Name'] .'</option>';
                                }

                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- Tags -->
                    <div class="row mb-3 form-pos">
                        <label for="Tags" class="col-sm-3 col-md-2 col-form-label col-form-label-lg">Tags</label>
                        <div class="col-sm-9 col-md-10 align-box">
                            <input type="text" name="tags" class="form-control" id="Tags" placeholder="Separate Tags With Comma (,)" value="<?php echo $item['tags'] ?>" >
                        </div>
                    </div>
                    <!-- submit -->
                    <div class="row mb-3">
                        <div class="text-center col-sm-12">
                            <input type="submit" value="Save Item" class="btn btn-primary ">
                        </div>
                    </div>
                </form>
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
                                    Item_ID = ?");

            $stmt->execute(array($itemid));

            $rows = $stmt->fetchAll();

            if (! empty($rows)){
            
            ?>
            
            <h1 class="text-center">Management [<?php echo $item['Name'] ?>] Comments</h1>

                <div class="table-responsive">
                    <table class="text-center table table-bordered main-table">
                        <tr>
                            <td>Comment</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" . $row['comment'] . "</td>";
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
                <?php } ?>
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
            echo "<h1 class='text-center'>Update Item</h1>";
            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country      = $_POST['country'];
            $status      = $_POST['status'];
            $member      = $_POST['member'];
            $cat      = $_POST['category'];
            $tags      = $_POST['tags'];

            $formerrors = array();

            if(empty($name)){
                $formerrors[] = 'Name Can Not Be Empty';
            }
            if(empty($desc)){
                $formerrors[] = 'Description Can Not Be Empty';
            }
            if(empty($price)){
                $formerrors[] = 'Price Can Not Be Empty';
            }
            if(empty($country)){
                $formerrors[] = 'Country Can Not Be Empty';
            }
            if($status == 0){
                $formerrors[] = 'You Must Choose Status';
            }
            if($member == 0){
                $formerrors[] = 'You Must Choose Member';
            }
            if($cat == 0){
                $formerrors[] = 'You Must Choose Category';
            }

            foreach($formerrors as $errors){
                echo '<div class="alert alert-danger">' . $errors .'</div>';
            }
            
            // if there is no error update the database
            if(empty($formerrors)){

                    $stmt = $con->prepare("UPDATE items SET Name = ? ,Description = ? ,Price = ? ,Country_Made = ?,Status = ?,Cat_ID = ?,Member_ID = ?,tags = ? WHERE Item_ID = ?");
                    $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$tags,$id));

                    $theMsg = '<div class="alert alert-success"> '.$stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back');

                // }
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
        echo "<h1 class='text-center'>Delete Item</h1>";

        // check if the req is GET and the itemid is number 
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $check = checkItems('Item_ID','items',$itemid);

        if($check > 0){
            
            // :then any name to bind it with $itemid
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :itemid");

            $stmt->bindParam(":itemid" , $itemid);

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
        echo "<h1 class='text-center'>Approve Item</h1>";

         // check if the req is GET and the itemid is number 
         $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

         // select all data with this id || we use ? to execute the variable next 
         $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? ");
         // search on itemid in database
         $stmt->execute(array($itemid));
         // check if the id in database
         $count = $stmt->rowCount();
 
         if($count > 0){
             
             // :then any name to bind it with $itemid
             $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
 
             $stmt->execute(array($itemid));
 
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
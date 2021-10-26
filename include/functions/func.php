<?php

    // Get All Items V2.0 
    function getAllFrom($field, $table, $where, $and, $orderfield, $ordering = "DESC"){

        global $con;

        if($where == NULL){
            $is = '';
        }else{
            $is = $where;
        }
        
        if($and == NULL){
            $which = '';
        }else{
            $which = $and;
        }

        $getAll = $con->prepare("SELECT $field FROM $table $is $which ORDER BY $orderfield $ordering");

        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;

    }


// get all items from any table function
function getAllstatic($table,$approve = NULL){

    global $con;

    $getAllstatic = $con->prepare("SELECT * FROM $table $approve");

    $getAllstatic->execute();

    $allstatic = $getAllstatic->fetchAll();

    return $allstatic;

}

// get Category function
function getCat(){

    global $con;

    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

    $getCat->execute();

    $cats = $getCat->fetchAll();

    return $cats;

}
// get items function 
function getItems($where,$value,$approve = NULL){ // where Member_ID or Cat_ID = ?

    global $con;

    if($approve == NULL){
        $sql = 'AND Approve = 1';
    }else{
        $sql = NULL;
    }

    $getItem = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");

    $getItem->execute(array($value));

    $items = $getItem->fetchAll();

    return $items;

}
// function to check the member approved or not
function checkStatus($user){
    global $con;

    $stmts = $con->prepare("SELECT
                                username,RegStatus 
                            FROM 
                                users 
                            WHERE 
                                username = ? 
                            AND 
                                RegStatus = 0");

    $stmts->execute(array($user));

    $status = $stmts->rowCount();

    return $status;
}
// function to check if the item exist in database 
function checkItems($select,$from,$value){
    global $con; 

    $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statment->execute(array($value));
    $count = $statment->rowCount();

    return $count;
}











    // func to get the title for every page that has $pageTitle variable
    function Title(){
        global $pageTitle;

        if(isset($pageTitle)){
            echo $pageTitle;
        }else{
            echo 'default';
        }
    };

    // function to redirect user to any page
    function redirectHome($theMsg,$url = null,$seconds = 3){

        if($url === null){
            $url = 'index.php';
        }else{
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
                $url = $_SERVER['HTTP_REFERER'];
            }else{
                $url = 'index.php';
            }
            
        }
            echo $theMsg;
            echo "<div class='alert alert-info'>You Will Be Redirected After $seconds</div>";
            
            header("refresh:$seconds;url=$url");
            exit();
    }


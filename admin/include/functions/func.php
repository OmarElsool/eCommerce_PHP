<?php

    // Get All Items V2.0 
    function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC"){

        global $con;

        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;

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

    // function to check if the item exist in database 
    function checkItems($select,$from,$value){
        global $con; 

        $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute(array($value));
        $count = $statment->rowCount();

        return $count;
    }

    //function to Display the number of items 

    function numItems($item , $table){
        global $con; 
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

        $stmt2->execute();

        return $stmt2->fetchColumn();
    }

    // function to Get the latest Items From Database

    function latestItems($select,$table,$order,$limit = 5){

        global $con;

        $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

        $stmt->execute();

        $row = $stmt->fetchAll();

        return $row;

    }
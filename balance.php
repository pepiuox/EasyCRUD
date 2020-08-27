<?php
//This is temporal file only for add new row in balance
if (isset($_POST['addrow'])) { 
$user_id = $_POST["user_id"]; 
$currency = $_POST["currency"]; 
$balance = $_POST["balance"]; 
$awaiting_deposit = $_POST["awaiting_deposit"]; 
$reserved_in_orders = $_POST["reserved_in_orders"]; 
$est_USD = $_POST["est_USD"]; 
$deposit = $_POST["deposit"]; 
$withdrawal = $_POST["withdrawal"]; 
$history = $_POST["history"]; 

    if (!empty($user_id) && !empty($currency) && !empty($balance) && !empty($awaiting_deposit) && !empty($reserved_in_orders) && !empty($est_USD) && !empty($deposit) && !empty($withdrawal) && !empty($history)) { 
        $query = "INSERT INTO `$tble`(`user_id` , `currency` , `balance` , `awaiting_deposit` , `reserved_in_orders` , `est_USD` , `deposit` , `withdrawal` , `history`) VALUES ('$user_id' , '$currency' , '$balance' , '$awaiting_deposit' , '$reserved_in_orders' , '$est_USD' , '$deposit' , '$withdrawal' , '$history')";
if ($link->query($query) == TRUE) {
               echo "Record added successfully";                                           
            } else {
               echo "Error added record: " . $link->error;
            }
            unlink("balance.php");
            
    } 
} 
?> 

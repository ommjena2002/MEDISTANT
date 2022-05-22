<?php




session_start();

require_once "assets\db_details\db_details.php";

if(isset($_POST["apnt_id"])){

    $sql = "DELETE FROM `appointments` WHERE `appointments`.`apnt_id` = :id";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute(["id"=>$_POST["apnt_id"]])){
        header("location: approveAppointments.php");
    }else{
        header("location: error.php");
    }
}
header("location: approveAppointments.php");
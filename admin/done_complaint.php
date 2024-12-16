<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query = mysqli_query($con,"UPDATE `complaints` SET `status`='Closed' WHERE `id` = '$id' ");
    
    echo "<script>alert('Complaint Status Closed..!')</script>";
    echo "<script>window.location.href = 'complaints.php';</script>";   
    }
?>

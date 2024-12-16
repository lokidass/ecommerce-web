<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query = mysqli_query($con,"UPDATE `complaints` SET `status`='Accepted' WHERE `id` = '$id' ");
    
    echo "<script>alert('Complaint Accepted..!')</script>";
    echo "<script>window.location.href = 'complaints.php';</script>";   
    }
?>

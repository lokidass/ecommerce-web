<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query = mysqli_query($con,"DELETE FROM `complaints` WHERE `id`='$id'");
    
    echo "<script>alert('Complaint Deleted Successfully..!')</script>";
    echo "<script>window.location.href = 'complaints.php';</script>";   
    }
?>

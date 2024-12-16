<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query = mysqli_query($con,"DELETE FROM `enquiries` WHERE `id`='$id'");
    
    echo "<script>alert('Enquiry Deleted Successfully..!')</script>";
    echo "<script>window.location.href = 'enquiries.php';</script>";   
    }
?>

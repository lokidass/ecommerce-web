<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query = mysqli_query($con,"DELETE FROM `review` WHERE `id`='$id'");
    
    echo "<script>alert('Review Deleted Successfully..!')</script>";
    echo "<script>window.location.href = 'reviews.php';</script>";   
    } 
?>

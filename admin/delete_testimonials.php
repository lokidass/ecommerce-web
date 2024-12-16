<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $query = mysqli_query($con,"DELETE FROM `testimonials` WHERE `id`='$id'");
    
    echo "<script>alert('Testimonials Deleted Successfully..!')</script>";
    echo "<script>window.location.href = 'testimonials.php';</script>";   
    } 
?>

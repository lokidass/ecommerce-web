<?php 

include('../config.php');

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $select = mysqli_query($con, "SELECT * FROM `review` WHERE `id` = '$id' ");
    $data = mysqli_fetch_array($select);
    $name = $data['name'];
    $review = $data['review'];

    $insert = mysqli_query($con, "INSERT INTO `testimonials`(`name`, `review`) VALUES ('$name','$review')");

    if($insert){
        echo "<script>alert('Review Added Successfully..!')</script>";

        $update = mysqli_query($con, "UPDATE `review` SET `status`='check-circle-fill' WHERE `id` = '$id' ");

        echo "<script>window.location.href = 'reviews.php';</script>";  
    }
    
 
    } 
?>

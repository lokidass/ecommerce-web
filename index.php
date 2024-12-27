<style>
    .hero-header{
        margin-top: 60px;
    }

     @media (max-width: 576px) {
        .btn {
            padding: 0.5rem 1rem; /* Reduce padding */
            font-size: 0.875rem; /* Smaller font size */
        }

        .container-xxxl, .hero-header, .mt-5{
            margin-top: -20px;
        }

    }
</style>


<?php include('header.php'); ?>
<?php
// Fetch the header text and heading from the database
$query = "SELECT hheading, htext FROM headertext WHERE id = 1";
$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);

$heading = $data['hheading'];
$text = $data['htext'];
?>

<div class="container-xxxl hero-header " style=" background-color: #f1f1f;">
    <div class="container px-lg-5">
        <div class="row g-5 align-items-end">
        <div class="col-lg-7 text-center text-lg-start">
    <h1 class="text-black mb-4 animated slideInDown">
        <?php echo $heading; ?>
    </h1>
    <p class="text-black pb-3 animated slideInDown">
        <?php echo $text; ?>
    </p>
    <a href="Customer_Support" class="btn text-white py-2 px-4 px-sm-5 rounded-pill me-3 animated slideInLeft">
                    Customer Help
                </a>
                <button class="btn text-black py-2 px-4 px-sm-5 rounded-pill me-3 animated slideInLeft bg-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Write a Review
                </button>
    </div>

            
            <div class="col-lg-5 text-center text-lg-start">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner ">
                        <?php
                        // Fetch images from the headerimage table
                        $result = mysqli_query($con, "SELECT * FROM `headerimage` ORDER BY `id` DESC");
                        $activeClass = 'active'; // Set the first image as active

                        while ($row = mysqli_fetch_array($result)) {
                            echo '<div class="carousel-item ' . $activeClass . '">';
                            echo '<img src="admin/uploads/headerimages/' . $row['img'] . '" class="rounded d-block w-100" alt="slider.image" style="height: 300px;widht: 100px;">';
                            echo '</div>';
                            $activeClass = ''; // Remove active class after the first image
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="name" id="name" placeholder="Customer Name">
                                <label for="name">Your Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" required name="number" id="number" placeholder="Contact Number" maxlength="10" pattern="[0-9]{10}" title="Please enter a 10-digit phone number">
                                <label for="number">Mobile Number</label>
                            </div>
                        </div>
                    

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="review" required placeholder="Type a Review" id="message" style="height: 130px"></textarea>
                                <label for="message">Write a Review</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" name="submit" type="submit">Submit Review</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>   -->
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<div style="margin-top:15%;"></div>


<?php include('product_content.php'); ?>
<div style="margin-top:30px;"></div>
<!-- Service Start -->
<!-- Testimonial End -->

<?php
if (isset($_POST['send'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $number = mysqli_real_escape_string($con, $_POST['number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $product = mysqli_real_escape_string($con, $_POST['product']);

    $insert = mysqli_query($con, "INSERT INTO `enquiries`(`name`, `number`,`email`, `address`, `product`, `time`) VALUES ('$name','$number','$email','$address','$product',current_timestamp() )");


    if ($insert) {
        echo '<script>alert("Enquiry Sent..!")</script>';
        echo '<script>window.location.href = window.location </script>';
    } else {
        echo "<script>alert('Something Went Wrong..!')</script>";
    }
}
?>
<!-- <div style="margin-top:-100px;"></div> -->

<div class="container-xxl bg-gradi newsletter py-4 wow fadeInUp" data-wow-delay="0.1s" style="margin-top: -1%;">
    <div class="container py-5 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <!-- <p class="section-title text-secondary justify-content-center"><span></span>Enquiry Now<span></span></p> -->
                <h1 class="text-center text-black mb-4">Enquiry Now</h1>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-sm">
                                <input type="text" class="form-control" required name="name" id="name" placeholder="Customer Name">
                                <label for="name">Customer Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-sm">
                            <input type="text" class="form-control" required name="number" id="number" placeholder="Contact Number" maxlength="10" pattern="[0-9]{10}" title="Please enter a 10-digit phone number">
                            <label for="number">Mobile Number</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-sm">
                                <input type="email" class="form-control" required name="email" id="email" placeholder="Your Email">
                                <label for="email">Your Email</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-sm">
                                <select name="product" required class="form-select" id="">
                                    <option value="" selected disabled>Select Category</option>
                                    <?php
                                    // Query to retrieve the product titles from the 'product_images' table
                                    $query = "SELECT * FROM categories ";
                                    $result = mysqli_query($con, $query);

                                    // Fetch each row and add it as an option in the select dropdown
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                    }
                                    ?>
                                    <option value="" >Others</option>
                                </select>

                                <label for="product">Select Product</label>
                            </div>
                        </div>
                        

                        <div class="col-12">
                            <div class="form-floating form-floating-sm">
                                <textarea class="form-control" name="address" required placeholder="Customer Address" id="message" style="height: 80px"></textarea>
                                <label for="message">Message</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn text-white w-100 py-3" name="send" type="submit">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php //include('projects_content.php') ?> 
<?php //include('contactform.php') ?>
<!-- Testimonial Start -->
<div class="container-xxl py-5" id="review" >
    <div class="container py-5 px-lg-5">

        <div class="wow fadeInUp" data-wow-delay="0.1s">
        <p class="section-title mb-5 d-flex justify-content-center" style="color: black; font-size: 24px;">
     Customer Feedback 
</p>
            <!-- <h1 class="text-center mb-5">What Say Our Clients!</h1> -->
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
            
        <?php
        $select = mysqli_query($con, "SELECT * FROM `testimonials` ");
        while($run = mysqli_fetch_array($select)){
            ?>
            <div class="testimonial-item rounded p-4">
                <div class="d-flex align-items-start mb-4"> <!-- Aligned to the left -->
                    <!-- <img class="img-fluid bg-white rounded flex-shrink-0 p-1" src="img/testimonial-1.jpg" style="width: 85px; height: 85px;"> -->
                    <div>
                        <h5 class="mb-1"><?= $run['name'] ?></h5> <!-- Name aligned left -->
                        <!-- <p class="mb-1">Profession</p> -->
                        <div>
                            <!-- Client rating -->
                            <small class="fa fa-star text-warning"></small>
                            <small class="fa fa-star text-warning"></small>
                            <small class="fa fa-star text-warning"></small>
                            <small class="fa fa-star text-warning"></small>
                            <small class="fa fa-star text-warning"></small>
                        </div>
                    </div>
                </div>
                <p class="mb-0"><?= $run['review'] ?></p> <!-- Review aligned left -->
            </div>
            <?php
        }
        ?>
        </div>
    </div>
</div>

<?php include('footer.php') ?>
<?php


if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $number = mysqli_real_escape_string($con, $_POST['number']);
    $review = mysqli_real_escape_string($con, $_POST['review']);

    $insert = mysqli_query($con, "INSERT INTO `review`(`name`, `number`, `review`, `time`) VALUES ('$name','$number','$review',current_timestamp() )");

    if ($insert) {
        echo '<script>alert("Review Sent..!")</script>';
        echo '<script>window.location.href = window.location </script>';
    } else {
        echo "<script>alert('Something Went Wrong..!')</script>";
    }
}
?>


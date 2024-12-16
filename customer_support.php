<?php include('header.php') ?>
<div style="margin-top:40px;"></div>

<!-- <div class="container-xxl py-1"> 
    <div class="container my-3 py-3 px-lg-5"> 
        <div class="row g-3"> 
            <div class="col-12 text-center">
                <h1 class="text-dark animated slideInDown">Customer Support</h1>
                <hr class="bg-dark mx-auto mt-0" style="width: 90px;">
                <nav aria-label="breadcrumb"> 
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-dark" href="#">Home</a></li>
                        <li class="breadcrumb-item text-dark active" aria-current="page">Our Products</li>
                    </ol> 
                </nav>
            </div>
        </div>
    </div>
</div> -->

<!-- Navbar & Hero End -->

<style>
    .brand-logo {
        width: 100%;
        height: 50px; /* Adjust the height to ensure uniformity */
        object-fit: contain; /* Ensure the image fits within the given dimensions while maintaining aspect ratio */
    }

    .section-title {
        display: flex;
        justify-content: center;
        text-align: center;
    }
    .section-title {
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.line {
    display: inline-block;
    width: 40px;
    height: 2px;
    background-color: black;
    gap:0px;
    /* margin: 0 10px; */
}


    @media screen and (max-width: 768px) {
        .col-6 {
            max-width: 50%; /* Two items per row on mobile */
        }
    }

</style>

<div style="margin-top:-40px;"></div>
<!-- Contact Start -->
<div class="container-xxl py-2 ">
    <div class="container py-5 px-lg-5">
        <div class="wow fadeInUp" data-wow-delay="0.1s">
        <p class="section-title mb-5" style="color: black; font-size: 24px; font-weight: bold;">
    <!-- <span class="line"></span>  -->
    Customer Support
</p>

            <!-- <h1 class="text-center mb-5">Customer Help Section</h1> -->
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <!-- <p class="text-center mb-4">The contact form is currently inactive. Get a functional and working contact form with Ajax & PHP in a few minutes. Just copy and paste the files, add a little code and you're done. <a href="https://htmlcodex.com/contact-form">Download Now</a>.</p> -->
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" required name="name" id="name" placeholder="Customer Name">
                                    <label for="name">Customer Name</label>
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
                                    <input type="email" class="form-control" required name="email" id="email" placeholder="Your Email">
                                    <label for="email">Email ID</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" required name="address" id="address" placeholder="Address">
                                    <label for="address">Full Address</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="problem" required placeholder="Type Your Problem" id="message" style="height: 130px"></textarea>
                                    <label for="message">Type a Query</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn text-white w-100 py-3" name="submit" type="submit">Submit Query</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->


<?php include('footer.php') ?>

<?php

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $number = mysqli_real_escape_string($con, $_POST['number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $problem = mysqli_real_escape_string($con, $_POST['problem']);

    $insert = mysqli_query($con, "INSERT INTO `complaints`(`name`, `number`,`email`, `address`, `problem`, `time`) VALUES ('$name','$number','$email','$address','$problem',current_timestamp() )");

    if ($insert) {
        echo '<script>alert("Complaint Sent..!")</script>';

        $subject = 'New Complaint';
        $message = "You Have a new Complaint from $name...! ";
        $headers = "From: websitefrom31@gmail.com";
        $emaill = "lokeshweb420@gmail.com";
        if (mail($emaill, $subject, $message, $headers)) {
            echo "<script>alert('Check Email for Status..!')</script>";
        } else{
            echo "<script>alert('Something went wrong..!')</script>";

        }

        echo '<script>window.location.href = window.location </script>';
    } else {
        echo "<script>alert('Something Went Wrong..!')</script>";
    }
}
?>
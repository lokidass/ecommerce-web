<?php include('header.php') ?>
<div style="height: 6px;"></div> <!-- Spacer div, adjust height as needed -->

<div class="container-xxl py-4"> 
    <div class="container my-3 py-3 px-lg-5"> 
        <div class="row g-3"> 
            <div class="col-12 text-center">
                <h1 class="text-dark animated slideInDown">Job Application Form</h1>
                <hr class="bg-dark mx-auto mt-0" style="width: 90px;">
                <!-- <nav aria-label="breadcrumb"> -->
                    <!-- <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a class="text-dark" href="#">Home</a></li>
                        <li class="breadcrumb-item text-dark active" aria-current="page">Our Products</li>
                    </ol> -->
                <!-- </nav> -->
            </div>
        </div>
    </div>
</div>

</div>
<!-- Navbar & Hero End -->


<!-- Job Application Form Start -->
<div class="container-xxl py-5" style="margin-top: -5%;">
    <div class="container py-5 px-lg-5">
        <div class="wow fadeInUp" data-wow-delay="0.1s">
            <p class="section-title text-secondary justify-content-center">Apply for a Job</p>
</br>
</br>
            <!-- <h1 class="text-center mb-5">Apply for a Job</h1> -->
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" required name="name" id="name" placeholder="Your Name">
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

                            <!-- Position Dropdown -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="position" required class="form-select" id="position">
                                        <option value="" selected disabled>Select Position</option>
                                        <option value="Technician">Technician</option>
                                        <option value="Sales Executive">Sales Executive</option>
                                        <option value="Customer Support">Customer Support</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Manager">Other</option>
                                    </select>
                                    <label for="position">Position Applying For</label>
                                </div>
                            </div>

                            <!-- Experience Level Dropdown -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="experience" required class="form-select" id="experience" onchange="showExperienceField()">
                                        <option value="" selected disabled>Are you a Fresher or Experienced?</option>
                                        <option value="fresher">Fresher</option>
                                        <option value="experienced">Experienced</option>
                                    </select>
                                    <label for="experience">Experience Level</label>
                                </div>
                            </div>

                            <!-- Experience Year Text Area (Initially Hidden) -->
                            <div class="col-12" id="experience-years" style="display: none;">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="years_of_experience" id="years_of_experience" placeholder="Years of Experience" maxlength="2" pattern="[0-9]{10}" title="Please enter a 10-digit phone number">
                                    <label for="years_of_experience">Years of Experience</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" name="submit" type="submit">Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Job Application Form End -->

<?php include('footer.php') ?>

<!-- Show/Hide Experience Field Based on Dropdown Selection -->
<script>
    function showExperienceField() {
        var experience = document.getElementById("experience").value;
        var experienceYearsField = document.getElementById("experience-years");
        if (experience === "experienced") {
            experienceYearsField.style.display = "block";
        } else {
            experienceYearsField.style.display = "none";
        }
    }
</script>

<?php

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $number = mysqli_real_escape_string($con, $_POST['number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $position = mysqli_real_escape_string($con, $_POST['position']);
    $experience = mysqli_real_escape_string($con, $_POST['experience']);
    $years_of_experience = isset($_POST['years_of_experience']) ? mysqli_real_escape_string($con, $_POST['years_of_experience']) : null;

    $insert = mysqli_query($con, "INSERT INTO `job_applications`(`name`, `number`,`email`, `address`, `position`, `experience`, `years_of_experience`, `time`) VALUES ('$name','$number','$email','$address','$position','$experience','$years_of_experience',current_timestamp() )");

    if ($insert) {
        echo '<script>alert("Application Submitted Successfully!")</script>';
        echo '<script>window.location.href = window.location </script>';
    } else {
        echo "<script>alert('Something Went Wrong..!')</script>";
    }
}
?>

<?php 
include('config.php');
?>

<?php
// Fetch the header text and heading from the database
$query = "SELECT hdesc, htdesc FROM aboutdescription WHERE id = 1";
$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);

$hdesc = $data['hdesc'];
$htdesc = $data['htdesc'];
?>


<!-- Feature Start -->
<?php
// Fetch services from the serviceblock table
$query = "SELECT * FROM serviceblock";
$result = mysqli_query($con, $query);

if ($result) {
    $services = mysqli_fetch_all($result, MYSQLI_ASSOC); // Fetch all results as an associative array
} else {
    echo "Error fetching data: " . mysqli_error($con);
}
?>


<style>
    .black-text {
        color: black;
    }

    .black-text i {
        color: black;
    }

    .service-image {

        max-width: 108%;
        height: auto;
        margin-bottom: 15px;
        margin-left: -11px;
        margin-top: -10px;
        border-radius: 8px;
    }
    .feature-item {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
    }
</style>
<div style="margin-top:-110px"></div>
<div class="container-fluid p-0 mt-0">
    <div class="container py-0 px-lg-5">
        <div class="row g-4">
        <p class="section-title mb-2" style="color: black; font-size: 24px; font-weight: bold;">
    <!-- <span class="line"></span>--> About Us  
</p>
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
            
            <h1 class="mb-3"><?php echo $hdesc; ?></h1>
            <p class="mb-4">
                <?php echo $htdesc; ?>
            </p>
            <div class="skill mb-4">
                <div class="d-flex justify-content-between">
                    <p class="mb-2">Customer Satisfaction</p>
                    <p class="mb-2">100%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="skill mb-4">
                <div class="d-flex justify-content-between">
                    <p class="mb-2">Product Availability</p>
                    <p class="mb-2">100%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="skill mb-4">
                <div class="d-flex justify-content-between">
                    <p class="mb-2">Work Efficiency</p>
                    <p class="mb-2">100%</p>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        </div>
        <?php
                    // Fetch images from the headerimage table
                    $result = mysqli_query($con, "SELECT * FROM `aboutimage` ORDER BY `id` DESC");
                    $activeClass = 'active'; // Set the first image as active

                    while ($row = mysqli_fetch_array($result)) {
                        echo '<div class="col-lg-6" ' . $activeClass . '">';
                        echo '<img src="admin/uploads/aboutimages/' . $row['img'] . '" class="rounded d-block w-100 " alt="slider.image" style="height: 95%;">';
                        echo '</div>';
                        $activeClass = ''; // Remove active class after the first image
                    }
                    ?>
           
        </div>
    </div>
</div>



<!-- Feature End -->


<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container py-5 px-lg-5">
        <p class="section-title mb-5" style="color: black; font-size: 24px; font-weight: bold;">
            <!-- <span class="line"></span>--> Our Services
        </p>
        <div class="row g-4">
            <?php
            // Initialize a delay for animation
            $delay = 0.1;

            // Loop through each service and create the HTML
            foreach ($services as $service) {
                echo '<div class="col-lg-4 wow fadeInUp" data-wow-delay="' . $delay . 's">';
                echo '<div class="feature-item rounded text-center p-4 black-text">'; // Use a CSS class for black text
                
                // Display the image
                echo '<img src="data:' . htmlspecialchars($service['image_type']) . ';base64,' . base64_encode($service['image']) . '" alt="' . htmlspecialchars($service['title']) . '" class="service-image">';
                
                // Display the title and description
                echo '<h5 class="mb-3">' . htmlspecialchars($service['title']) . '</h5>';
                echo '<p class="m-0">' . htmlspecialchars($service['description']) . '</p>';
                echo '</div>';
                echo '</div>';
                
                // Increment the delay for the next item
                $delay += 0.2; // Adjust as necessary
            }
            ?>
        </div>
    </div>
</div>

<!-- About End -->
<?php include('brand.php') ?>
<div style="margin-top:-100px;"></div>
<!-- Facts Start -->
<?php

// Query to fetch data from the company_facts table
$sql = "SELECT icon_class, fact_value, fact_label FROM company_facts";
$result = $con->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    echo '<div class="container-xxl bg-primary fact py-5 wow fadeInUp" data-wow-delay="0.5s">
            <div class="container py-5 px-lg-5">
                <div class="row g-4">';
    
    // Fetching each row and displaying the facts
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s">
                <i class="fa ' . $row["icon_class"] . ' fa-3x text-secondary mb-3"></i>
                <h1 class="text-white mb-2" data-toggle="counter-up">' . $row["fact_value"] . '</h1>
                <p class="text-white mb-0">' . $row["fact_label"] . '</p>
              </div>';
    }

    echo '    </div>
            </div>
          </div>';
} else {
    echo "No facts found in the database.";
}

// Close the database connection

?>
<div style="margin-top:-140px;"></div>
<?php include('projects_content.php') ?> 
<!-- Facts End -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Ensure this path is correct -->
    <link rel="stylesheet" href="path/to/lightbox.css"> <!-- Add your lightbox CSS path here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <script src="path/to/lightbox-plus-jquery.js"></script> <!-- Add your lightbox JS path here -->
    <style>
        .portfolio-item img {
            height: 200px; /* Set your desired height */
            object-fit: cover; /* Ensures the image covers the area while maintaining aspect ratio */
        }
    </style>
    <title>Completed Projects</title>
</head>
<body>
<br><br>
<!-- Projects Start -->
<div class="container-xxl py-5">
    <div class="container py-5 px-lg-5">
        <div class="wow fadeInUp" data-wow-delay="0.1s">
        <p class="section-title mb-5" style="color: black; font-size: 24px; font-weight: bold;">
        
    <span class="line"></span> Completed Projects <span class="line"></span>
</p>
            <br><br>
        </div>
        <div class="row mt-n2 wow fadeInUp" data-wow-delay="0.3s">
            <div class="col-12 text-center">
                <ul class="list-inline mb-5" id="portfolio-flters">
                    <li class="mx-2 active" data-filter="*">All</li>
                    <li class="mx-2" data-filter=".first">Residential</li>
                    <li class="mx-2" data-filter=".second">Commercial</li>
                </ul>
            </div>
        </div>
        <div class="row g-4 portfolio-container">
            <?php
            // Query to select all projects
            $query = "SELECT * FROM project ORDER BY id DESC";
            $result = mysqli_query($con, $query);

            // Check if the query returned any results
            if (mysqli_num_rows($result) > 0) {
                // Loop through each project and display its details
                while ($project = mysqli_fetch_assoc($result)) {
                    // Determine the category class based on the project type
                    $category_class = strtolower(str_replace(' ', '', $project['project_type']));
            ?>
                    <div class="col-lg-3 col-md-6 portfolio-item <?= $category_class ?> wow fadeInUp" data-wow-delay="0.1s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="admin/uploads/projects/<?= htmlspecialchars($project['img']) ?>" alt="<?= htmlspecialchars($project['location']) ?>">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="admin/uploads/projects/<?= htmlspecialchars($project['img']) ?>" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                </div>
                            </div>
                            <div class="bg-light p-4">
                                <p class="text-primary fw-medium mb-2"><?= htmlspecialchars($project['project_type']) ?></p>
                                <p class="description">
                                    Location: <?= htmlspecialchars($project['location']) ?><br>
                                    Brand: <?= htmlspecialchars($project['brand']) ?><br>
                                    Number of Devices: <?= htmlspecialchars($project['number_of_device']) ?><br>
                                    Device Type: <?= htmlspecialchars($project['Device type']) ?><br>
                                </p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No projects found.</p>";
            }
            // Close the database connection
            mysqli_close($con);
            ?>
        </div>

        <!-- Enquiry Button Section -->
        <div class="row mt-5">
            <div class="col text-center">
                <button class="btn btn-primary py-2 px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal">Enquiry Now</button>
            </div>
        </div>
    </div>
</div>



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
                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Initialize Isotope
        var $portfolioContainer = $('.portfolio-container').isotope({
            itemSelector: '.portfolio-item',
            layoutMode: 'fitRows'
        });

        // Filter items on click
        $('#portfolio-filters li').on('click', function() {
            $('#portfolio-filters li').removeClass('active');
            $(this).addClass('active');
            var filterValue = $(this).attr('data-filter');
            $portfolioContainer.isotope({ filter: filterValue });
        });
    });
</script>
</body>
</html>

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
    <title>Recent Updates</title>
</head>
<body>
<br><br>
<!-- Projects Start -->
<div class="container-xxl py-5">
    <div class="container py-5 px-lg-5">
        <div class="wow fadeInUp" data-wow-delay="0.1s">
            <p class="section-title mb-5" style="color: black; font-size: 24px; font-weight: bold;">
            Recent Updates
            </p>
            <br><br>
        </div>
        <div class="row g-4 portfolio-container ">
            <?php
            // Query to select all projects
            $query = "SELECT * FROM project ORDER BY id DESC";
            $result = mysqli_query($con, $query);

            // Check if the query returned any results
            if (mysqli_num_rows($result) > 0) {
                // Loop through each project and display its details
                while ($project = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="col-lg-3 col-md-6 portfolio-item wow fadeInUp" data-wow-delay="0.1s">
                        <div class="rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="admin/uploads/projects/<?= htmlspecialchars($project['image']) ?>" alt="Project Image">
                                <div class="portfolio-overlay">
                                    <a class="btn btn-square btn-outline-light mx-1" href="admin/uploads/projects/<?= htmlspecialchars($project['image']) ?>" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                                </div>
                            </div>
                            <div class="bg-white border p-2 ">
                                <p class="description bg-white" style="text-align:justify;">
                                    <?= htmlspecialchars($project['description']) ?>
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

<!-- Feature Start -->
<div class="container-xxl py-3">
    <div class="container py-4 px-lg-5">
        <div class="row justify-content-center text-center g-4">
            <!-- <p class="section-title text-secondary mb-5"><span></span>Our Brands<span></span></p> -->
            <p class="section-title mb-5" style="color: black; font-size: 24px; font-weight: bold;">
     Our Brands 
</p>


            <!-- <h1 class="text-center mb-5">What Brands We Provide</h1> -->

            <?php
            // Fetching brand images from the brandimage table
            $brandImages = mysqli_query($con, "SELECT * FROM brandimage ORDER BY id ASC");
            $delay = 0.1; // Initial delay for animation

            while ($brand = mysqli_fetch_array($brandImages)) {
                ?>
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 " data-wow-delay="<?= $delay ?>s">
                    <div class="feature-item rounded text-center p-4">
                        <img src="admin/uploads/brandimages/<?= $brand['img'] ?>" class="img-fluid brand-logo" alt="Brand Image">
                    </div>
                </div>
                <?php
                $delay += 0.1; // Increment delay for each image for a staggered animation effect
            }
            ?>
        </div>
    </div>
</div>
<!-- Feature End -->

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

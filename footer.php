    <style>
    /* Quick Contact Container */
    .quickcon {
        position: fixed;
        right: 3.3%;
        bottom: 100px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
        transition: transform 0.3s ease-in-out;
    }

    /* Plus Button */
    .plus-btn {
        width: 50px;
        height: 50px;
        background-color: #26d367;
        border-radius: 50%;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-size: 30px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    /* Hover effect for plus button */
    .plus-btn:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, .5);
    }

    /* Icons Container - Hidden by default */
    .quickicons {
        display: none;
        position: absolute;
        bottom: 50px;
        left: 0;
        z-index: 999;
        flex-direction: column;
        align-items: flex-start;
        transition: all 1.5s ease;
    }

    /* Show icons when the 'show' class is removed */
    .quickcon.show .quickicons {
        display: flex;
    }

    /* Icons styling - Increased size */
    .whatsapp, .gogl, .fb, .inst, .call {
        width: 50px;   /* Increased size */
        height: 50px;  /* Increased size */
        font-size: 30px;  /* Increased font size of icons */
        border-radius: 50%;
        color: black;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
        transition: transform 1s ease;
    }

    /* Hover effect for larger icons */
    .whatsapp:hover, .gogl:hover, .fb:hover, .inst:hover, .call:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, .5);
    }

    .whatsapp, .gogl, .fb, .inst, .call {
        background-color:rgb(187, 219, 245);
    }


    /* Link Styling */
    .whatsapp a, .gogl a, .fb a, .inst a, .call a {
        color: black;
        text-decoration: none;
        /* margin-top: 8px; */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        /* Adjust position of Quick Contact */
        .section-title {
            gap: 100px;
        }
        .quickcon {
            position: fixed;
        right: 10%;
        /* bottom: 125px; */
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
        transition: transform 0.3s ease-in-out;
        }

    }

    /* For very small screens like phones (max-width: 480px) */
    @media (max-width: 480px) {
        /* Adjust position for small screens */
        .section-title {
            gap: 10px;
        }
        .quickcon {
            position: fixed;
        right: 8%;
        /* bottom: 125px; */
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
        transition: transform 0.3s ease-in-out;
        }
        .back-to-top{
                    right: 8%;
                    
                }

    }

    /* Extra small screens (e.g., very small devices) */
    @media (max-width: 320px) {
        /* More compact layout */
        .section-title {
            gap: 10px;
        }
        .quickcon {
            right: 8%;
            bottom: 120px;
        }
        .back-to-top{
                    right: 8%;
                    
                }

    }
    </style>
    <script>
    function toggleIcons() {
        var quickcon = document.querySelector('.quickcon');
        var plusBtn = document.querySelector('.plus-btn i');
        
        quickcon.classList.toggle('show');  // Toggle the 'show' class to display or hide icons

        // Toggle the icon between plus and minus
        if (quickcon.classList.contains('show')) {
            plusBtn.classList.remove('bx-plus');
            plusBtn.classList.add('bx-minus');
        } else {
            plusBtn.classList.remove('bx-minus');
            plusBtn.classList.add('bx-plus');
        }
    }
        </script>

    <!-- Footer Start -->
    <div class="container-fluid footer wow fadeIn" data-wow-delay="0.1s" style="background-color:#FAF0E6;  padding-bottom: 0;">
        <div class="container py-1 px-lg-1" style="padding-bottom: 2rem;">
            <div class="row g-3">
            
                <?php
                // Include the connection file
                include('config.php');

                // Query to fetch contact details
                $sql = "SELECT address, email, phone, secondary_phone, instagram,facebook,google_maps_link,youtube FROM contact_info WHERE id = 1";
                $result = mysqli_query($con, $sql);

                // Initialize variables
                $address = $email = $phone = $google_maps_link = $instagram = $facebook= $youtube="";

                if (mysqli_num_rows($result) > 0) {
                    // Fetch the row
                    $row = mysqli_fetch_assoc($result);
                    $address = $row['address'];
                    $email = $row['email'];
                    $phone = $row['phone'];
                    $secondary_phone = $row['secondary_phone'];
                    $google_maps_link = $row['google_maps_link'];
                    $instagram = $row['instagram'];
                    $facebook = $row['facebook'];
                    $youtube = $row['youtube'];
                } else {
                    echo "No contact details found";
                }
                ?>
                <div class="col-md-6 col-lg-5">
                    <p class="section-title text-black h5 mb-4" style=" justify-content:left;">Contact us<span></span></p>
                    <p><i class="fa fa-map-marker-alt me-2"></i><?php echo nl2br($address); ?></p>
                    <p><i class="fa fa-phone-alt me-2"></i><t href="tel:+91<?php echo $phone; ?>" class="text-black "><?php echo $phone; ?></t></p>
                    <!-- <p><i class="bi bi-person-video2"></i><t href="tel:+91<?php echo $phone; ?>" class="text-black"><?php echo $phone; ?></t></p> -->
                    <p style="margin-top:-10px;"><i class="fa fa-phone-alt me-2"></i><t href="tel:+91<?php echo $secondary_phone; ?>" class="text-black"><?php echo $secondary_phone; ?></t></p>
                    <p class="d-flex align-items-center">
                        <i class="fa fa-envelope me-2"></i>
                        <!-- <a href="mailto:<?php //echo $email; ?>" class="text-black"><?php //echo $email; ?></a> -->
                        <t class="text-black"><?php echo $email; ?></t>
                    </p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-black btn-social" href="<?php echo $google_maps_link; ?>" target="_blank">
                            <i class="fab fa-google"></i>
                        </a>
                        <a class="btn btn-outline-black btn-social" href="<?php echo $facebook; ?>" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="btn btn-outline-black btn-social" href="<?php echo $instagram; ?>" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="btn btn-outline-black btn-social" href="<?php echo $youtube; ?>" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
<!-- <div style="margin-top: 2px;"></div> -->
                <div class="col-md-6 col-lg-3 mt-3">
                    <p class="section-title text-black h5 mb-4" style=" justify-content:left;">Quick Link<span></span></p>
                    <a class="btn btn-link" href="About">About Us</a>
                    <a class="btn btn-link" href="Contact">Contact Us</a>
                    <a class="btn btn-link" href="privacy.php">Privacy Policy</a>
                    <a class="btn btn-link" href="terms.php">Terms & Condition</a>
                    <a class="btn btn-link" href="career.php">Career</a>
                </div>

                <div class="col-md-6 col-lg-4 mw-100 mb-1 ">
        <p class="section-title text-black h5 mb-4" style="justify-content:left;">Map Location<span></span></p>
        
        <!-- Responsive Google Map iframe -->

    <div class="ratio ratio-16x9" style="max-width: 100%; height: 220px; margin: 0 auto;">
        <iframe class="mb-2 mb-lg-0" 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3726.706730933781!2d77.7542074!3d20.924115399999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd6a4b0299dafcd%3A0x928d3920f5018ab4!2sVimal%20Marketing%20%26%20Services!5e0!3m2!1sen!2sin!4v1734170922603!5m2!1sen!2sin" 
            frameborder="0" 
            allowfullscreen>
        </iframe>
    </div>



    </div>
        </div>
            </div>
                </br>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="text-center mb-3 mb-md-0">
                            <t>Vimal Marketing And Services &copy; 2024</t></br>
                            Powered By<div style="height: 3px;"></div>
                            <a href="http://bizonance.in"><img src="img/biz_logo.png" width="60px" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer End -->

    <?php
    // Include the connection file
    include('config.php');

    // Query to fetch contact details
    $sql = "SELECT address, email, phone, instagram FROM contact_info WHERE id = 1";
    $result = mysqli_query($con, $sql);

    // Initialize variables
    $address = $email = $phone = $instagram_link = "";

    if (mysqli_num_rows($result) > 0) {
        // Fetch the row
        $row = mysqli_fetch_assoc($result);
        $address = $row['address'];
        $email = $row['email'];
        $phone = $row['phone'];
        $instagram_link = $row['instagram'];
    } else {
        echo "No contact details found";
    }
    ?>

    <!-- Quick Contact Icons Section -->
    <div class="quickcon">
        <!-- Plus Button to Show/Hide Icons -->
        <div class="plus-btn" onclick="toggleIcons()">
            <i class="bx bx-plus"></i>
        </div>


        <!-- Icons will be shown in a quarter-circle layout -->
        <div class="quickicons">
            <!-- WhatsApp Icon -->
            <div class="whatsapp">
                <a href="https://wa.me/+91<?php echo $phone; ?>?text=Enquiry%20From%20Website%20:%20I%20want%20to%20know%20more%20about%20Vimal%20Marketing%20and%20Services%20and%20its%20Products%20and%20services.">
                    <i class="bx bxl-whatsapp"></i>
                </a>
            </div>

            <!-- Google Maps Icon -->
            <div class="whatsapp gogl">
                <a href="<?php echo $google_maps_link; ?>" target="_blank">
                    <i class="bx bxl-google"></i>
                </a>
            </div>

            <!-- Facebook Icon -->
            <div class="whatsapp fb">
                <a href="<?php echo $facebook; ?>" target="_blank">
                    <i class="bx bxl-facebook" ></i>
                </a>
            </div>

            <!-- Instagram Icon -->
            <div class="whatsapp inst">
                <a href="<?php echo $instagram_link; ?>" target="_blank">
                    <i class="bx bxl-instagram"></i>
                </a>
            </div>

            <!-- Phone Call Icon -->
            <div class="whatsapp call">
                <a href="tel:+91<?php echo $phone; ?>">
                    <i class="bx bx-phone"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Back to Top and Telephone Buttons -->
    <div class="quickcon">
        <a href="#" class="btn btn-lg text-white btn-lg-square back-to-top" >
            <i class="bi bi-arrow-up"></i>
        </a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>
    </html>

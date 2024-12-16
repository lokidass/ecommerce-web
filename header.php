<?php include('config.php') ?>
<?php
// Database connection

// Retrieve the latest logo from the `logo` table
$query = "SELECT img, logo_text FROM `logo` ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $query);

// Fetch the logo image and text
if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $logo = $data['img'];
    $logo_text = $data['logo_text'];
} else {
    // Default values if no record exists in the database
    $logo = 'defaultlogo.jpg'; // Replace with the path to your default logo image
    $logo_text = 'Your Brand';  // Default text if no logo or text is available
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Vimal Marketing And Services</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="Water purification and solar Services in Amravati">
    <meta content="Vimal Marketing And Services - We are one of the leading service providers in the fields of water purification and solar technology. We offer a wide range of high-quality products at affordable prices to ensure you have access to clean water and sustainable energy solutions." name="description">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
    <link href="img/Vimal Marketing Logo.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Sofia:wght@500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.minn.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    .navbar {
        background-color: #F8F8F8 !important; 
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3); /* Darker shadow effect */
    }

    .navbar .nav-link {
        color: #000 !important; /* Force black color for nav links */
    }

    .navbar .nav-link:hover {
        color: #007bff !important; /* Optional: Hover effect */
    }

    .navbar .nav-link.active {
        color: #000 !important; /* Active nav link color */
    }

    .navbar-toggler {
        border-color: #000; /* Black border for the toggle button */
    }

    .navbar-toggler-icon {
        background-image: none; /* Remove default background image */
        color: #000; /* Black color for the toggle icon */
    }

    .offcanvas {
        background-color: #f8f8f8;
    }

    .offcanvas .nav-link {
        color: #000;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .offcanvas .nav-link:hover {
        color: #007bff;
    }
</style>

</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
    <a href="Home" class="navbar-brand p-0">
        <h1 class="m-0">
            <?php if (!empty($logo)) { ?>
                <img src="admin/uploads/logos/<?php echo $logo; ?>" style="width: 100%; height: 150%;" alt="Logo">
            <?php } elseif (!empty($logo_text)) { ?>
                <span style="font-size: 2rem; font-weight: bold; color:white"><?php echo $logo_text; ?></span>
            <?php } else { ?>
                <span style="font-size: 2rem; font-weight: bold; color:white">Your Brand</span>
            <?php } ?>
        </h1>
    </a>

    <!-- Navbar toggler button for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav">
        <span class="fa fa-bars" style="color:white;"></span>
    </button>

    <!-- Desktop Navigation -->
    <div class="collapse navbar-collapse" id="desktopNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a href="Home" class="nav-link text-black home-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="About" class="nav-link home-link">About</a>
            </li>
            <li class="nav-item">
                <!-- <a href="Products" class="nav-link home-link">Our Products</a> -->
            </li>
            <li class="nav-item">
                <a href="Customer_Support" class="nav-link home-link">Customer Support</a>
            </li>
            <li class="nav-item">
                <a href="Contact" class="nav-link home-link">Contact</a>
            </li>
        </ul>
    </div>

    <!-- Offcanvas Menu for Mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileNavLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="Home" class="nav-link home-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="About" class="nav-link home-link">About</a>
                </li>
                <li class="nav-item">
                    <!-- <a href="Products" class="nav-link home-link">Our Products</a> -->
                </li>
                <li class="nav-item">
                    <a href="Customer_Support" class="nav-link home-link">Customer Support</a>
                </li>
                <li class="nav-item">
                    <a href="Contact" class="nav-link home-link">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

        <!-- Navbar End -->

</body>
</html>

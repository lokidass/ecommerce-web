<?php include('../config.php'); 
session_start();
if(!isset($_SESSION['id'])){
    ?>
    <script>
      alert("You Are Logged Out");
      window.location.href = 'login.php';
    </script>
  <?php
}
?>

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
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin - Vimal Marketing And Services</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../img/Vimal Marketing Logo.jpg" rel="icon">
  <link href="../img/Vimal Marketing Logo.jpg" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
      .navbar-custom {
          background-color: #F8F8F8; /* Navbar background color */
      }
      .navbar-custom .nav-link,
      .navbar-custom .dropdown-item {
          color:black; /* Text color for links */
      }
      .navbar-custom .nav-link:hover,
      .navbar-custom .dropdown-item:hover {
          color: #ccc; /* Text color on hover */
      }
      .navbar-custom .dropdown-header {
          color: white; /* Color for dropdown header */
      }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>

<body>
<script>
    jQuery(function($) {
      var path = window.location.href;
      $('ul a').each(function() {
        if (this.href === path) {
          $(this).addClass('collapsed');
        }
      });
    });
</script>
  <!-- ======= Header ======= -->
  <header id="header" class="header navbar-custom fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
      <h1 class="m-0">
        <?php if (!empty($logo)) { ?>
            <img src="uploads/logos/<?php echo $logo; ?>" style="width: 100%; height: 100%;" alt="Logo">
        <?php } elseif (!empty($logo_text)) { ?>
            <!-- Display text as logo if no image is available -->
            <span style="font-size: 2rem; font-weight: bold; color:white"><?php echo $logo_text; ?></span>
        <?php } else { ?>
            <!-- Fallback text in case both logo image and text are missing -->
            <span style="font-size: 2rem; font-weight: bold; color:white">Your Brand</span>
        <?php } ?>
    </h1>
      </a>
      <i class="bi bi-list toggle-sidebar-btn" style="color:black;"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="complaints.php">
            <i class="bi bi-bell"></i>       
            <span class="badge bg-danger badge-number">   
              <?php
              include('config.php');
              $queryy = "SELECT `id` FROM `complaints` ORDER BY id";
              $runn = mysqli_query($con, $queryy);
              $count = mysqli_num_rows($runn);
              echo $count;
              ?>
            </span>
          </a><!-- End Notification Icon -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../img/Vimal Marketing Logo.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Vimal Marketing And Services</span>
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Vimal Marketing And Services</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
</body>
</html>

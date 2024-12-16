<?php

session_start();

include('../config.php');
if (isset($_POST['login'])) {
  $email = mysqli_real_escape_string($con, $_POST['username']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

  $email_search = "SELECT * FROM admin WHERE email = '$email' ";
  $query = mysqli_query($con, $email_search);

  $emailcount = mysqli_num_rows($query);
  
  if (isset($_POST['remember'])) {

    setcookie('emailcookie', $email, time() + 86400);
    setcookie('passcookie', $password, time() + 86400);
  }

  if ($emailcount) {
    $email_pass = mysqli_fetch_assoc($query);

    // $dbpass = $email_pass['password'];

    $_SESSION['id'] = $email_pass['id'];
    // $_SESSION['number'] = $email_pass['number'];
    // $_SESSION['id'] = $email_pass['id'];

    // $pass_decode = $email_pass['password'];
    // $pass_decode = password_verify($password, $dbpass);
    if($password == $email_pass['password']){
      ?>
      <script>
        alert("Login Successfull");
        window.location.href = 'index.php';
      </script>
    <?php
    }else{
      ?>
      <script>
        alert("Wrong Password");
     
      </script>
    <?php
    } 


  }else{
    ?>
    <script>
      alert("Invalid Email");

    </script>
  <?php
  }
}

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

  <title>Admin Login Vimal Marketing And Services</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/Vimal Marketing Logo.jpg" rel="icon">
  <link href="assets/img/Vimal Marketing Logo.jpg" rel="apple-touch-icon">

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

</head>

<body>
<style>
  .logo-img {
    width: 200%; /* Adjust this value as needed */
    height: auto; /* Maintain aspect ratio */
}

  </style>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-2">
              <a href="Home" class="navbar-brand p-0">
    <h1 class="d-flex justify-content-center  m-0">
        <?php if (!empty($logo)) { ?>
            <img src="uploads/logos/<?php echo $logo; ?>" style="width: 80%; height: 50%;" alt="Logo">
        <?php } elseif (!empty($logo_text)) { ?>
            <!-- Display text as logo if no image is available -->
            <span style="font-size: 2rem; font-weight: bold; color:white"><?php echo $logo_text; ?></span>
        <?php } else { ?>
            <!-- Fallback text in case both logo image and text are missing -->
            <span style="font-size: 2rem; font-weight: bold; color:white">Your Brand</span>
        <?php } ?>
    </h1>
</a>
</div><!-- End Logo -->


              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3 needs-validation" method="POST" action="" novalidate>

<div class="col-12">
  <label for="yourUsername" class="form-label">Username</label>
  <div class="input-group has-validation">
    <span class="input-group-text" id="inputGroupPrepend"><i style="font-size: 18px; font-weight: 700;" class="bi bi-person" id="toggePassword"></i></span>
    <input type="email" name="username" class="form-control" value="<?php if (isset($_COOKIE['emailcookie'])) {
                                                                      echo $_COOKIE['emailcookie'];
                                                                    } ?>" id="yourUsername" required>
    <div class="invalid-feedback">Please enter your username.</div>
  </div>
</div>


<div class="col-12">
  <label for="yourUsername" class="form-label">Password</label>
  <div class="input-group has-validation">
    <span class="input-group-text" id="inputGroupPrepend"><i style="font-size: 18px; font-weight: 700;" class="bi bi-lock"></i></span>
    <input type="password" name="password" class="form-control" value="<?php if (isset($_COOKIE['passcookie'])) {
                                                                          echo $_COOKIE['passcookie'];
                                                                        } ?>" id="yourPassword" required>
    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-eye-slash" id="togglePassword"></i></span>

    <div class="invalid-feedback">Please enter your username.</div>
  </div>
</div>

<div class="col-12">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
    <label class="form-check-label" for="rememberMe">Remember me</label>
  </div>
</div>
<div class="col-12">
  <button class="btn btn-primary w-100" name="login" type="submit">Login</button>
</div>

</form>


                </div>
              </div>

              <div class="credits text-center">
                Designed & Managed by <br> <a href="http://bizonance.in"><img src="../img/biz_logo.png" width="60px" alt=""></a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#yourPassword');


    togglePassword.addEventListener('click', function(e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
    });
  </script>
</body>

</html>
<style>
/*--------------------------------------------------------------
# Contact
--------------------------------------------------------------*/
input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }


.contact .info-box {
  color: #444444;
  text-align: center;
  box-shadow: 0 0 30px rgba(214, 215, 216, 0.6);
  padding: 20px 0 30px 0;
  background: #fff;
}

.contact .info-box i {
  font-size: 32px;
  color: #5846f9;
  border-radius: 50%;
  padding: 8px;
}

.contact .info-box h3 {
  font-size: 20px;
  color: #2c4964;
  font-weight: 700;
  margin: 10px 0;
}

.contact .info-box p {
  padding: 0;
  line-height: 24px;
  font-size: 14px;
  margin-bottom: 0;
}

.contact .php-email-form {
  box-shadow: 0 0 30px rgba(214, 215, 216, 0.9);
  padding: 30px;
  background: #fff;
}

.contact .php-email-form .error-message {
  display: none;
  color: #fff;
  background: #ed3c0d;
  text-align: left;
  padding: 15px;
  font-weight: 600;
}

.contact .php-email-form .error-message br + br {
  margin-top: 25px;
}

.contact .php-email-form .sent-message {
  display: none;
  color: #fff;
  background: #18d26e;
  text-align: center;
  padding: 15px;
  font-weight: 600;
}

.contact .php-email-form .loading {
  display: none;
  background: #fff;
  text-align: center;
  padding: 15px;
}

.contact .php-email-form .loading:before {
  content: "";
  display: inline-block;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  margin: 0 10px -6px 0;
  border: 3px solid #18d26e;
  border-top-color: #eee;
  -webkit-animation: animate-loading 1s linear infinite;
  animation: animate-loading 1s linear infinite;
}

.contact .php-email-form input, .contact .php-email-form textarea {
  border-radius: 5px;
  box-shadow: none;
  font-size: 14px;
}

.contact .php-email-form input:focus, .contact .php-email-form textarea:focus {
  border-color: #5846f9;
}

.contact .php-email-form input {
  padding: 10px 15px;
}

.contact .php-email-form textarea {
  padding: 12px 15px;
}



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

.line {
  display: inline-block;
  width: 40px;
  height: 2px;
  background-color: black;
  gap: 0px;
}

@media screen and (max-width: 768px) {
  .col-6 {
    max-width: 50%; /* Two items per row on mobile */
  }
  .contact .row {
    flex-direction: column; /* Stack the columns on small screens */
  }
}

/* Aligning the contact sections */
.contact .row .info-box {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.contact .row .info-box .bx {
  font-size: 40px; /* Increase icon size */
  margin-top: 10px;
}

.contact .row {
  justify-content: center;
}

.contact .info-box p {
  font-size: 16px;
  color: #333;
}

/* New Styles for the form */
.contact .col-lg-6 {
  padding: 20px;
}

.contact-form {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.contact-form input,
.contact-form textarea {
  margin-bottom: 15px;
  padding: 12px;
  border-radius: 5px;
  border: 1px solid #ddd;
}


</style>

<!-- ======= Contact Section ======= -->

  <div class="container" data-aos="fade-up">
    <div class="wow fadeInUp" data-wow-delay="0.1s">
      <p class="section-title mb-5" style="color: black; font-size: 24px; font-weight: bold;">
         Contact Us 
      </p>
    </div>

    <?php
      // Include the connection file
      include('config.php');

      // Query to fetch contact details
      $sql = "SELECT address, email, phone, secondary_phone, time FROM contact_info WHERE id = 1";
      $result = mysqli_query($con, $sql);

      // Initialize variables
      $address = $email = $primary_phone = $secondary_phone = "";

      if (mysqli_num_rows($result) > 0) {
          // Fetch the row
          $row = mysqli_fetch_assoc($result);
          $address = $row['address'];
          $email = $row['email'];
          $primary_phone = $row['phone'];
          $secondary_phone = $row['secondary_phone']; 
          $time = $row['time'];
      } else {
          echo "No contact details found";
      }
    ?>

    <div class="row">
      <!-- Contact Info -->
      <div class="col-lg-6">
        <div class="info-box mb-4">
          <h5><i class="fa fa-map-marker-alt me-2"></i> Our Address</h5>
          <p><?php echo nl2br($address); ?></p>


          <h5><i class="fa fa-envelope me-2"></i> Email Us</h5>
          <p><?php echo $email; ?></p>


          <h5><i class="fa fa-phone-alt me-2"></i></i> Call Us</h5>
          <p><?php echo $primary_phone; ?></p>
          <div style="margin-top:-17px;"></div>
          <p><?php echo $secondary_phone; ?></p>


          <h5><i class="bx bx-time "></i> Our Timing</h4>
          <p><?php echo $time; ?></p>
        </div>
      </div>

      <!-- Contact Form -->
      <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $number = mysqli_real_escape_string($con, $_POST['number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // SQL query to insert the data into the contact_form table
    $query = "INSERT INTO contact_form (name, number, email, message) 
              VALUES ('$name', '$number', '$email', '$message')";

    // Execute the query
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Message sent successfully!');</script>";
        echo '<script>window.location.href = window.location </script>';
        // You can also redirect to another page after submission
        // header("Location: thank_you.php");  // Uncomment to redirect
    } else {
        echo "<script>alert('Error: Could not send your message. Please try again later.');</script>";
    }
}
?>

<!-- HTML Form -->
<div class="col-lg-6">
    <form action="contact.php" method="POST" class="php-email-form contact-form" id="contactForm">
        <div class="form-group">
            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required minlength="3" maxlength="50">
        </div>
        <div class="form-group">
            <input type="number" name="number" class="form-control" id="number" placeholder="Mobile Number" required pattern="^\d{10}$" title="Please enter a valid 10-digit mobile number">
        </div>
        <div class="form-group">
            <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
        </div>
        <div class="form-group">
            <textarea name="message" class="form-control" id="message" rows="5" placeholder="Your Message" required minlength="10" maxlength="500"></textarea>
        </div>
        <div class="text-center">
            <button class="btn text-white w-100 py-3" type="submit">Send Message</button>
        </div>
        <br>
    </form>
</div>

    </div>
  </div>



<!-- Optional: JavaScript for additional validation -->
<script>
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        let form = event.target;
        let valid = true;

        // Validate Name (min length of 3 characters)
        const name = form.name.value;
        if (name.length < 3) {
            alert('Name must be at least 3 characters long');
            valid = false;
        }

        // Validate Mobile Number (must be 10 digits)
        const number = form.number.value;
        if (!/^\d{10}$/.test(number)) {
            alert('Please enter a valid 10-digit mobile number');
            valid = false;
        }

        // Validate Email (standard email validation is handled by HTML5)
        const email = form.email.value;
        if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
            alert('Please enter a valid email address');
            valid = false;
        }

        // Validate Message (min length of 10 characters)
        const message = form.message.value;
        if (message.length < 10) {
            alert('Message must be at least 10 characters long');
            valid = false;
        }

        // If validation fails, prevent form submission
        if (!valid) {
            event.preventDefault();
        }
    });
</script>

</section><!-- End Contact Section -->

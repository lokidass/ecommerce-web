<?php
include('header.php');
include('sidebar.php');

// Handle form submission for adding or updating contact info
if (isset($_POST['submit'])) {
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $secondary_phone = mysqli_real_escape_string($con, $_POST['secondary_phone']);
    $instagram = mysqli_real_escape_string($con, $_POST['instagram']);
    $facebook = mysqli_real_escape_string($con, $_POST['facebook']);
    $google_maps_link = mysqli_real_escape_string($con, $_POST['google_maps_link']);
    $youtube = mysqli_real_escape_string($con, $_POST['youtube']);
    $time = mysqli_real_escape_string($con, $_POST['time']);
    
    // Check if the contact info already exists
    $check = mysqli_query($con, "SELECT * FROM contact_info WHERE id = 1");
    if (mysqli_num_rows($check) > 0) {
        // Update the contact info
        $update = mysqli_query($con, "UPDATE contact_info 
                                      SET address='$address', email='$email', phone='$phone', secondary_phone='$secondary_phone', 
                                          instagram='$instagram', facebook='$facebook', google_maps_link='$google_maps_link', youtube='$youtube', time='$time' 
                                      WHERE id=1");
        if ($update) {
            echo "<script>alert('Contact information updated successfully!'); window.location.href = 'contactedit.php';</script>";
        } else {
            echo "<script>alert('Failed to update contact information.'); window.location.href = 'contactedit.php';</script>";
        }
    } else {
        // Insert new contact info
        $insert = mysqli_query($con, "INSERT INTO contact_info (address, email, primary_phone, secondary_phone, instagram, facebook, google_maps_link, youtube) 
                                      VALUES ('$address', '$email', '$primary_phone', '$secondary_phone', '$instagram', '$facebook', '$google_maps_link', '$youtube')");
        if ($insert) {
            echo "<script>alert('Contact information added successfully!'); window.location.href = 'contactedit.php';</script>";
        } else {
            echo "<script>alert('Failed to add contact information.'); window.location.href = 'contactedit.php';</script>";
        }
    }
}

// Retrieve existing contact info
$query = mysqli_query($con, "SELECT * FROM contact_info WHERE id = 1");
$contact_info = mysqli_fetch_array($query) ;
?>
<style>
    .form-control{
        background-color:white;
    }
    </style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Contact Info</h1>
        <nav class="breadcrumb-container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Contact Info</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="pagetitle">
        <div class="text-edit-table">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title">Contact Information</h5>
                    
                    <!-- Form for editing contact info -->
                    <form action="contactedit.php" method="POST" id="contactForm">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Bussiness Address</td>
                                    <td><textarea class="form-control" name="address" id="address" rows="3" disabled><?= $contact_info['address'] ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>Primary Phone Number</td>
                                    <td><input type="text" class="form-control" name="phone" id="phone" value="<?= $contact_info['phone'] ?>" disabled></td>
                                    
                                </tr>
                                <tr>
                                    <td>Secondary Phone Number</td>
                                    <td><input type="text" class="form-control" name="secondary_phone" id="secondary_phone" value="<?= $contact_info['secondary_phone'] ?>" disabled></td>
                                    
                                </tr>
                                <tr>
                                    <td>Bussiness Email</td>
                                    <td><input type="email" class="form-control" name="email" id="email" value="<?= $contact_info['email'] ?>" disabled></td>
                                </tr>
                                
                                <tr>
                                    <td>Google Maps Link</td>
                                    <td><input type="url" class="form-control" name="google_maps_link" id="google_maps_link" value="<?= $contact_info['google_maps_link'] ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Facebook Link</td>
                                    <td><input type="url" class="form-control" name="facebook" id="facebook" value="<?= $contact_info['facebook'] ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Instagram Link</td>
                                    <td><input type="url" class="form-control" name="instagram" id="instagram" value="<?= $contact_info['instagram'] ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>YouTube Link</td>
                                    <td><input type="url" class="form-control" name="youtube" id="google_maps_link" value="<?= $contact_info['youtube'] ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Opening Timing</td>
                                    <td><input type="text" class="form-control" name="time" id="google_maps_link" value="<?= $contact_info['time'] ?>" disabled></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Save Changes button (initially hidden) -->
                        <button type="submit" name="submit" id="saveButton" class="btn btn-primary" style="display: none;">Save Changes</button>
                    </form>

                    <!-- Edit button -->
                    <button type="button" id="editButton" class="btn btn-warning">Edit Info</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php') ?>

<!-- JavaScript to handle edit and save functionality -->
<script>
document.getElementById('editButton').addEventListener('click', function() {
    // Enable all input fields
    document.querySelectorAll('#contactForm input, #contactForm textarea').forEach(function(element) {
        element.disabled = false;
    });

    // Show the Save Changes button
    document.getElementById('saveButton').style.display = 'inline-block';

    // Hide the Edit button
    this.style.display = 'none';
});
</script>

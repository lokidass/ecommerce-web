<?php include('header.php') ?>
<div style="height: 10px;"></div> <!-- Spacer div, adjust height as needed -->

<div class="container-xxl py-4"> 
    <div class="container my-3 py-3 px-lg-5"> 
        <div class="row g-3"> 
            <div class="col-12 text-center">
                <h1 class="text-dark animated slideInDown">Terms and Conditions</h1>
                <hr class="bg-dark mx-auto mt-0" style="width: 90px;">
            </div>
        </div>
    </div>
</div>
<!-- Navbar & Hero End -->
<?php

// Fetch contact info from the database
$query = "SELECT email, phone FROM contact_info WHERE id = 1"; // Assuming id=1 is for Vimal Marketing and Services
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $contact_info = mysqli_fetch_assoc($result);
    $email = $contact_info['email'];
    $phone = $contact_info['phone'];
} else {
    $email = 'vimalmarketing@example.com'; // Default email if not found
    $phone = '+91 9876543210'; // Default phone if not found
}
?>

<!-- Terms and Conditions Start -->
<div class="container-xxl py-5">
    <div class="container py-5 px-lg-5">
        <div class="row">
            <div class="col-12" style="text-align: justify;">
                <h2 class="mb-4">Terms and Conditions for Vimal Marketing and Services</h2>
                
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing or using our website and services, you agree to comply with and be bound by these Terms and Conditions. If you do not agree with these terms, please do not use our services or website.</p>
                
                <h3>2. Use of Services</h3>
                <p>Our services are intended for lawful use only. By using our services, you agree not to:</p>
                <ul>
                    <li>Engage in any illegal activities.</li>
                    <li>Attempt to disrupt the operation of our website or services.</li>
                    <li>Upload or transmit viruses or harmful code.</li>
                    <li>Use our services to infringe upon the rights of others.</li>
                </ul>
                
                <h3>3. Intellectual Property</h3>
                <p>All content, materials, trademarks, and logos on our website are the property of Vimal Marketing and Services or its licensors. You are not permitted to use, reproduce, or distribute any content from our website without prior written consent from us.</p>
                
                <h3>4. Product and Service Descriptions</h3>
                <p>We strive to provide accurate information regarding the water purification systems, solar technology products, and services we offer. However, we do not warrant that product descriptions, pricing, or other content on our website are free of errors, inaccuracies, or omissions. We reserve the right to correct such errors and to change or update information at any time without prior notice.</p>
                
                <h3>5. Limitation of Liability</h3>
                <p>Vimal Marketing and Services will not be liable for any direct, indirect, incidental, consequential, or punitive damages arising from the use of our website or services. This includes, but is not limited to, damages for loss of profits, data, or other intangible losses resulting from:</p>
                <ul>
                    <li>Your use or inability to use our services.</li>
                    <li>Unauthorized access to your data.</li>
                    <li>Any third-party conduct on our website or services.</li>
                </ul>
                
                <h3>6. Warranty Disclaimer</h3>
                <p>warranty will be provided only if it is applicable to the product. We do not guarantee that our services will be uninterrupted, error-free, or completely secure.</p>
                
                <h3>7. Governing Law</h3>
                <p>These Terms and Conditions are governed by and construed in accordance with the laws of [Your Jurisdiction]. Any disputes arising from or related to the use of our website or services will be subject to the exclusive jurisdiction of the courts in [Your Jurisdiction].</p>
                
                <h3>8. Changes to Terms</h3>
                <p>We reserve the right to update or modify these Terms and Conditions at any time. Changes will be posted on our website, and it is your responsibility to review these Terms periodically. Your continued use of our services following any changes constitutes your acceptance of the updated Terms.</p>
                
                <h3>9. Contact Us</h3>
                <p>If you have any questions or concerns regarding these Terms and Conditions, please contact us at:</p>
                <p>Vimal Marketing and Services<br>
                   Email: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br>
                   Phone: <?php echo $phone; ?></p>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php') ?>

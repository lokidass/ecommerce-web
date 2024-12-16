<?php include('header.php') ?>
<div style="height: 10px;"></div> <!-- Spacer div, adjust height as needed -->

<div class="container-xxl py-4"> 
    <div class="container my-3 py-3 px-lg-5"> 
        <div class="row g-3"> 
            <div class="col-12 text-center">
                <h1 class="text-dark animated slideInDown">Privacy Policy</h1>
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
    $email = 'vimalservices@example.com'; // Default email if not found
    $phone = '+91 9876543210'; // Default phone if not found
}
?>

<!-- Privacy Policy Start -->
<div class="container-xxl py-5">
    <div class="container py-5 px-lg-5">
        <div class="row">
            <div class="col-12" style="text-align: justify;">
                <h2 class="mb-4">Privacy Policy for Vimal Marketing and Services</h2>
                
                <h3>1. Introduction</h3>
                <p>Vimal Marketing and Services is committed to protecting your privacy. This Privacy Policy outlines how we collect, use, disclose, and safeguard your information when you visit our website and use our services. By using our website, you consent to the practices described in this policy.</p>
                
                <h3>2. Information We Collect</h3>
                <p>We may collect the following types of information:</p>
                <ul>
                    <li><strong>Personal Information:</strong> This includes your name, email address, phone number, and billing information when you purchase water purification systems, solar technology products, or request maintenance services.</li>
                    <li><strong>Non-Personal Information:</strong> This includes data such as your IP address, browser type, operating system, and the pages you visit on our website, which helps us enhance your experience and analyze site usage.</li>
                </ul>
                
                <h3>3. How We Use Your Information</h3>
                <p>We use the information we collect for the following purposes:</p>
                <ul>
                    <li><strong>Service Provision:</strong> To process your orders, manage your account, and deliver the water purification and solar technology products or services you purchase.</li>
                    <li><strong>Communication:</strong> To contact you regarding your orders, respond to your inquiries, and send relevant service updates.</li>
                    <li><strong>Improvement of Services:</strong> To enhance our website, products, and services based on your feedback and site usage data.</li>
                    <li><strong>Legal Compliance:</strong> To comply with legal obligations and protect our rights and the rights of our customers.</li>
                </ul>
                
                <h3>4. How We Share Your Information</h3>
                <p>We do not sell or rent your personal information to third parties. However, we may share your information with:</p>
                <ul>
                    <li><strong>Service Providers:</strong> Trusted third parties that help us operate our business, such as payment processors and installation partners.</li>
                    <li><strong>Legal Requirements:</strong> We may disclose your information when required by law or to protect our rights and the safety of our customers.</li>
                </ul>
                
                <h3>5. Cookies and Tracking Technologies</h3>
                <p>Our website uses cookies and similar tracking technologies to enhance your browsing experience. Cookies are small files that a website transfers to your device to capture and remember certain information.</p>
                <ul>
                    <li><strong>Types of Cookies Used:</strong> We use session cookies (which expire when you close your browser) and persistent cookies (which remain until you delete them) to remember your preferences and analyze website usage.</li>
                    <li><strong>Managing Cookies:</strong> You can set your browser to refuse cookies or alert you when they are being sent. Disabling cookies may affect the functionality of our website.</li>
                </ul>
                
                <h3>6. Data Security</h3>
                <p>We implement various security measures to protect your personal information. Your information is stored in secured networks and is accessible only by authorized personnel with special access rights, who are required to keep it confidential.</p>
                
                <h3>7. Third-Party Links</h3>
                <p>Our website may contain links to third-party websites. These sites have their own privacy policies, and we are not responsible for their content or activities. We encourage you to review their privacy policies.</p>
                
                <h3>8. Children’s Privacy</h3>
                <p>Our services are not directed to individuals under 18. We do not knowingly collect personal information from children under 18. If we become aware that we have inadvertently received such information, we will delete it promptly.</p>
                
                <h3>9. Your Rights</h3>
                <p>You have the right to:</p>
                <ul>
                    <li><strong>Access:</strong> Request a copy of the personal information we hold about you.</li>
                    <li><strong>Rectification:</strong> Request corrections to inaccuracies in your personal information.</li>
                    <li><strong>Erasure:</strong> Request deletion of your personal information where applicable.</li>
                    <li><strong>Objection:</strong> Object to the processing of your personal information for marketing purposes.</li>
                    <li><strong>Data Portability:</strong> Request the transfer of your personal information to another party.</li>
                </ul>
                <p>To exercise these rights, please contact us using the contact details below.</p>
                
                <h3>10. Changes to This Privacy Policy</h3>
                <p>We may update this Privacy Policy occasionally to reflect changes in our practices or for legal or regulatory reasons. Significant changes will be communicated by posting the new policy on our website and updating the "Last Updated" date.</p>
                
                <h3>11. Contact Us</h3>
                <p>If you have questions or concerns regarding this Privacy Policy or how we handle your personal information, please contact us at:</p>
                <p>Vimal Marketing and Services<br>
   Email: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br>
   Phone: <?php echo $phone; ?></p>
            </div>
        </div>
    </div>
</div>
<!-- Privacy Policy End -->

<?php include('footer.php') ?>

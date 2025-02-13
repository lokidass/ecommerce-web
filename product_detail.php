<style>
h1 {

    margin: 0;
    padding: 10px;
    background-color:rgb(91, 156, 241);
    font-weight: 500;
    transition: .5s;
    /* border-radius: 20px; */
    color: white;
}

.text-justify {
    text-align: justify;
}

.related-product-card {
    width: 100%; /* Ensures it takes full available width in smaller screens */
}

@media (min-width: 576px) {
    
    .related-product-card {
        width: 170px; /* Use a fixed width for larger screens */
    }
}
</style>
<?php
include('header.php');
// Database connection (assuming `$con` is already initialized)

// Get product ID from the URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Fetch product details including category position
$query = "SELECT product_images.*, categories.name AS category_name, categories.position AS category_position 
          FROM product_images 
          LEFT JOIN categories ON product_images.category_id = categories.id
          WHERE product_images.id = $product_id";

$result = mysqli_query($con, $query);

// Check if the product exists
if (mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Product not found'); window.location.href = 'index.php';</script>";
    exit();
}

// Fetch related products from the same category
$relatedQuery = "SELECT * FROM product_images 
                 WHERE category_id = " . intval($product['category_id']) . " 
                 AND id != $product_id
                 ORDER BY RAND() LIMIT 8"; // Limit increased to fit scrolling view
$relatedProducts = mysqli_query($con, $relatedQuery);

// Fetch sub-images for the product
$subImagesQuery = "SELECT * FROM product_sub_images WHERE product_id = $product_id";
$subImagesResult = mysqli_query($con, $subImagesQuery);

// Fetch contact information
$sql = "SELECT address, email, phone, secondary_phone, instagram, facebook, google_maps_link, youtube 
        FROM contact_info WHERE id = 1";
$result = mysqli_query($con, $sql);

// Initialize variables
$address = $email = $phone = $google_maps_link = $instagram = $facebook = $youtube = "";

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $address = $row['address'];
    $email = $row['email'];
    $phone = $row['phone'];
    $secondary_phone = $row['secondary_phone'];
    $google_maps_link = $row['google_maps_link'];
    $instagram = $row['instagram'];
    $facebook = $row['facebook'];
    $youtube = $row['youtube'];
}
?>

<main id="main" class="main">
    <div class="pagetitle text-center">
        <br><br>
        <h1 style="color:white;">
           <?= htmlspecialchars($product['category_name']) ?>
        </h1>
        <nav class="breadcrumb-container">
            <ol class="breadcrumb justify-content-center">
                <!-- Breadcrumb items can go here if needed -->
            </ol>
        </nav>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <!-- Main product image -->
            <div class="col-md-5 col-12 mb-4">
                <img id="mainImage" 
                     src="admin/uploads/productimages/<?= htmlspecialchars($product['img']) ?>" 
                     class="img-fluid" 
                     alt="<?= htmlspecialchars($product['title']) ?>" 
                     style="width: 100%; max-width: 230px; height: 230px;">

                <!-- Gallery for sub-images -->
                <div class="sub-image-gallery mt-3">
                    <div class="row justify-content-center">
                        <!-- Display the main image as the first sub-image -->
                        <div class="col-3">
                            <img src="admin/uploads/productimages/<?= htmlspecialchars($product['img']) ?>" 
                                 class="img-fluid sub-image" 
                                 alt="<?= htmlspecialchars($product['title']) ?>" 
                                 data-image="admin/uploads/productimages/<?= htmlspecialchars($product['img']) ?>"
                                 style="width: 100%; max-width: 230px; height: 100px;">
                        </div>

                        <!-- Display other sub-images -->
                        <?php while ($subImage = mysqli_fetch_assoc($subImagesResult)) { ?>
                            <div class="col-3">
                                <img src="admin/uploads/product_sub_images/<?= htmlspecialchars($subImage['image']) ?>" 
                                     class="img-fluid sub-image" 
                                     alt="<?= htmlspecialchars($subImage['title']) ?>" 
                                     data-image="admin/uploads/product_sub_images/<?= htmlspecialchars($subImage['image']) ?>"
                                     style="width: 100%; max-width: 230px; height: 100px;">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Product details -->
            <div class="col-md-7 col-12 text-justify">
            <p style="font-size:110%; font-weight:bold; color:black;"><strong><?= htmlspecialchars($product['title']) ?></strong></p>

                <p><strong>Category:</strong>    <?= htmlspecialchars($product['category_name']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
                <p><strong>MRP:</strong> <s>₹<?= number_format($product['costing'], 2) ?></s></p>
                <p><strong>Discounted Price:</strong> ₹<?= number_format($product['discounted_price'], 2) ?></p>
                <div class="mt-4">
                    <a href="https://wa.me/+91<?php echo $phone; ?>?text=Hi, I am interested in the product:%0A
                    - Product: <?= urlencode($product['title']) ?>%0A
                    - Category: <?= urlencode($product['category_name']) ?>%0A
                    - MRP: ₹<?= number_format($product['costing'], 2) ?>%0A
                    - Discounted Price: ₹<?= number_format($product['discounted_price'], 2) ?>%0A
                    - Image: <?= urlencode("https://dfg.com/admin/uploads/productimages/" . htmlspecialchars($product['img'])) ?>" 
                    class="btn text-white py-2 px-4 px-sm-5 rounded-pill me-3" target="_blank">Enquire Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section Start -->
    <div class="container py-5">
        <h3>Related Products</h3>
        <div class="related-products-container d-flex overflow-auto">
            <?php while ($related = mysqli_fetch_assoc($relatedProducts)) { ?>
                <div class="related-product-card d-flex flex-column position-relative col-1" style="width: 170px; margin-right: 15px; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
                    <a href="product_detail.php?product_id=<?= htmlspecialchars($related['id']) ?>" class="text-decoration-none d-flex flex-column flex-grow-1">
                        <img src="admin/uploads/productimages/<?= htmlspecialchars($related['img']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($related['title']) ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h5 class="card-title mb-2" style="font-size: 14px;">
                                <?= htmlspecialchars($related['title']) ?>
                            </h5>
                            <s class="card-text mb-1" style="font-size: 13px; color: black;">
                                ₹<?= number_format($related['costing'], 2) ?>
                            </s>
                            <p class="card-text mb-3" style="font-size: 14px; color: black;">
                                ₹<?= number_format($related['discounted_price'], 2) ?>
                            </p>
                        </div>
                    </a>
                    <a href="product_detail.php?product_id=<?= htmlspecialchars($related['id']) ?>" class="btn text-white btn-sm position-absolute bottom-0 start-0 w-100" style="border-radius: 0 0 10px 10px;">View Details</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- Related Products Section End -->
</main>

<?php include('footer.php'); ?>

<!-- JavaScript for handling main image change -->
<script>
document.querySelectorAll('.sub-image').forEach(function (image) {
    image.addEventListener('click', function () {
        var newImage = image.getAttribute('data-image');
        document.getElementById('mainImage').src = newImage;
    });
});
</script>

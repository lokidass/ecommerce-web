<style>
/* General styles for gaps */
</style>


<?php
include 'config.php';

if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    if ($category_id == 0) {
        // Query to fetch 5 products per category for All Categories
        $query = "SELECT product_images.*, categories.name AS category_name 
                  FROM product_images 
                  LEFT JOIN categories ON product_images.category_id = categories.id 
                  ORDER BY categories.position, product_images.id DESC";

        $result = mysqli_query($con, $query);

        $lastCategory = '';
        $productCount = 0;

        echo '
        <div style="margin-top:-30px;"></div>
        <div class="container py-5" style="max-width: 1200px; margin: 0 auto;">';

        while ($product = mysqli_fetch_assoc($result)) {
            if ($lastCategory != $product['category_name']) {
                if ($productCount > 0) {
                    echo '</div> <div style="margin-top:50px;">'; // Close previous row
                }

                $lastCategory = $product['category_name'];
                $productCount = 0;

                // Render category heading and start new row
                echo '<h4 class="mt-0 mb-3">' . htmlspecialchars($lastCategory) . '</h4>';
                echo '<div class="row row-cols-2 row-cols-md-4 g-4">'; // Ensure 2 items per row on mobile
            }

            // Render product if within limit
            if ($productCount < 4) {
                echo '
                <div class="col">
                    <div class="related-product-card d-flex flex-column position-relative" style="border: 1px solid #ddd; border-radius: 10px; overflow: hidden; height: 100%; margin-bottom: 20px;">
                        <a href="product_detail.php?product_id=' . htmlspecialchars($product['id']) . '" class="text-decoration-none d-flex flex-column flex-grow-1">
                            <img src="admin/uploads/productimages/' . htmlspecialchars($product['img']) . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($product['title']) . '" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column flex-grow-1">
                                <h5 class="card-title mb-2" style="font-size: 13px;">'
                                    . htmlspecialchars($product['title']) . '
                                </h5>
                                <s class="card-text mb-1" style="font-size: 12px; color: black;">
                                    ₹' . number_format($product['costing'], 2) . '
                                </s>
                                <p class="card-text mb-3" style="font-size: 13px; color: black;">
                                    ₹' . number_format($product['discounted_price'], 2) . '
                                </p>
                            </div>
                        </a>
                        <a href="product_detail.php?product_id=' . htmlspecialchars($product['id']) . '" class="btn text-white btn-sm position-absolute bottom-0 start-0 w-100" style="border-radius: 0 0 10px 10px;">View Details</a>
                    </div>
                </div>
                ';
                $productCount++;
            }
        }

        echo '</div>'; // Close last row
        echo '</div>
        '; // Close container
    } else {
        // Query to fetch all products for a specific category
        $query = "SELECT product_images.*, categories.name AS category_name 
                  FROM product_images 
                  LEFT JOIN categories ON product_images.category_id = categories.id 
                  WHERE product_images.category_id = $category_id 
                  ORDER BY product_images.id DESC";

        $result = mysqli_query($con, $query);

        // Fetch category title
        $category_query = "SELECT name FROM categories WHERE id = $category_id";
        $category_result = mysqli_query($con, $category_query);
        $category = mysqli_fetch_assoc($category_result);

        echo '<div style="margin-top:-30px;"><div class="container py-5" style="max-width: 1200px; margin: 0 auto;">';
        if ($category) {
            echo '<h3 class="mb-4">' . htmlspecialchars($category['name']) . '</h3>';
        }
        echo '<div class="row row-cols-2 row-cols-md-4 g-4">'; // Ensure 2 items per row on mobile

        while ($product = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col">
                <div class="related-product-card d-flex flex-column position-relative" style="border: 1px solid #ddd; border-radius: 10px; overflow: hidden; height: 100%; margin-bottom: 20px;">
                    <a href="product_detail.php?product_id=' . htmlspecialchars($product['id']) . '" class="text-decoration-none d-flex flex-column flex-grow-1">
                        <img src="admin/uploads/productimages/' . htmlspecialchars($product['img']) . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($product['title']) . '" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h5 class="card-title mb-2" style="font-size: 14px;">'
                                . htmlspecialchars($product['title']) . '
                            </h5>
                            <s class="card-text mb-1" style="font-size: 13px; color: black;">
                                ₹' . number_format($product['costing'], 2) . '
                            </s>
                            <p class="card-text mb-3" style="font-size: 14px; color: black;">
                                ₹' . number_format($product['discounted_price'], 2) . '
                            </p>
                        </div>
                    </a>
                    <a href="product_detail.php?product_id=' . htmlspecialchars($product['id']) . '" class="btn text-white btn-sm position-absolute bottom-0 start-0 w-100" style="border-radius: 0 0 10px 10px;">View Details</a>
                </div>
            </div>';
        }

        echo '</div>'; // Close row
        echo '</div>
        <div style="margin-top:30px;">'; // Close container
    }
}
?>

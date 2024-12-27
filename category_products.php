<?php include('header.php') ?>

<style>
    .category-sidebar {
        padding: 15px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 10px;
    }

    .category-sidebar h4 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .list-group-item {
        border: none;
        padding: 5px 0;
    }

    .list-group-item label {
        margin-left: 8px;
        font-weight: normal;
        cursor: pointer;
    }

    .product-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .product-image img {
        max-width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #555;
    }

    .product-pricing .discounted-price {
        color: #ff4b3a;
        font-weight: bold;
        margin-right: 8px;
    }

    .product-pricing .original-price {
        color: #888;
        font-size: 14px;
    }



    @media (max-width: 768px) {
        .category-sidebar {
            margin-bottom: 20px;
        }

        .related-product-card {
            width: 100% !important;
            margin-right: 0 !important;
            margin-bottom: 15px;
        }
    }
</style>

<?php
// Assuming the categoryId is passed in $_POST and default is 'All Categories'
$categoryId = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;

// Base query for products
$query = "SELECT product_images.*, categories.name AS category_name FROM product_images 
          LEFT JOIN categories ON product_images.category_id = categories.id";

// If a specific category is selected, filter the products by category
if ($categoryId !== null && $categoryId > 0) {
    $query .= " WHERE product_images.category_id = " . $categoryId;
}

$query .= " ORDER BY product_images.id DESC"; // You can limit it based on your need

// Execute the query
$selection = mysqli_query($con, $query);

// Query for categories with product count
$categoryQuery = mysqli_query($con, "
    SELECT c.id, c.name, COUNT(p.id) AS product_count
    FROM categories c
    LEFT JOIN product_images p ON c.id = p.category_id
    GROUP BY c.id
    ORDER BY c.name ASC
");

?>

<!-- Service Start -->
<div class="container-xxl py-0">
    <div class="container py-0 px-lg-5">
        <div class="row">
            <!-- Sidebar for Category Filter -->
            <div class="col-lg-3">
                <div class="category-sidebar">
                    <form method="POST" id="categoryForm">
                        <h4 class="text-secondary">Filter by Category</h4>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="radio" name="category_id" value="0" id="category_all" onchange="this.form.submit()" 
                                <?= (!isset($_POST['category_id']) || $_POST['category_id'] == 0) ? 'checked' : '' ?>>
                                <label for="category_all">All Categories</label>
                            </li>
                            <?php
                            while ($row = mysqli_fetch_assoc($categoryQuery)) {
                                $isSelected = (isset($_POST['category_id']) && $_POST['category_id'] == $row['id']) ? 'checked' : '';
                                echo '
                                <li class="list-group-item">
                                    <input type="radio" name="category_id" value="' . htmlspecialchars($row['id']) . '" id="category_' . htmlspecialchars($row['id']) . '" onchange="this.form.submit()" ' . $isSelected . '>
                                    <label for="category_' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . ' (' . $row['product_count'] . ')</label>
                                </li>';
                            }
                            ?>
                        </ul>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Display Products based on selected category -->
                <?php
                $lastCategory = '';
                while ($product = mysqli_fetch_array($selection)) {
                    if ($lastCategory != $product['category_name']) {
                        if ($lastCategory != '') {
                            echo '</div>';
                        }
                        echo '<h3 class="category-heading d-flex justify-content-between align-items-center">';
                        echo htmlspecialchars($product['category_name']);
                        echo '</h3>';
                        echo '<div class="related-products-container d-flex overflow-auto" style="margin-bottom: 20px;">';
                    }

                    ?>
                    <div class="related-product-card d-flex flex-column position-relative" style="width: 170px; margin-right: 15px; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
                        <a href="product_detail.php?product_id=<?= htmlspecialchars($product['id']) ?>" class="text-decoration-none d-flex flex-column flex-grow-1">
                            <img src="admin/uploads/productimages/<?= htmlspecialchars($product['img']) ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($product['title']) ?>" style="height: auto; object-fit: cover;">
                            <div class="card-body d-flex flex-column flex-grow-1">
                                <h5 class="card-title mb-2" style="font-size: 14px;"><?= htmlspecialchars($product['title']) ?></h5>
                                <s class="card-text mb-1" style="font-size: 13px; color: black;">₹<?= number_format($product['costing'], 2) ?></s>
                                <p class="card-text mb-3" style="font-size: 14px; color: black;">₹<?= number_format($product['discounted_price'], 2) ?></p>
                            </div>
                        </a>
                        <a href="product_detail.php?product_id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-primary btn-sm bottom-0 start-0 w-100" style="border-radius: 0 0 10px 10px;">View Details</a>
                    </div>
                    <?php
                    $lastCategory = $product['category_name'];
                    
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->

<?php include('footer.php'); ?>

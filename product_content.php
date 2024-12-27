<style>
    .related-products-container {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 10px;
        flex-wrap: wrap; /* Allow products to wrap on smaller screens */
        justify-content: flex-start; /* Ensure left alignment */
    }

    .related-product-card {
        max-width: 100%;
        margin-right: 15px;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
        box-sizing: border-box; /* Ensures padding doesn't mess with width */
        margin-bottom: 20px;
    }

    .related-product-card img {
        height: auto;
        object-fit: cover;
        width: 100%; /* Make sure images are responsive */
    }

    .related-product-card .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        padding: 10px;
    }

    .related-product-card .btn {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        border-radius: 0 0 10px 10px;
    }

    /* Sidebar styling for mobile */
    .category-sidebar {
        padding-right: 15px;
    }

    .category-sidebar form {
        position: sticky;
        top: 100;
        z-index: 100;
    }

    /* Mobile responsive adjustments */
    @media (max-width: 1200px) {
        .related-product-card {
            min-width: 230px;
        }
    }

    @media (max-width: 992px) {
        .related-product-card {
            min-width: 200px;
        }

        .category-sidebar {
            flex: 1;
            margin-bottom: 15px;
        }

        .container-xxl {
            margin-left: 0;
            margin-right: 0;
        }

        .related-products-container {
            flex-wrap: wrap; /* Allow items to wrap on smaller screens */
            justify-content: center; /* Center products on mobile */
            margin: 0; /* Remove margin for mobile view */
        }

        .category-sidebar {
            width: 100%; /* Full width for sidebar on mobile */
        }

        .col-lg-3, .col-lg-9 {
            width: 100%;
            padding: 0;
        }
    }

    /* Small screens (mobile) */
    @media (max-width: 576px) {
        .related-product-card {
            min-width: 160px;
            margin-left:-10px;
        }

        .related-product-card img {
            height: 180px; /* Set a fixed height for mobile */
        }

        .category-sidebar {
            margin-bottom: 20px; /* Spacing between sidebar and products */
        }

        /* Additional spacing for better mobile display */
        .category-heading {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .col {
            margin-bottom: 15px; /* Ensure proper spacing between products */
        }
    }
</style>

<div class="" style="margin-top:-100px;"></div>
<div class="container-xxl py-1 mx-000">
    <div class="container py-0 px-lg-0">
        <div class="row">
            <!-- Sidebar for Category Filter -->
            <div class="col-lg-3 category-sidebar">
                <form method="POST" id="categoryForm" class="d-none d-lg-block">
                    <h4 class="text-black">Product Category</h4>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <input type="radio" name="category_id" value="0" id="category_all" checked>
                            <label for="category_all">All Categories</label>
                        </li>
                        <?php
                        $categoryQuery = mysqli_query($con, "
                        SELECT 
                            id, 
                            name, 
                            (SELECT COUNT(*) FROM product_images WHERE category_id = categories.id) AS product_count 
                        FROM categories 
                        ORDER BY position ASC
                    ");
                    
                        while ($row = mysqli_fetch_assoc($categoryQuery)) {
                            echo '
                            <li class="list-group-item">
                                <input type="radio" name="category_id" value="' . htmlspecialchars($row['id']) . '" id="category_' . htmlspecialchars($row['id']) . '">
                                <label for="category_' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . ' (' . $row['product_count'] . ')</label>
                            </li>';
                        }
                        ?>
                    </ul>
                </form>

                <div class="d-block d-lg-none">
                    <h4 class="text-black mb-2">Product Category</h4>
                    <select id="categoryDropdown" class="form-select">
                        <option value="0" selected>All Categories</option>
                        <?php
                        $categoryQuery = mysqli_query($con, "SELECT id, name, (SELECT COUNT(*) FROM product_images WHERE category_id = categories.id) AS product_count FROM categories");
                        while ($row = mysqli_fetch_assoc($categoryQuery)) {
                            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . ' (' . $row['product_count'] . ')</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Product Container -->
            <div id="productContainer" class="col-lg-9">
                <?php
                $query = "SELECT product_images.*, categories.name AS category_name 
                          FROM product_images 
                          LEFT JOIN categories ON product_images.category_id = categories.id 
                          ORDER BY categories.name, product_images.id DESC";
                $result = mysqli_query($con, $query);

                $lastCategory = '';

                while ($product = mysqli_fetch_assoc($result)) {
                    if ($lastCategory != $product['category_name']) {
                        if ($lastCategory != '') {
                            echo '</div>';
                        }
                        echo '<h3 class="category-heading d-flex justify-content-between align-items-center">';
                        echo htmlspecialchars($product['category_name']);
                        echo '</h3>';
                        echo '<div class="related-products-container">';
                        $lastCategory = $product['category_name'];
                    }

                    echo '
                    <div class="col">
                    <div class="related-product-card d-flex flex-column position-relative" style="border: 1px solid #ddd; border-radius: 10px; overflow: hidden; height: 100%; margin-bottom: 20px;">
                        <a href="product_detail.php?product_id=' . htmlspecialchars($product['id']) . '" class="text-decoration-none d-flex flex-column flex-grow-1">
                            <img src="admin/uploads/productimages/' . htmlspecialchars($product['img']) .'" 
                                class="card-img-top img-fluid" 
                                alt="' . htmlspecialchars($product['title']) .'" 
                                style="width: 100%;">

                            <div class="card-body d-flex flex-column flex-grow-1">
                                <h5 class="card-title mb-2" style="font-size: 14px;">' . htmlspecialchars($product['title']) . '</h5>
                                <s class="card-text mb-1" style="font-size: 13px; color: black;">₹' . number_format($product['costing'], 2) . '</s>
                                <p class="card-text mb-3" style="font-size: 14px; color: black;">₹' . number_format($product['discounted_price'], 2) . '</p>
                            </div>
                        </a>
                        <a href="product_detail.php?product_id=' . htmlspecialchars($product['id']) . '" class="btn text-white btn-sm">View Details</a>
                    </div>
                    </div>';
                }

                if ($lastCategory != '') {
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    // Radio button change
    $('input[name="category_id"]').on('change', function () {
        var categoryId = $(this).val();
        fetchProducts(categoryId);
    });

    // Dropdown change for mobile view
    $('#categoryDropdown').on('change', function () {
        var categoryId = $(this).val();
        fetchProducts(categoryId);
    });

    // Trigger initial load
    $('input[name="category_id"]:checked').trigger('change');

    function fetchProducts(categoryId) {
        $.ajax({
            url: 'fetch_products.php',
            type: 'POST',
            data: { category_id: categoryId },
            success: function (response) {
                $('#productContainer').html(response);
            },
            error: function () {
                alert('Failed to load products. Please try again.');
            }
        });
    }
});


    $(document).ready(function () {
        $('input[name="category_id"]').on('change', function () {
            var categoryId = $(this).val();
            $.ajax({
                url: 'fetch_products.php',
                type: 'POST',
                data: { category_id: categoryId },
                success: function (response) {
                    $('#productContainer').html(response);
                },
                error: function () {
                    alert('Failed to load products. Please try again.');
                }
            });
        });

        // Trigger initial load
        $('input[name="category_id"]:checked').trigger('change');
    });
</script>

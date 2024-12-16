<?php
include('header.php');
include('sidebar.php');
include('config.php'); // Replace with your database connection file

// Handle product submission
if (isset($_POST['submitpr'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $costing = (float)$_POST['costing'];
    $discounted_price = (float)$_POST['discounted_price'];
    $img = '';

    // Handle main product image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img = basename($_FILES['image']['name']);
        $target = "uploads/productimages/" . $img;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Failed to upload main product image.");
        }
    }

    // Insert product data into product_images table
    $stmt = $con->prepare("INSERT INTO product_images (title, category_id, description, costing, discounted_price, img) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdds", $title, $category_id, $description, $costing, $discounted_price, $img);
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;

        // Handle sub-images
        for ($i = 1; $i <= 5; $i++) {
            if (isset($_FILES["sub_image_$i"]) && $_FILES["sub_image_$i"]["error"] === UPLOAD_ERR_OK) {
                $sub_img = basename($_FILES["sub_image_$i"]["name"]);
                $target_sub_img = "uploads/sub_images/" . $sub_img;
                if (move_uploaded_file($_FILES["sub_image_$i"]["tmp_name"], $target_sub_img)) {
                    $stmt_sub_img = $con->prepare("INSERT INTO sub_images (product_id, sub_image) VALUES (?, ?)");
                    $stmt_sub_img->bind_param("is", $product_id, $sub_img);
                    $stmt_sub_img->execute();
                }
            }
        }

        echo "<script>alert('Product added successfully!'); window.location.href = 'productedit.php';</script>";
    } else {
        die("Error: " . $stmt->error);
    }
}

// Handle product update
if (isset($_POST['update_product_image'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $costing = (float)$_POST['costing'];
    $discounted_price = (float)$_POST['discounted_price'];

    // Handle main image update
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img = basename($_FILES['image']['name']);
        $target = "uploads/productimages/" . $img;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmt = $con->prepare("UPDATE product_images SET img=? WHERE id=?");
            $stmt->bind_param("si", $img, $id);
            $stmt->execute();
        }
    }

    // Update other details
    $stmt = $con->prepare("UPDATE product_images SET title=?, category_id=?, description=?, costing=?, discounted_price=? WHERE id=?");
    $stmt->bind_param("sisddi", $title, $category_id, $description, $costing, $discounted_price, $id);
    if ($stmt->execute()) {
        for ($i = 1; $i <= 5; $i++) {
            if (isset($_FILES["sub_image_$i"]) && $_FILES["sub_image_$i"]["error"] === UPLOAD_ERR_OK) {
                $sub_img = basename($_FILES["sub_image_$i"]["name"]);
                $target_sub_img = "uploads/sub_images/" . $sub_img;
                if (move_uploaded_file($_FILES["sub_image_$i"]["tmp_name"], $target_sub_img)) {
                    $stmt_sub_img = $con->prepare("INSERT INTO sub_images (product_id, sub_image) VALUES (?, ?)");
                    $stmt_sub_img->bind_param("is", $id, $sub_img);
                    $stmt_sub_img->execute();
                }
            }
        }
        echo "<script>alert('Product updated successfully!'); window.location.href = 'productedit.php';</script>";
    }
}

// Handle product deletion
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $con->prepare("SELECT img FROM product_images WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $image_filename = $data['img'];

    $deleteStmt = $con->prepare("DELETE FROM product_images WHERE id=?");
    $deleteStmt->bind_param("i", $id);
    if ($deleteStmt->execute()) {
        if (!empty($image_filename) && file_exists("uploads/productimages/" . $image_filename)) {
            unlink("uploads/productimages/" . $image_filename);
        }
        $deleteSubImagesStmt = $con->prepare("DELETE FROM sub_images WHERE product_id=?");
        $deleteSubImagesStmt->bind_param("i", $id);
        $deleteSubImagesStmt->execute();

        echo "<script>alert('Product and images deleted successfully!'); window.location.href = 'productedit.php';</script>";
    } else {
        echo "<script>alert('Failed to delete product.'); window.location.href = 'productedit.php';</script>";
    }
}
?>

<!-- Add, Update, Delete HTML/Modal Code here -->


<!-- Add, Update, Delete HTML/Modal Code here -->
<style>
.container {
    display: flex;
    flex-wrap: wrap; /* Wrap to the next line if needed */
    gap: 10px; /* Add space between the tables */
    justify-content: space-between; /* Distribute tables evenly */
}


.text-edit-table {
    flex: 1;
    min-width: 300px; /* Ensure a minimum width for smaller screens */
    max-width: 200%; /* Limit the max width */
}

@media screen and (max-width: 992px) {
    .text-edit-table {
        max-width: 100%; /* Adjust max width for medium screens */
    }
}

@media screen and (max-width: 768px) {
    .text-edit-table {
        max-width: 100%; /* Make tables full-width on small screens */
    }
}

.table {
    width: 100%; /* Ensure the table occupies the full width of its parent container */
}

</style>

<main id="main" class="main">
<div class="pagetitle">
        <h1>Our Products</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Our Products</li>
            </ol>
        </nav>

        <div class="text-edit-table">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title">Edit Product</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="productTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Discounted Price</th>
                            <th>Sub-Images</th>
                            <th colspan="2" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT product_images.*, categories.name AS category_name 
                                  FROM product_images
                                  LEFT JOIN categories ON product_images.category_id = categories.id 
                                  ORDER BY product_images.id DESC";
                        $result = mysqli_query($con, $query);
                        if (!$result) {
                            echo "<tr><td colspan='10' class='text-center'>Error fetching data.</td></tr>";
                        } else {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td>
                                <?php if (!empty($row['img'])) { ?>
                                    <img src="uploads/productimages/<?= htmlspecialchars($row['img']); ?>" width="100px" alt="<?= htmlspecialchars($row['title']); ?>">
                                <?php } else { ?>
                                    No Image
                                <?php } ?>
                            </td>
                            <td><?= htmlspecialchars($row['title']); ?></td>
                            <td><?= htmlspecialchars($row['category_name']); ?></td>
                            <td><?= htmlspecialchars($row['description']); ?></td>
                            <td><?= htmlspecialchars($row['costing']); ?></td>
                            <td><?= htmlspecialchars($row['discounted_price']); ?></td>
                            <td>
                                <?php
                                $subImageQuery = "SELECT * FROM sub_images WHERE product_id = " . (int)$row['id'];
                                $subImageResult = mysqli_query($con, $subImageQuery);
                                if ($subImageResult) {
                                    while ($subImage = mysqli_fetch_assoc($subImageResult)) {
                                        echo "<img src='uploads/sub_images/" . htmlspecialchars($subImage['sub_image']) . "' width='50px' alt='Sub Image'>";
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a href="deleteproduct.php?id=<?= htmlspecialchars($row['id']); ?>" class="text-danger" onclick="return confirm('Are you sure?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#updateProductImageModal-<?= htmlspecialchars($row['id']); ?>" class="text-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Update Product Image Modal -->
                        <div class="modal fade" id="updateProductImageModal-<?= htmlspecialchars($row['id']); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="productedit.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Product: <?= htmlspecialchars($row['title']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">

                                            <label for="title">Title</label>
                                            <input type="text" name="title" value="<?= htmlspecialchars($row['title']); ?>" class="form-control" required>

                                            <label for="category_id">Category</label>
                                            <select name="category_id" class="form-control" required>
                                                <option value="<?= htmlspecialchars($row['category_id']); ?>"><?= htmlspecialchars($row['category_name']); ?></option>
                                                <?php
                                                $categoryQuery = "SELECT * FROM categories";
                                                $categoryResult = mysqli_query($con, $categoryQuery);
                                                while ($category = mysqli_fetch_assoc($categoryResult)) {
                                                    echo "<option value='" . htmlspecialchars($category['id']) . "'>" . htmlspecialchars($category['name']) . "</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for="description">Description</label>
                                            <textarea name="description" class="form-control" required><?= htmlspecialchars($row['description']); ?></textarea>

                                            <label for="costing">Cost</label>
                                            <input type="number" name="costing" value="<?= htmlspecialchars($row['costing']); ?>" class="form-control" required>

                                            <label for="discounted_price">Discounted Price</label>
                                            <input type="number" name="discounted_price" value="<?= htmlspecialchars($row['discounted_price']); ?>" class="form-control" required>

                                            <label for="image">Product Image</label>
                                            <input type="file" name="image" class="form-control">

                                            <label for="sub_images[]">Sub-images (Max 5)</label>
                                            <input type="file" name="sub_images[]" class="form-control" multiple>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="update_product" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                                $count++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <a href="#" data-bs-toggle="modal" data-bs-target="#addProductImageModal" class="btn btn-primary">Add Product</a>

            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductImageModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="productedit.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label for="title" class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="title" required>

                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $categoryQuery = "SELECT * FROM categories";
                                    $categoryResult = mysqli_query($con, $categoryQuery);
                                    while ($category = mysqli_fetch_assoc($categoryResult)) {
                                        echo "<option value='" . htmlspecialchars($category['id']) . "'>" . htmlspecialchars($category['name']) . "</option>";
                                    }
                                    ?>
                                </select>

                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>

                                <label for="costing" class="form-label">Cost</label>
                                <input type="number" class="form-control" name="costing" required>

                                <label for="discounted_price" class="form-label">Discounted Price</label>
                                <input type="number" class="form-control" name="discounted_price">

                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" name="image" required>

                                <label for="sub_images[]" class="form-label">Upload Sub-Images (Max 5)</label>
                                <input type="file" class="form-control" name="sub_images[]" multiple>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>


</main>

<?php include('footer.php') ?>

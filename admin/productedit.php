<?php
include('header.php');
include('sidebar.php');
?>
<?php
$categoryQuery = mysqli_query($con, "SELECT * FROM categories ORDER BY name ASC");

if (mysqli_num_rows($categoryQuery) > 0) {
    while ($category = mysqli_fetch_assoc($categoryQuery)) {
        // echo "<option value='" . htmlspecialchars($category['id']) . "'>" . htmlspecialchars($category['name']) . "</option>";
    }
} else {
    echo "<option>No categories found</option>";
}

// Handle category submission
if (isset($_POST['submit_category'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $stmt = $con->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        //header("Location: productedit.php");
    }
}

// Handle category update
if (isset($_POST['update_category'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $stmt = $con->prepare("UPDATE categories SET name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);
    if ($stmt->execute()) {
        //header("Location: productedit.php");
    }
}

// Handle category deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete_category') {
    $categoryId = $_GET['id'];

    // Check if there are any products under this category
    $productCheckQuery = mysqli_query($con, "SELECT COUNT(*) AS product_count FROM product_images WHERE category_id = $categoryId");
    $productCountData = mysqli_fetch_assoc($productCheckQuery);

    if ($productCountData['product_count'] > 0) {
        // If products exist, redirect back with a message to delete products first
        $_SESSION['category_delete_warning'] = "This category has {$productCountData['product_count']} products. Please delete the products first.";
        header("Location: productedit.php");
        exit;
    } else {
        // If no products, proceed with the category deletion
        mysqli_query($con, "DELETE FROM categories WHERE id = $categoryId");
        echo "<script>alert('category deleted successfully!'); window.location.href = 'productedit.php';</script>";
        exit;
    }
}

// Handle product submission

// Handle deletion of a single image
if (isset($_GET['id']) && isset($_GET['delete_main_image']) && $_GET['delete_main_image'] == 'true') {
    $id = (int)$_GET['id'];

    // Get the image filename from the database
    $stmt = $con->prepare("SELECT img FROM product_images WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $image_filename = $data['img'];

    // Delete the image from the database
    $deleteStmt = $con->prepare("DELETE FROM product_images WHERE id=?");
    $deleteStmt->bind_param("i", $id);
    if ($deleteStmt->execute()) {
        // If there is an image, delete it from the server
        if (!empty($image_filename) && file_exists("uploads/productimages/" . $image_filename)) {
            unlink("uploads/productimages/" . $image_filename);
        }
        echo "<script>alert('Image deleted successfully!'); window.location.href = 'productedit.php';</script>";
    } else {
        echo "<script>alert('Failed to delete image.'); window.location.href = 'productedit.php';</script>";
    }
}


?>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    // Debugging: Check if the form is being submitted


    // Get form data
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $costing = $_POST['costing'];
    $discounted_price = $_POST['discounted_price'];

    // Main product image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '-' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/productimages/' . $imageName);
        // Update the product image in the database
        $updateProductQuery = "UPDATE product_images SET img = '$imageName' WHERE id = '$id'";
        if (mysqli_query($con, $updateProductQuery)) {
            // echo "Product image updated successfully.";
        } else {
            // echo "Error updating product image: " . mysqli_error($con);
        }
    }

    // Sub-image upload
    if (isset($_FILES['sub_images']) && $_FILES['sub_images']['error'][0] == 0) {
        $subImages = $_FILES['sub_images'];
        $fileCount = count($subImages['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = time() . '-' . $subImages['name'][$i];
            move_uploaded_file($subImages['tmp_name'][$i], 'uploads/product_sub_images/' . $fileName);

            // Insert sub-image into the database
            $insertSubImageQuery = "INSERT INTO product_sub_images (product_id, img) VALUES ('$id', '$fileName')";
            if (mysqli_query($con, $insertSubImageQuery)) {
                // echo "Sub-image added successfully.";
            } else {
                // echo "Error adding sub-image: " . mysqli_error($con);
            }
        }
    }

    // Update product details in the database
    $updateProductDetailsQuery = "UPDATE product_images SET title = '$title', category_id = '$category_id', description = '$description', costing = '$costing', discounted_price = '$discounted_price' WHERE id = '$id'";
    if (mysqli_query($con, $updateProductDetailsQuery)) {
        // echo "Product details updated successfully.";
    } else {
        // echo "Error updating product details: " . mysqli_error($con);
    }
}
?>

<?php
// Enable error reporting for debugging
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    // The button with the name 'add_product' was clicked
    // Your form handling code goes here

    // Get form data
    $title = $_POST['title'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $description = $_POST['description'] ?? '';
    $costing = $_POST['costing'] ?? '';
    $discounted_price = $_POST['discounted_price'] ?? '';
    $imageName = '';

    // Handle main product image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '-' . basename($_FILES['image']['name']);
        $imagePath = 'uploads/productimages/' . $imageName;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Error uploading main product image.");
        }
    }

    // Insert product data into the database
    $insertProductQuery = "INSERT INTO product_images (title, category_id, description, costing, discounted_price, img) 
                           VALUES ('$title', '$category_id', '$description', '$costing', '$discounted_price', '$imageName')";
    if (mysqli_query($con, $insertProductQuery)) {
        $productId = mysqli_insert_id($con); // Get the inserted product ID

        // Handle sub-image uploads
        if (isset($_FILES['sub_images'])) {
            foreach ($_FILES['sub_images']['tmp_name'] as $key => $tmp_name) {
                if (!empty($tmp_name) && $_FILES['sub_images']['error'][$key] == 0) {
                    $subImageName = time() . '-' . basename($_FILES['sub_images']['name'][$key]);
                    $subImagePath = 'uploads/product_sub_images/' . $subImageName;

                    // Move uploaded file
                    if (move_uploaded_file($tmp_name, $subImagePath)) {
                        // Insert sub-image data into the database
                        $insertSubImageQuery = "INSERT INTO product_sub_images (product_id, image) 
                                                VALUES ('$productId', '$subImageName')";
                        if (!mysqli_query($con, $insertSubImageQuery)) {
                            echo "DB Error: " . mysqli_error($con) . "<br>";
                        }
                    } else {
                        echo "Failed to move sub-image: $subImageName<br>";
                    }
                } else {
                    echo "Error uploading sub-image: " . $_FILES['sub_images']['name'][$key] . "<br>";
                }
            }
        }

        // Redirect to avoid form resubmission
        echo "<script>alert('Product added successfully!'); window.location.href = 'productedit.php';</script>";
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>



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

    </div><!-- End Page Title -->

    <div class="text-edit-table">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title">Manage Categories</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="categoriesTable">
                    <thead>
                        <tr draggable="false">
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col" class="text-center" colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
// Fetch all records from the categories table
$categoryQuery = mysqli_query($con, "SELECT * FROM categories ORDER BY position ASC");
$c = 1;
while ($data = mysqli_fetch_array($categoryQuery)) {
?>
    <tr data-id="<?= $data['id'] ?>" class="draggable">
        
        <th scope="row"><?= $c; ?></th>
        <td><?= $data['name']; ?></td>
        <td>
            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal-<?= $data['id'] ?>" title="Delete Category" class="text-center text-danger fs-4">
                <i class="bi bi-trash"></i>
            </a>
        </td>
        <td>
            <a href="#" data-bs-toggle="modal" data-bs-target="#updateCategoryModal-<?= $data['id'] ?>" title="Update Category" class="text-center text-warning fs-4">
                <i class="bi bi-pencil"></i>
            </a>
        </td>
        <td>
            <button class="btn btn-outline-secondary btn-sm drag-handle" title="Drag to reorder">
                <i class="bi bi-arrows-move"></i>
            </button>
        </td>
    </tr>
                            <!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // Check if there are any products under this category
                $productCheckQuery = mysqli_query($con, "SELECT COUNT(*) AS product_count FROM product_images WHERE category_id = {$data['id']}");
                $productCountData = mysqli_fetch_assoc($productCheckQuery);

                if ($productCountData['product_count'] > 0) {
                    // Show a warning if there are products under this category
                    echo "This category has {$productCountData['product_count']} products. Please delete the products before deleting the category.";
                } else {
                    // Show a confirmation to delete the category
                    echo "Are you sure you want to delete this category?";
                }
                ?>
            </div>
            <div class="modal-footer">
                <?php if ($productCountData['product_count'] == 0) { ?>
                    <a href="productedit.php?id=<?= $data['id'] ?>&action=delete_category" class="btn btn-danger">Delete Category</a>
                <?php } ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                            <!-- Update Category Modal -->
                            <div class="modal fade" id="updateCategoryModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="updateCategoryModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateCategoryModalLabel">Update Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="productedit.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Category Name</label>
                                                    <input type="text" class="form-control" name="name" value="<?= $data['name'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_category" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Update Category Modal -->

                        <?php
                            $c++;
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add Category Button -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#addCategoryModal" title="Add Category" class="btn btn-primary py-2 px-4 rounded-pill">Add Category</a>

                <!-- Add Category Modal -->
                <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="productedit.php" method="POST">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Category Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="submit_category" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Add Category Modal -->

            </div>
        </div>
    </div>
</div>


<script>
   const table = document.getElementById("categoriesTable");
let draggedRow = null;

table.addEventListener("mousedown", (e) => {
    // Enable dragging only when clicking the drag handle
    const dragHandle = e.target.closest(".drag-handle");
    if (dragHandle) {
        draggedRow = dragHandle.closest("tr");
        draggedRow.setAttribute("draggable", "true");
    }
});

table.addEventListener("dragstart", (e) => {
    if (draggedRow !== e.target) {
        e.preventDefault();
    }
});

table.addEventListener("dragover", (e) => {
    e.preventDefault();
    const targetRow = e.target.closest("tr");
    if (targetRow && targetRow !== draggedRow && !targetRow.closest("thead")) {
        const rect = targetRow.getBoundingClientRect();
        const next = e.clientY > rect.top + rect.height / 2;
        targetRow.parentNode.insertBefore(draggedRow, next ? targetRow.nextSibling : targetRow);
    }
});

table.addEventListener("dragend", () => {
    if (draggedRow) {
        draggedRow.setAttribute("draggable", "false");
        draggedRow = null;

        // Update the order in the database
        const ids = Array.from(table.querySelectorAll("tbody tr")).map(row => row.dataset.id);
        fetch("updateCategoryOrder.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ order: ids }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Category order updated successfully!");
            } else {
                alert("Failed to update category order.");
            }
        });
    }
});
</script>

<div class="text-edit-table">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title">Edit Product</h5>
            <!-- Table for displaying products -->
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="productTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Multiple Image</th>
                            <th scope="col">
                                Product Name 
                                <button class="btn btn-link dropdown-toggle p-0 ms-2" id="searchDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Search">
                                    <i class="bi bi-search"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="searchDropdown">
                                    <li>
                                        <div class="p-2">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Search Product" aria-label="Search Product">
                                        </div>
                                    </li>
                                </ul>
                            </th>
                            <th scope="col">
                                Category
                                <button class="btn btn-link dropdown-toggle p-0 ms-2" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Filter by Category">
                                    <i class="bi bi-filter"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                                    <li>
                                        <div class="p-2">
                                            <select id="categoryDropdownFilter" class="form-select" aria-label="Filter by Category">
                                                <option value="">All Categories</option>
                                                <?php
                                                // Fetch categories from the categories table for the dropdown
                                                $categoryQuery = mysqli_query($con, "SELECT * FROM categories ORDER BY name ASC");
                                                while ($category = mysqli_fetch_assoc($categoryQuery)) {
                                                    echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Discounted Price</th>
                            <th colspan="2" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch products along with category names and sub-images
                        $query = "SELECT product_images.*, categories.id AS category_id, categories.name AS category_name 
                                  FROM product_images
                                  LEFT JOIN categories ON product_images.category_id = categories.id 
                                  ORDER BY product_images.id DESC";
                        $result = mysqli_query($con, $query);
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr data-category-id="<?= $row['category_id']; ?>">
                            <td><?= $count; ?></td>
                            <td>
                                <!-- Main Image -->
                                <?php if (!empty($row['img'])) { ?>
                                    <img src="uploads/productimages/<?= $row['img'] ?>" width="100px" alt="<?= $row['title'] ?>">
                                <?php } else { ?>
                                    No Image
                                <?php } ?>
                            </td>
                            <td>
        <!-- Sub-Images Column -->
        <div class="d-flex flex-wrap">
            <?php
            // Fetch sub-images for the current product
            $subImagesQuery = "SELECT * FROM product_sub_images WHERE product_id = " . $row['id'];
            $subImagesResult = mysqli_query($con, $subImagesQuery);
            while ($subImage = mysqli_fetch_assoc($subImagesResult)) {
            ?>
                <div class="ms-2 position-relative">
                    <img src="uploads/product_sub_images/<?= $subImage['image']; ?>" width="50px" alt="<?= $subImage['title']; ?>">

                    <!-- Delete Icon for Sub-image -->
                   
                </div>
            <?php } ?>
        </div>
    </td>
                            <td><?= $row['title']; ?></td>
                            <td><?= $row['category_name']; ?></td>
                            <td><?= $row['description']; ?></td>
                            <td><?= $row['costing']; ?></td>
                            <td><?= $row['discounted_price']; ?></td>
                            <td><a href="productedit.php?id=<?= $row['id'] ?>&delete_main_image=true" class="text-danger"><i class="bi bi-trash"></i></a></td>
                            <td><a href="#" data-bs-toggle="modal" data-bs-target="#updateProductImageModal-<?= $row['id'] ?>" class="text-warning"><i class="bi bi-pencil"></i></a></td>
                        </tr>

                        <!-- Update Product Image Modal -->
                        <div class="modal fade" id="updateProductImageModal-<?= $row['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateProductForm-<?= $row['id'] ?>" action="productedit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="title" value="<?= $row['title'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php
                            $categoryQuery = mysqli_query($con, "SELECT * FROM categories ORDER BY name ASC");
                            while ($category = mysqli_fetch_assoc($categoryQuery)) {
                                $selected = ($category['id'] == $row['category_id']) ? 'selected' : '';
                                echo "<option value='" . $category['id'] . "' $selected>" . $category['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required><?= $row['description'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="costing" class="form-label">Cost</label>
                        <input type="number" class="form-control" name="costing" step="0.01" value="<?= $row['costing'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="discounted_price" class="form-label">Discounted Price</label>
                        <input type="number" class="form-control" name="discounted_price" step="0.01" value="<?= $row['discounted_price'] ?>">
                    </div>

                    <!-- Main Image Upload -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>

                    <!-- Sub-Image Upload Section -->
                    <div class="subimage">
                        <h6>Multiple Images For <?= $row['title']; ?></h6>
                        <div class="row" id="subImageContainer">
                            <?php
                            // Fetch sub-images for the current product
                            $subImagesQuery = "SELECT * FROM product_sub_images WHERE product_id = " . $row['id'];
                            $subImagesResult = mysqli_query($con, $subImagesQuery);
                            while ($subImage = mysqli_fetch_assoc($subImagesResult)) {
                            ?>
                            <div class="col-md-3">
                                <img src="uploads/product_sub_images/<?= $subImage['image']; ?>" width="100px" alt="<?= $subImage['title']; ?>">
                                <a href="delete_sub_image.php?id=<?= $subImage['id'] ?>&product_id=<?= $row['id'] ?>" class="text-danger"><i class="bi bi-trash"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                        <label for="sub_images" class="form-label">Upload Sub-Images</label>
                        <input type="file" class="form-control" name="sub_images[]" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_product">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>



                        <?php $count++; } ?>
                    </tbody>
                </table>

                <!-- Add Product Image Button -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#addProductImageModal" class="btn btn-primary">Add Product</a>

                <!-- Modal for Adding New Product -->
                <div class="modal fade" id="addProductImageModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="addProductForm" action="productedit.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <!-- Product Form Fields -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            // Fetch categories from the database
                                            $categoryQuery = mysqli_query($con, "SELECT * FROM categories ORDER BY name ASC");
                                            while ($category = mysqli_fetch_assoc($categoryQuery)) {
                                                echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="costing" class="form-label">Cost</label>
                                        <input type="number" class="form-control" name="costing" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="discounted_price" class="form-label">Discounted Price</label>
                                        <input type="number" class="form-control" name="discounted_price" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Product Image</label>
                                        <input type="file" class="form-control" name="image" required>
                                    </div>
                                    <!-- Sub-Image Upload Section -->
                                    <div class="subimage">
                                        <label for="sub_images" class="form-label">Upload Sub-Images</label>
                                        <input type="file" class="form-control" name="sub_images[]" multiple>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- PHP Handling -->
<?php
// Handling add sub-image
if (isset($_POST['add_sub_image'])) {
    $product_id = $_POST['product_id'];
    $sub_image_title = $_POST['sub_image_title'];
    $sub_image = $_FILES['sub_image']['name'];
    $target = "uploads/product_sub_images/" . basename($sub_image);
    
    if (move_uploaded_file($_FILES['sub_image']['tmp_name'], $target)) {
        $query = "INSERT INTO product_sub_images (product_id, image, title) VALUES ('$product_id', '$sub_image', '$sub_image_title')";
        mysqli_query($con, $query);
        echo "<script>alert('added successfully!'); window.location.href = 'productedit.php';</script>";
    }
}

// Handling delete sub-image

?>

<!-- JavaScript for Filtering Products -->
<script>
   document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    document.querySelectorAll('#productTable tbody tr').forEach(function(row) {
        const productName = row.cells[2].innerText.toLowerCase();
        row.style.display = productName.includes(searchText) ? '' : 'none';
    });
});

document.getElementById('categoryDropdownFilter').addEventListener('change', function() {
    const selectedCategory = this.value;
    document.querySelectorAll('#productTable tbody tr').forEach(function(row) {
        const categoryId = row.getAttribute('data-category-id');
        row.style.display = selectedCategory === '' || selectedCategory === categoryId ? '' : 'none';
    });
});

</script>
</main>

<?php include('footer.php') ?>

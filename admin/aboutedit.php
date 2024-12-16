<?php include('../config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdownList = document.querySelector('.dropdown-list');
    const selectElement = document.querySelector('#iconDropdown');
    
    dropdownBtn.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default action (e.g., form submission)
        dropdownList.style.display = dropdownList.style.display === 'block' ? 'none' : 'block';
    });

    document.querySelectorAll('.dropdown-list li').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default action for list items (if any)

            const iconClass = this.getAttribute('data-value');
            const iconLabel = this.innerHTML;

            // Update the button to show the selected item
            dropdownBtn.innerHTML = iconLabel;

            // Update the hidden select element with the selected value
            selectElement.value = iconClass;

            // Hide the dropdown
            dropdownList.style.display = 'none';
        });
    });

    // Close the dropdown if clicked outside
    document.addEventListener('click', function (e) {
        if (!dropdownBtn.contains(e.target) && !dropdownList.contains(e.target)) {
            dropdownList.style.display = 'none';
        }
    });
});


    </script> -->
    <script>
    document.querySelectorAll('form button[name="delete_service"]').forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm("Are you sure you want to delete this service?")) {
                e.preventDefault();
            }
        });
    });
</script>

</head>
<!-- <style>
.custom-dropdown {
    position: relative;
    display: inline-block;
    width: 100%;
}

.dropdown-btn {
    padding: 10px;
    width: 100%;
    background-color: #fff;
    border: 1px solid #ccc;
    cursor: pointer;
    text-align: left;
}

.dropdown-list {
    display: none;
    position: absolute;
    width: 100%;
    background-color: #fff;
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    z-index: 1;
    margin-top: 5px;
}

.dropdown-list li {
    padding: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.dropdown-list li:hover {
    background-color: #f0f0f0;
}

.dropdown-list li i {
    margin-right: 10px;
}
</style> -->


<?php
include('header.php');
include('sidebar.php');
?>
<?php
if (isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    $imageType = $_FILES['image']['type'];

    $stmt = $con->prepare("INSERT INTO serviceblock (image, image_type, title, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("bsss", $image, $imageType, $title, $description);
    $stmt->send_long_data(0, $image); // Binary data
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid resubmission on refresh
    echo "<script>alert('added successfully'); window.location.href = 'aboutedit.php';</script>";
    exit();
}

if (isset($_POST['update_service'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($_FILES['image']['tmp_name'])) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];

        $stmt = $con->prepare("UPDATE serviceblock SET image = ?, image_type = ?, title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("bsssi", $image, $imageType, $title, $description, $id);
        $stmt->send_long_data(0, $image);
    } else {
        $stmt = $con->prepare("UPDATE serviceblock SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
    }
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid resubmission on refresh
    echo "<script>alert('Udated successfully'); window.location.href = 'aboutedit.php';</script>";
    exit();
}



if (isset($_GET['action']) && $_GET['action'] === 'delete_service') {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']); // Sanitize the ID

        // Prepare the SQL statement
        $query = "DELETE FROM serviceblock WHERE id = ?";
        $stmt = $con->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // Redirect with success message
                echo "<script>alert('Deleted successfully'); window.location.href = 'aboutedit.php';</script>";
            } else {
                // Error handling
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }

        $con->close();
    } else {
        echo "Invalid or missing ID.";
    }
}

?>
<?php
if (isset($_POST['aboutupdate_text'])) {
    $id = $_POST['id'];
    $hdesc = $_POST['hdesc'];
    $htdesc = $_POST['htdesc'];

    // Prepare the SQL query
    // Update the text and heading in the database
    $update_query = "UPDATE aboutdescription SET hdesc='$hdesc', htdesc='$htdesc' WHERE id='$id'";
    if (mysqli_query($con, $update_query)) {
        echo "Text updated successfully.";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<?php
if (isset($_POST['update_image'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    
    // Generate a unique image name to avoid overwriting
    $image_name = time() . '_' . $image;
    $target_dir = "uploads/aboutimages/";
    $target_file = $target_dir . $image_name;

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($image_tmp, $target_file)) {
        // Update the image info in the database
        $update = mysqli_query($con, "UPDATE `aboutimage` SET img='$image_name' WHERE id='$id'");
        if ($update) {
            echo "<script>alert('Image updated successfully!'); window.location.href = 'aboutedit.php';</script>";
        } else {
            echo "<script>alert('Failed to update image.'); window.location.href = 'aboutedit.php';</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.'); window.location.href = 'aboutedit.php';</script>";
    }
}
?>
<?php
// Handle form submission for adding images
if (isset($_POST['submit'])) {
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    
    // Generate a unique image name to avoid overwriting
    $image_name = time() . '_' . $image;
    $target_dir = "uploads/brandimages/";
    $target_file = $target_dir . $image_name;

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($image_tmp, $target_file)) {
        // Save the image info in the database
        $insert = mysqli_query($con, "INSERT INTO `brandimage` (img) VALUES ('$image_name')");
        if ($insert) {
            // echo "<script>alert('Image added successfully!'); window.location.href = 'aboutedit.php';</script>";
        } else {
            echo "<script>alert('Failed to add image.'); window.location.href = 'aboutedit.php';</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.'); window.location.href = 'aboutedit.php';</script>";
    }
}

if (isset($_POST['update_brand_image'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    
    // Generate a unique image name to avoid overwriting
    $image_name = time() . '_' . $image;
    $target_dir = "uploads/brandimages/";
    $target_file = $target_dir . $image_name;

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($image_tmp, $target_file)) {
        // Update the image info in the database
        $update = mysqli_query($con, "UPDATE `brandimage` SET img='$image_name' WHERE id='$id'");
        if ($update) {
            echo "<script>alert('Image updated successfully!'); window.location.href = 'aboutedit.php';</script>";
        } else {
            echo "<script>alert('Failed to update image.'); window.location.href = 'aboutedit.php';</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.'); window.location.href = 'aboutedit.php';</script>";
    }
}


// Handle deletion of a single image
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $image = mysqli_real_escape_string($con, $_GET['image']);

    // Get the image filename from the database
    $query = mysqli_query($con, "SELECT img FROM `brandimage` WHERE `id` = '$id'");
    $data = mysqli_fetch_array($query);
    $image_filename = $data['img'];

    // Delete the image from the database
    $delete = mysqli_query($con, "DELETE FROM `brandimage` WHERE `id` = '$id'");
    if ($delete) {
        // If there is an image, delete it from the server
        if (!empty($image_filename) && file_exists("uploads/brandimages/" . $image_filename)) {
            unlink("uploads/brandimages/" . $image_filename);
        }
        echo "<script>alert('Image deleted successfully!'); window.location.href = 'aboutedit.php';</script>";
    } else {
        echo "<script>alert('Failed to delete image.'); window.location.href = 'aboutedit.php';</script>";
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
        <h1>About Us</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Our Services</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    
        <!-- Text Editing Table -->

        <div class="text-edit-table">
    <!-- Display Images -->
     
    <?php
$selection = mysqli_query($con, "SELECT * FROM `serviceblock`");
$counter = 0;

while ($data = mysqli_fetch_array($selection)) {
    $counter++;
?>
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title">Service <?= $counter ?></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Display Image -->
                            <td>
                                <img src="data:<?= $data['image_type'] ?>;base64,<?= base64_encode($data['image']) ?>" 
                                     alt="Service Image" style="width: 100px; height: auto;">
                            </td>
                            <td><?= $data['title'] ?></td>
                            <td><?= $data['description'] ?></td>
                            <td class="text-center">
                                <a href="aboutedit.php?action=delete_service&id=<?= $data['id'] ?>" 
                                   title="Delete Service" class="text-danger fs-4"
                                   onclick="return confirm('Are you sure you want to delete this service?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" 
                                   data-bs-target="#editServiceModal-<?= $data['id'] ?>" 
                                   title="Edit Service" class="text-center text-warning fs-4">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Edit Service Modal -->
                        <div class="modal fade" id="editServiceModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="editServiceModalLabel-<?= $data['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editServiceModalLabel-<?= $data['id'] ?>">Edit Service</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="aboutedit.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                            <div class="mb-3">
                                                <label for="title-<?= $data['id'] ?>" class="form-label">Title</label>
                                                <input type="text" class="form-control" name="title" id="title-<?= $data['id'] ?>" value="<?= $data['title'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description-<?= $data['id'] ?>" class="form-label">Description</label>
                                                <textarea class="form-control" name="description" id="description-<?= $data['id'] ?>" rows="3" required><?= $data['description'] ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image-<?= $data['id'] ?>" class="form-label">Image (optional)</label>
                                                <input type="file" class="form-control" name="image" id="image-<?= $data['id'] ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="update_service" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Edit Service Modal -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="aboutedit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="image" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<a href="#" data-bs-toggle="modal" data-bs-target="#addServiceModal" title="Add New Service" class="btn btn-primary py-2 px-4 rounded-pill">Add New Service</a>
<td class="text-center">
</td>


<br>
<br>    
<nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">About Us</li>
            </ol>
        </nav>
<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12"> <!-- Changed to col-lg-12 for full width -->
            <div class="row">

                <!-- Sales Card -->
                <div class="col-lg-6 col-md-6"> <!-- Changed to col-lg-6 to increase width -->
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Edit About us </h5>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Heading</th>
                                        <th scope="col">Text</th>
                                        <th scope="col" class="text-center" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $selection = mysqli_query($con, "SELECT * FROM aboutdescription where id=1");
                                    $c = 1;
                                    while ($data = mysqli_fetch_array($selection)) {
                                    ?>
                                        <tr>
                                            <td><?= $data['hdesc'] ?></td>
                                            <td><?= $data['htdesc'] ?></td>
                                            <td>
                                                <!-- Trigger Modal for Editing -->
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#aboutedit-<?= $data['id'] ?>" title="Edit Text" class="text-center text-warning fs-4">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Edit Text Modal -->
                                        <div class="modal fade" id="aboutedit-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="editTextModalLabel-<?= $data['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editTextModalLabel-<?= $data['id'] ?>">Edit Header Text</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="aboutedit.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                            <div class="mb-3">
                                                                <label for="hdesc-<?= $data['id'] ?>" class="form-label">Heading</label>
                                                                <input type="text" class="form-control" name="hdesc" id="hdesc-<?= $data['id'] ?>" value="<?= $data['hdesc'] ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="htdesc-<?= $data['id'] ?>" class="form-label">Text</label>
                                                                <textarea class="form-control" name="htdesc" id="htdesc-<?= $data['id'] ?>" rows="3" required><?= $data['htdesc'] ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="aboutupdate_text" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edit Text Modal -->

                                    <?php
                                        $c++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- End Sales Card -->

                <!-- Revenue Card -->
                <div class="col-lg-6 col-md-6"> <!-- Changed to col-lg-6 to increase width -->
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Edit About us Image</h5>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetching data from the new aboutimage table
                                    $selection = mysqli_query($con, "SELECT * FROM `aboutimage` ORDER BY `id` DESC");
                                    $c = 1;
                                    while ($data = mysqli_fetch_array($selection)) {
                                    ?>
                                        <tr>
                                            <th scope="row"><?= $c; ?></th>
                                            <td>
                                                <?php if (!empty($data['img'])) { ?>
                                                    <img src="uploads/aboutimages/<?= $data['img'] ?>" width="100px" alt="Image">
                                                <?php } else { ?>
                                                    No image
                                                <?php } ?>
                                            </td>
                                            <td><a href="#" data-bs-toggle="modal" data-bs-target="#updateImageModal-<?= $data['id'] ?>" title="Update Image" class="text-center text-warning fs-4"><i class="bi bi-pencil"></i></a></td>
                                        </tr>

                                        <!-- Update Image Modal -->
                                        <div class="modal fade" id="updateImageModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateImageModalLabel">Update Image</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="aboutedit.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                            <div class="mb-3">
                                                                <label for="image" class="form-label">Upload New Image</label>
                                                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" name="update_image" class="btn btn-primary">Update Image</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Update Image Modal -->

                                    <?php
                                        $c++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- End Revenue Card -->
            </div>
        </div>
    </div>
    
</section>


<nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Our Brands</li>
            </ol>
        </nav>
<div class="text-edit-table">
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title">Edit Brand Partners</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col" class="text-center" colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all records from the brandimage table
                        $selection = mysqli_query($con, "SELECT * FROM `brandimage` ORDER BY `id` DESC");
                        $c = 1;
                        while ($data = mysqli_fetch_array($selection)) {
                        ?>
                            <tr>
                                <th scope="row"><?= $c; ?></th>
                                <td>
                                    <?php if (!empty($data['img'])) { ?>
                                        <img src="uploads/brandimages/<?= $data['img'] ?>" width="100px" alt="Brand Image">
                                    <?php } else { ?>
                                        No image
                                    <?php } ?>
                                </td>
                                <td><a href="aboutedit.php?id=<?= $data['id'] ?>&image=<?= $data['img'] ?>" title="Delete Image" class="text-center text-danger fs-4"><i class="bi bi-trash"></i></a></td>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#updateBrandImageModal-<?= $data['id'] ?>" title="Update Image" class="text-center text-warning fs-4"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>

                            <!-- Update Brand Image Modal -->
                            <div class="modal fade" id="updateBrandImageModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateImageModalLabel">Update Brand</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="aboutedit.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Upload New Image</label>
                                                    <input type="file" class="form-control" name="image" accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_brand_image" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Update Brand Image Modal -->

                        <?php
                            $c++;
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add Brand Image Button -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#addBrandImageModal" title="Add Image" class="btn btn-primary py-2 px-4 rounded-pill">Add Brand Partners</a>

                <!-- Add Brand Image Modal -->
                <div class="modal fade" id="addBrandImageModal" tabindex="-1" aria-labelledby="addBrandImageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addBrandImageModalLabel">Add Brand</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form  method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload New Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Add Brand Image Modal -->

            </div>
        </div>
    </div>
</div>


<?php
// Handle form submission for adding company facts
if (isset($_POST['submit111'])) {
    $icon_class = mysqli_real_escape_string($con, $_POST['icon_class']);
    $fact_value = mysqli_real_escape_string($con, $_POST['fact_value']);
    $fact_label = mysqli_real_escape_string($con, $_POST['fact_label']);
    
    // Save the fact info in the database
    $insert = mysqli_query($con, "INSERT INTO `company_facts` (icon_class, fact_value, fact_label) VALUES ('$icon_class', '$fact_value', '$fact_label')");
    if ($insert) {
        echo "<script>alert('Fact added successfully!'); window.location.href = 'aboutedit.php';</script>";
    } else {
        echo "<script>alert('Failed to add fact.'); window.location.href = 'aboutedit.php';</script>";
    }
}

// Handle updating an existing fact
if (isset($_POST['update_fact'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $icon_class = mysqli_real_escape_string($con, $_POST['icon_class']);
    $fact_value = mysqli_real_escape_string($con, $_POST['fact_value']);
    $fact_label = mysqli_real_escape_string($con, $_POST['fact_label']);
    
    // Update the fact info in the database
    $update = mysqli_query($con, "UPDATE `company_facts` SET  fact_value='$fact_value', fact_label='$fact_label' WHERE id='$id'");
    if ($update) {
        echo "<script>alert('Fact updated successfully!'); window.location.href = 'aboutedit.php';</script>";
    } else {
        echo "<script>alert('Failed to update fact.'); window.location.href = 'aboutedit.php';</script>";
    }
}

// Handle deletion of a single fact
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    
    // Delete the fact from the database
    $delete = mysqli_query($con, "DELETE FROM `company_facts` WHERE `id` = '$id'");
    if ($delete) {
        echo "<script>alert('Fact deleted successfully!'); window.location.href = 'aboutedit.php';</script>";
    } else {
        echo "<script>alert('Failed to delete fact.'); window.location.href = 'aboutedit.php';</script>";
    }
}

?>
<div class="text-edit-table">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
    <div class="card recent-sales">
        <div class="card-body">
            <h5 class="card-title">Edit Facts</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <!-- <th scope="col">Icon</th> -->
                            <th scope="col">Fact Value</th>
                            <th scope="col">Fact Label</th>
                            <th scope="col" class="text-center" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all records from the company_facts table
                        $selection = mysqli_query($con, "SELECT * FROM `company_facts` ORDER BY `id` DESC");
                        $c = 1;
                        while ($data = mysqli_fetch_array($selection)) {
                        ?>
                            <tr>
                                <th scope="row"><?= $c; ?></th>
                                <!-- <td><i class="fa <?= $data['icon_class']; ?>"></i></td>  -->
                                <td><?= $data['fact_value']; ?></td>
                                <td><?= $data['fact_label']; ?></td>
                                <!-- <td><a href="aboutedit.php?id=<?= $data['id'] ?>" title="Delete Fact" class="text-center text-danger fs-4"><i class="bi bi-trash"></i></a></td> -->
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#updateFactModal-<?= $data['id'] ?>" title="Update Fact" class="text-center text-warning fs-4"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>

                            <!-- Update Fact Modal -->
                            <div class="modal fade" id="updateFactModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="updateFactModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateFactModalLabel">Update Fact</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="aboutedit.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                <!-- <div class="mb-3"> -->
                                                    <!-- <label for="icon_class" class="form-label">Icon Class</label> -->
                                                    

                                            <!-- This will be the custom dropdown container -->
                                            <!-- <div class="custom-dropdown">
                                                <button class="dropdown-btn">Select an icon</button>
                                                <ul class="dropdown-list">
                                                    <li data-value="fa fa-briefcase"><i class="fa fa-briefcase"></i> Briefcase</li>
                                                    <li data-value="fa fa-users"><i class="fa fa-users"></i> Users</li>
                                                    <li data-value="fa fa-globe"><i class="fa fa-globe"></i> Globe</li>
                                                    
                                                </ul>
                                            </div> -->


                                                <!-- </div> -->
                                                <div class="mb-3">
                                                    <label for="fact_value" class="form-label">Fact Value</label>
                                                    <input type="number" class="form-control" name="fact_value" value="<?= $data['fact_value'] ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fact_label" class="form-label">Fact Label</label>
                                                    <input type="text" class="form-control" name="fact_label" value="<?= $data['fact_label'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_fact" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Update Fact Modal -->

                        <?php
                            $c++;
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add Fact Button -->
                <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#addFactModal" title="Add Fact" class="btn btn-primary py-2 px-4 rounded-pill">Add Fact</a>

                <!-- Add Fact Modal     
                <div class="modal fade" id="addFactModal" tabindex="-1" aria-labelledby="addFactModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addFactModalLabel">Add New Fact</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="aboutedit.php" method="POST">
                                <div class="modal-body">
                                    <!-- <div class="mb-3">
                                        <label for="icon_class" class="form-label">Icon Class</label>
                                        <select class="form-select" name="icon_class" required>
                                            <option value="fa fa-briefcase"><i class="fa fa-briefcase"></i> Briefcase</option>
                                            <option value="fa fa-users"><i class="fa fa-users"></i> Users</option>
                                            <option value="fa fa-globe"><i class="fa fa-globe"></i> Globe</option>
                                             <!-- Add more FontAwesome icons as options here 
                                        </select>
                                    </div> 
                                    <div class="mb-3">
                                        <label for="fact_value" class="form-label">Fact Value</label>
                                        <input type="number" class="form-control" name="fact_value" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fact_label" class="form-label">Fact Label</label>
                                        <input type="text" class="form-control" name="fact_label" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> -->
                <!-- End Add Fact Modal -->

            </div>
        </div>
    </div>
</div>
</main>


<?php include('footer.php') ?>

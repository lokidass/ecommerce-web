<?php
include('header.php');
include('sidebar.php');

// Function to handle file upload and return file name
function handle_file_upload($file, $target_dir) {
    $image = $file['name'];
    $image_tmp = $file['tmp_name'];

    // Generate a unique image name to avoid overwriting
    $image_name = time() . '_' . $image;
    $target_file = $target_dir . $image_name;

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($image_tmp, $target_file)) {
        return $image_name;
    }
    return false;
}

// Handle Add Logo
if (isset($_POST['submitlogo'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = handle_file_upload($_FILES['image'], 'uploads/logos/');
        if ($image_name) {
            // Insert the image path into the database
            $sql = "INSERT INTO `logo` (img) VALUES (?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('s', $image_name);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('Image uploaded successfully!'); window.location.href = 'headeredit.php';</script>";
        } else {
            echo "<script>alert('Image upload failed.'); window.location.href = 'headeredit.php';</script>";
        }
    }

    // Handle text logo addition
    if (isset($_POST['logo_text']) && !empty($_POST['logo_text'])) {
        $logoText = $_POST['logo_text'];
        $sql = "INSERT INTO `logo` (logo_text) VALUES (?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('s', $logoText);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Text logo added successfully!'); window.location.href = 'headeredit.php';</script>";
    }
}

// Update Logo (Image or Text)
if (isset($_POST['update_logo'])) {
    $id = $_POST['id'];
    $logoText = $_POST['logo_text'] ?? null;
    $imageName = null;

    if (isset($_POST['input_type'])) {
        if ($_POST['input_type'] === 'image' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageName = handle_file_upload($_FILES['image'], 'uploads/logos/');
            if ($imageName) {
                // Update the database with image
                $query = "UPDATE `logo` SET img=?, logo_text=NULL WHERE id=?";
                $stmt = $con->prepare($query);
                $stmt->bind_param('si', $imageName, $id);
                $stmt->execute();
                $stmt->close();
            }
        } elseif ($_POST['input_type'] === 'text' && !empty($logoText)) {
            // Update the database with text
            $query = "UPDATE `logo` SET img=NULL, logo_text=? WHERE id=?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('si', $logoText, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Handle image upload for header
if (isset($_POST['submit'])) {
    $image_name = handle_file_upload($_FILES['image'], 'uploads/headerimages/');
    if ($image_name) {
        $insert = $con->prepare("INSERT INTO `headerimage` (img) VALUES (?)");
        $insert->bind_param('s', $image_name);
        if ($insert->execute()) {
            echo "<script>alert('Image added successfully!'); window.location.href = 'headeredit.php';</script>";
        } else {
            echo "<script>alert('Failed to add image.'); window.location.href = 'headeredit.php';</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.'); window.location.href = 'headeredit.php';</script>";
    }
}

// Handle deletion of a single image
if (isset($_GET['id']) && isset($_GET['image'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $image_filename = mysqli_real_escape_string($con, $_GET['image']);
    $query = mysqli_query($con, "SELECT img FROM `headerimage` WHERE `id` = '$id'");
    $data = mysqli_fetch_array($query);
    $image_filename = $data['img'];

    // Delete the image from the database
    if ($image_filename) {
        $delete = mysqli_query($con, "DELETE FROM `headerimage` WHERE `id` = '$id'");
        if ($delete) {
            // If there is an image, delete it from the server
            if (file_exists("uploads/headerimages/" . $image_filename)) {
                unlink("uploads/headerimages/" . $image_filename);
            }
            echo "<script>alert('Image deleted successfully!'); window.location.href = 'headeredit.php';</script>";
        } else {
            echo "<script>alert('Failed to delete image.'); window.location.href = 'headeredit.php';</script>";
        }
    }
}

// Handle image update for header
if (isset($_POST['update_image'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $image_name = handle_file_upload($_FILES['image'], 'uploads/headerimages/');
    if ($image_name) {
        $update = $con->prepare("UPDATE `headerimage` SET img=? WHERE id=?");
        $update->bind_param('si', $image_name, $id);
        if ($update->execute()) {
            echo "<script>alert('Image updated successfully!'); window.location.href = 'headeredit.php';</script>";
        } else {
            echo "<script>alert('Failed to update image.'); window.location.href = 'headeredit.php';</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.'); window.location.href = 'headeredit.php';</script>";
    }
}

// Handle text update for header
if (isset($_POST['update_text'])) {
    $id = $_POST['id'];
    $hheading = $_POST['hheading'];
    $htext = $_POST['htext'];

    $update_query = "UPDATE headertext SET hheading=?, htext=? WHERE id=?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param('ssi', $hheading, $htext, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Text updated successfully.'); window.location.href = 'headeredit.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'headeredit.php';</script>";
    }
}
?>




<style>
    .breadcrumb-container {
    margin-top: 10px; /* Adjust this value as needed */
    padding-left: 0;
}

.pagetitle h1 {
    margin-bottom: 0;
    padding-bottom: 0;
}

.breadcrumb-container ol.breadcrumb {
    display: flex;
    align-items: center;
    padding: 0;
    background-color: transparent; /* Optional, to make it blend well */
    margin-bottom: 0;
}

/* .pagetitle {
    display: flex;
    flex-wrap: wrap; /* Wrap to the next line if needed */
    gap: 10px; /* Add space between the tables */
    justify-content: space-between; /* Distribute tables evenly */
} */

.text-edit-table {
    flex: 1;
    min-width: 300px; /* Ensure a minimum width for smaller screens */
    max-width: 50%; /* Limit the max width */
}

@media screen and (max-width: 992px) {
    .text-edit-table {
        max-width: 45%; /* Adjust max width for medium screens */
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
        <h1>Header</h1>
        <nav class="breadcrumb-container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Header</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


<div class="pagetitle">
    <div class="text-edit-table">
        <div class="card recent-sales">
            <div class="card-body">
                <h5 class="card-title">Edit Logo Image</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Logo</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $selection = mysqli_query($con, "SELECT * FROM `logo` ORDER BY `id` DESC");
                            $c = 1;
                            while ($data = mysqli_fetch_array($selection)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $c; ?></th>
                                    <td>
                                        <?php if (!empty($data['img'])) { ?>
                                            <img src="uploads/logos/<?= $data['img'] ?>" width="100px" alt="Logo">
                                        <?php } else { ?>
                                            No image
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#updateLogoModal-<?= $data['id'] ?>" title="Update Logo" class="text-center text-warning fs-4"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>

                                <!-- Update Logo Modal -->
                               <!-- Update Logo Modal -->
<!-- Update Logo Modal -->
<div class="modal fade" id="updateLogoModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="updateLogoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateLogoModalLabel">Update Logo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="headeredit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Choose Type:</label>
                        <div>
                            <input type="radio" id="updateLogoImage" name="input_type" value="image" onchange="toggleInput('update', 'image')" checked>
                            <label for="updateLogoImage">Image Logo</label>
                            
                            <input type="radio" id="updateLogoText" name="input_type" value="text" onchange="toggleInput('update', 'text')">
                            <label for="updateLogoText">Text Logo</label>
                        </div>
                    </div>

                    <div id="updateLogoImageInput" class="mb-3">
                        <!-- <label for="image" class="form-label">Upload New Logo (optional)</label> -->
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>

                    <div id="updateLogoTextInput" class="mb-3" style="display:none;">
                        <!-- <label for="logo_text" class="form-label">Logo Text (optional)</label> -->
                        <input type="text" class="form-control" name="logo_text" value="<?= $data['logo_text'] ?? '' ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_logo" class="btn btn-primary">Update Logo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Update Logo Modal -->






                                <!-- End Update Logo Modal -->

                            <?php
                                $c++;
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Add Logo Button -->
                    <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#addLogoModal" title="Add Logo" class="btn btn-primary py-2 px-4 rounded-pill">Add Logo</a> -->
                    
                    <!-- Add Logo Modal -->
                    <!-- Add Logo Modal -->
<!-- Add Logo Modal -->
<div class="modal fade" id="addLogoModal" tabindex="-1" aria-labelledby="addLogoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLogoModalLabel">Add Logo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="headeredit.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Choose Type:</label>
                        <div>
                            <input type="radio" id="addLogoImage" name="input_type" value="image" onchange="toggleInput('add', 'image')" checked>
                            <label for="addLogoImage">Upload New Logo</label>
                            
                            <input type="radio" id="addLogoText" name="input_type" value="text" onchange="toggleInput('add', 'text')">
                            <label for="addLogoText">Logo Text</label>
                        </div>
                    </div>

                    <div id="addLogoImageInput" class="mb-3">
                        <label for="image" class="form-label">Upload New Logo (optional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>

                    <div id="addLogoTextInput" class="mb-3" style="display:none;">
                        <label for="logo_text" class="form-label">Logo Text (optional)</label>
                        <input type="text" class="form-control" name="logo_text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submitlogo" class="btn btn-primary">Add Logo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Add Logo Modal -->
                    <!-- End Add Logo Modal -->
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Text Editing Table 1 -->
        <div class="text-edit-table">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title">Edit Header Text</h5>
                    <div class="table-responsive">
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
                                $selection = mysqli_query($con, "SELECT * FROM headertext where id=1");
                                $c = 1;
                                while ($data = mysqli_fetch_array($selection)) {
                                ?>
                                    <tr>
                                        <td><?= $data['hheading'] ?></td>
                                        <td><?= $data['htext'] ?></td>
                                        <td>
                                            <!-- Trigger Modal for Editing -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editTextModal-<?= $data['id'] ?>" title="Edit Text" class="text-center text-warning fs-4">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Text Modal -->
                                    <div class="modal fade" id="editTextModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="editTextModalLabel-<?= $data['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editTextModalLabel-<?= $data['id'] ?>">Edit Header Text</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="headeredit.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                        <div class="mb-3">
                                                            <label for="hheading-<?= $data['id'] ?>" class="form-label">Heading</label>
                                                            <input type="text" class="form-control" name="hheading" id="hheading-<?= $data['id'] ?>" value="<?= $data['hheading'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="htext-<?= $data['id'] ?>" class="form-label">Text</label>
                                                            <textarea class="form-control" name="htext" id="htext-<?= $data['id'] ?>" rows="3" required><?= $data['htext'] ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="update_text" class="btn btn-primary">Save Changes</button>
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
            </div>
        </div>

        <!-- Text Editing Table 2 -->
        <div class="text-edit-table">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title">Edit Header Image</h5>
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
                                $selection = mysqli_query($con, "SELECT * FROM `headerimage` ORDER BY `id` DESC");
                                $c = 1;
                                while ($data = mysqli_fetch_array($selection)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?= $c; ?></th>
                                        <td>
                                            <?php if (!empty($data['img'])) { ?>
                                                <img src="uploads/headerimages/<?= $data['img'] ?>" width="100px" alt="Image">
                                            <?php } else { ?>
                                                No image
                                            <?php } ?>
                                        </td>
                                        <td><a href="headeredit.php?id=<?= $data['id'] ?>&image=<?= $data['img'] ?>" title="Delete Image" class="text-center text-danger fs-4"><i class="bi bi-trash"></i></a></td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#updateImageModal-<?= $data['id'] ?>" title="Update Image" class="text-center text-warning fs-4"><i class="bi bi-pencil"></i></a>
                                        </td>
                                    </tr>

                                    <!-- Update Image Modal -->
                                    <div class="modal fade" id="updateImageModal-<?= $data['id'] ?>" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateImageModalLabel">Update Image</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="headeredit.php" method="POST" enctype="multipart/form-data">
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
                        <!-- Add Image Modal -->
                      <!-- Trigger Modal and Set ID Using JavaScript -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addimage" class="btn btn-primary py-2 px-4 rounded-pill" onclick="setImageId(<?= $data['id'] ?>)">Add Image</a>

                        <!-- Your Modal Code -->
                        <div class="modal fade" id="addimage" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateImageModalLabel">Add Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="headeredit.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" id="imageId" name="id" value="">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Upload New Image</label>
                                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" class="btn btn-primary">Add Image</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Add Image Modal -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
        // Function to set the ID dynamically
        function setImageId(id) {
        document.getElementById('imageId').value = id;
    }

function toggleInput(modalPrefix, inputType) {
    const imageInput = document.getElementById(`${modalPrefix}LogoImageInput`);
    const textInput = document.getElementById(`${modalPrefix}LogoTextInput`);


    if (inputType === 'image') {
        imageInput.style.display = 'block';
        textInput.style.display = 'none';
    } else {
        imageInput.style.display = 'none';
        textInput.style.display = 'block';
    }
}
</script>

<?php include('footer.php') ?>

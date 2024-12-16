<?php
include('header.php');
include('sidebar.php');

// Handle form submission for adding a project
if (isset($_POST['add_project'])) {
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $description = mysqli_real_escape_string($con, $_POST['description']);

    // Generate a unique image name and upload image
    $image_name = time() . '_' . $image;
    $target_dir = "uploads/projects/";
    $target_file = $target_dir . $image_name;

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($image_tmp, $target_file)) {
        $query = "INSERT INTO project (image, description) 
                  VALUES ('$image_name', '$description')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Project added successfully!'); window.location.href = 'projectedit.php';</script>";
        } else {
            echo "<script>alert('Failed to add project: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.');</script>";
    }
}

// Handle deletion of a project
if (isset($_GET['project_id'])) {
    $id = mysqli_real_escape_string($con, $_GET['project_id']);
    $query = mysqli_query($con, "SELECT image FROM `project` WHERE `id` = '$id'");
    $data = mysqli_fetch_array($query);
    $image_filename = $data['image'];

    $delete = mysqli_query($con, "DELETE FROM `project` WHERE `id` = '$id'");
    if ($delete) {
        if (!empty($image_filename) && file_exists("uploads/projects/" . $image_filename)) {
            unlink("uploads/projects/" . $image_filename);
        }
        echo "<script>alert('Project deleted successfully!'); window.location.href = 'projectedit.php';</script>";
    } else {
        echo "<script>alert('Failed to delete project: " . mysqli_error($con) . "');</script>";
    }
}

// Handle image update
if (isset($_POST['update_project'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $description = mysqli_real_escape_string($con, $_POST['description']);

    $update_query = "UPDATE `project` SET description='$description'";

    if (!empty($image)) {
        $image_name = time() . '_' . $image;
        $target_dir = "uploads/projects/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($image_tmp, $target_file)) {
            $update_query .= ", image='$image_name'";
        }
    }

    $update_query .= " WHERE id='$id'";

    if (mysqli_query($con, $update_query)) {
        echo "<script>alert('Project updated successfully!'); window.location.href = 'projectedit.php';</script>";
    } else {
        echo "<script>alert('Failed to update project: " . mysqli_error($con) . "');</script>";
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Projects</h1>
        <nav class="breadcrumb-container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </nav>
    </div>

    <div class="text-edit-table">
        <div class="card recent-sales">
            <div class="card-body">
                <h5 class="card-title">Recent Updates</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th colspan="2" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $selection = mysqli_query($con, "SELECT * FROM `project` ORDER BY `id` DESC");
                            $c = 1;
                            while ($data = mysqli_fetch_array($selection)) {
                            ?>
                                <tr>
                                    <td><?= $c; ?></td>
                                    <td>
                                        <?php if (!empty($data['image'])) { ?>
                                            <img src="uploads/projects/<?= $data['image'] ?>" width="100px" alt="Image">
                                        <?php } else { ?>
                                            No image
                                        <?php } ?>
                                    </td>
                                    <td><?= $data['description']; ?></td>
                                    <td>
                                        <a href="projectedit.php?project_id=<?= $data['id'] ?>" class="text-danger"><i class="bi bi-trash"></i></a>
                                    </td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#updateProjectModal-<?= $data['id'] ?>" class="text-warning"><i class="bi bi-pencil"></i></a>
                                    </td>
                                </tr>

                                <!-- Update Project Modal -->
                                <div class="modal fade" id="updateProjectModal-<?= $data['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Project</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="projectedit.php" method="POST" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                    <div class="mb-3">
                                                        <label>Upload Image</label>
                                                        <input type="file" class="form-control" name="image">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Description</label>
                                                        <textarea class="form-control" name="description" required><?= $data['description']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="update_project">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Add Project Modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add Project</button>
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="projectedit.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Upload Image</label>
                            <input type="file" class="form-control" name="image" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_project">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php
include('footer.php');
?>

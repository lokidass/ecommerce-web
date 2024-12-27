<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT img FROM `logo` WHERE id = $id");

    if ($query && $data = mysqli_fetch_assoc($query)) {
        // Delete the image file from the server
        if (!empty($data['img']) && file_exists('uploads/logos/' . $data['img'])) {
            unlink('uploads/logos/' . $data['img']);
        }

        // Delete the record from the database
        $deleteQuery = mysqli_query($con, "UPDATE `logo`
SET `img` = NULL;");

        if ($deleteQuery) {
            header('Location: headeredit.php?message=Deleted Successfully');
        } else {
            header('Location: headeredit.php?message=Delete Failed');
        }
    } else {
        header('Location: headeredit.php?message=Record Not Found');
    }
}
?>

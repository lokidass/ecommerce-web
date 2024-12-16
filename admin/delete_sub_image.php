<?php
include("../config.php");
if (isset($_GET['id']) && isset($_GET['product_id'])) {
    $sub_image_id = $_GET['id'];
    $product_id = $_GET['product_id'];

    // Fetch the sub-image filename for the given sub-image ID
    $subImageQuery = "SELECT image FROM product_sub_images WHERE id = $sub_image_id AND product_id = $product_id";
    $subImageResult = mysqli_query($con, $subImageQuery);

    if ($subImageResult) {
        $subImage = mysqli_fetch_assoc($subImageResult);

        if ($subImage) {
            // Ensure the file path is correct
            $file_path = "uploads/product_sub_images/" . $subImage['image'];

            // Delete the sub-image file from the server
            if (unlink($file_path)) {
                // If the file is successfully deleted, delete the record from the database
                $deleteQuery = "DELETE FROM product_sub_images WHERE id = $sub_image_id";
                if (mysqli_query($con, $deleteQuery)) {
                    // Redirect to the product edit page for the current product
                    header("Location: productedit.php?id=$product_id");
                    exit;
                } else {
                    echo "Error deleting sub-image from database: " . mysqli_error($con);
                }
            } else {
                echo "Error deleting the sub-image file. Please check file permissions.";
            }
        } else {
            echo "Sub-image not found in the database.";
        }
    } else {
        echo "Error fetching sub-image data: " . mysqli_error($con);
    }
}

?>

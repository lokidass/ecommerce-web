<?php
include("../config.php");
// updateCategoryOrder.php
header("Content-Type: application/json");

// Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['order']) && is_array($data['order'])) {
        foreach ($data['order'] as $position => $id) {
            $stmt = $con->prepare("UPDATE categories SET position = ? WHERE id = ?");
            $stmt->bind_param("ii", $position, $id);
            $stmt->execute();
        }

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid data."]);
    }
}
?>
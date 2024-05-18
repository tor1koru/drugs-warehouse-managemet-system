<?php
include_once "../database/db_connection.php";

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query) {
    $sql = "SELECT id_dep, name_dep FROM Department WHERE name_dep LIKE '%$query%'";
    $result = $conn->query($sql);

    $departments = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }

    echo json_encode($departments);
}

// Закриття з'єднання з базою даних
$conn->close();
?>

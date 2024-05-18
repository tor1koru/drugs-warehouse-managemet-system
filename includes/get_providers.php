<?php
include_once "../database/db_connection.php";

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query) {
    $sql = "SELECT id_provider, name_provider FROM Providers WHERE name_provider LIKE '%$query%'";
    $result = $conn->query($sql);

    $providers = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }
    }

    echo json_encode($providers);
}

// Закриття з'єднання з базою даних
$conn->close();
?>

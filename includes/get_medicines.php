<?php
include_once "../database/db_connection.php";

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query) {
    $sql = "SELECT id_med, name_med, med_form, dosage, producer FROM Medicines WHERE name_med LIKE '%$query%'";
    $result = $conn->query($sql);

    $medicines = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $medicines[] = $row;
        }
    }

    echo json_encode($medicines);
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<?php
include_once "../database/db_connection.php";

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query) {
    $sql = "SELECT id_person, CONCAT(name, ' ', surname, ' ', patronim, ' ', age) AS full_name FROM Person WHERE name LIKE '%$query%' OR surname LIKE '%$query%' OR patronim LIKE '%$query%'";
    $result = $conn->query($sql);

    $persons = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $persons[] = $row;
        }
    }

    echo json_encode($persons);
}

// Закриття з'єднання з базою даних
$conn->close();
?>

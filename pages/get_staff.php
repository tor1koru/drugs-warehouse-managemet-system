<?php
include_once "../database/db_connection.php";

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query) {
    $sql = "SELECT s.id_staff, CONCAT(p.name, ' ', p.surname, ' ', p.patronim) AS full_name, p.age, s.position, d.name_dep
            FROM Staff s
            INNER JOIN Person p ON s.id_person = p.id_person
            INNER JOIN Department d ON s.id_dep = d.id_dep
            WHERE p.name LIKE '%$query%' OR p.surname LIKE '%$query%' OR p.patronim LIKE '%$query%' OR s.position LIKE '%$query%' OR d.name_dep LIKE '%$query%'";
    $result = $conn->query($sql);

    $staff = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }
    }

    echo json_encode($staff);
}

// Закриття з'єднання з базою даних
$conn->close();
?>

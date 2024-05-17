<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список відділів</title>
</head>
<body>
<h1>Список відділів</h1>

<?php
include_once "../database/db_connection.php";

// SQL запит для вибору всіх даних з таблиці Department
$sql = "SELECT * FROM Department";
$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Назва Відділу</th><th>Склад відділу</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_dep"] . "</td>";
        echo "<td>" . $row["name_dep"] . "</td>";
        echo "<td><a href='dep_storage.php?id_dep=" . $row["id_dep"] . "'>Склад відділу</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

</body>
</html>

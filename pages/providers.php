
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список виробників</title>
</head>
<body>
<h1>Список виробників</h1>
<a href="home.php">На головну</a><br><br>

<?php
include_once "../database/db_connection.php";
// SQL запит для вибору всіх даних з таблиці Providers
$sql = "SELECT * FROM Providers";
$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Назва виробника</th><th>Адреса</th><th>Телефон</th><th>ПІБ менеджера</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_provider"] . "</td>";
        echo "<td>" . $row["name_provider"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td>" . $row["teleph"] . "</td>"; // Виправлено назву поля
        echo "<td>" . $row["fullName_manager"] . "</td>";
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

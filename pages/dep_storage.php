<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Відділ</title>
</head>
<body>
<h1>Медикаменти відділу</h1>

<?php
require_once "../database/db_connection.php";

// Перевірка, чи передано ID відділу через GET-запит

if (!isset($_GET['id'])) {
    echo "Не вказано ID відділу.";
    exit;
}
$department_id = $_GET['id_dep'];


// SQL запит для вибору всіх даних з таблиці Department_storage для вибраного відділу
$sql = "SELECT * FROM Department_storage WHERE id_department = $department_id";
$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Назва медикаменту</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Кількість на відділі</th><th>Дата останнього відвантаження</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_med_dep_storage"] . "</td>";
        echo "<td>" . $row["name_med"] . "</td>";
        echo "<td>" . $row["med_form"] . "</td>";
        echo "<td>" . $row["dosage"] . "</td>";
        echo "<td>" . $row["producer"] . "</td>";
        echo "<td>" . $row["count_dep"] . "</td>";
        echo "<td>" . $row["data_dep"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "На цьому відділі немає медикаментів";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

</body>
</html>

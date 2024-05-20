<?php require_once "../includes/header.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список виробників</title>
    <style>
        /* Сховати форму спочатку */
        #addProviderForm {
            display: none;
        }
    </style>
</head>
<body>
<h1>Список постачальників</h1>

<?php
include_once "../database/db_connection.php";
// SQL запит для вибору всіх даних з таблиці Providers
$sql = "SELECT * FROM Providers";
$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Назва виробника</th><th>Адреса</th><th>Телефон</th><th>ПІБ менеджера</th><th>Редагувати</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_provider"] . "</td>";
        echo "<td>" . $row["name_provider"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td>" . $row["teleph"] . "</td>"; // Виправлено назву поля
        echo "<td>" . $row["fullName_manager"] . "</td>";
        // Додано стовпці з посиланнями на редагування та видалення
        echo "<td><a href='edit_provider.php?id=" . $row["id_provider"] . "'>Редагувати</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних";
}

// Закриття з'єднання з базою даних
$conn->close();
?>
<button onclick="toggleForm()">Додати нового постачальника</button>

<!-- Форма для додавання нового постачальника -->
<div id="addProviderForm">
    <h2>Додати нового постачальника</h2>
    <form method="post" action="../includes/add_provider.php">
        Назва виробника: <input type="text" name="name_provider" required><br><br>
        Адреса: <input type="text" name="address" required><br><br>
        Телефон: <input type="text" name="teleph" required><br><br>
        ПІБ менеджера: <input type="text" name="fullName_manager" required><br><br>
        <input type="submit" value="Додати постачальника">
    </form>
</div>

<script>
    function toggleForm() {
        var form = document.getElementById('addProviderForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
</body>
</html>

<?php require_once "../includes/footer.php" ?>
<?php require_once "../includes/header.php"; ?>
<h1>Список осіб</h1>

<form method="get" action="pers.php">
    <label for="search">Пошук за Ім'ям, Прізвищем та По батькові:</label>
    <input type="text" id="search" name="search">
    <button type="submit">Пошук</button>
</form>

<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Якщо надіслано пошуковий запит
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    // SQL запит з фільтрацією за Ім'ям, Прізвищем та По батькові
    $sql = "SELECT * FROM Person WHERE CONCAT(name, ' ', surname, ' ', patronim) LIKE '%$search_term%'";
} else {
    // Звичайний SQL запит для вибору всіх осіб
    $sql = "SELECT * FROM Person";
}

$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Ім'я</th><th>Прізвище</th><th>По батькові</th><th>Вік</th><th>Адреса</th><th>Телефон</th><th>Дії</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_person"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["surname"] . "</td>";
        echo "<td>" . $row["patronim"] . "</td>";
        echo "<td>" . $row["age"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td>" . $row["telephone"] . "</td>";

        // Перевірка, чи поточний користувач - адміністратор, перед виведенням посилань на редагування та видалення
        if ($_SESSION['login'] == "admin") {
            echo "<td><a href='edit_person.php?id=" . $row["id_person"] . "'>Редагувати</a></td>";
        } else {
            echo "<td colspan='2'>Редагування доступні тільки адміністратору</td>";
        }

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<br>
<button onclick="toggleForm()">Додати нову особу</button>

<!-- Форма для додавання нової особи -->
<div id="addPersonForm" style="display: none;">
    <h2>Додати нову особу</h2>
    <form method="post" action="../includes/add_person.php">
        Ім'я: <input type="text" name="name" required><br><br>
        Прізвище: <input type="text" name="surname" required><br><br>
        По батькові: <input type="text" name="patronim" required><br><br>
        Вік: <input type="number" name="age" required min="0"><br><br>
        Адреса: <input type="text" name="address" required><br><br>
        Телефон: <input type="text" name="telephone" required><br><br>
        <input type="submit" value="Додати особу">
    </form>
</div>

<script>
    function toggleForm() {
        var form = document.getElementById('addPersonForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>

<?php require_once "../includes/footer.php"; ?>

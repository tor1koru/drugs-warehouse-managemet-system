<?php require_once "../includes/header.php" ?>
<h1>Склад відділу</h1>

<form method="GET" action="">
    <label for="search_med">Пошук за назвою медикаменту:</label>
    <input type="text" id="search_med" name="search_med">
    <button type="submit">Пошук</button>
    <?php if (isset($_GET['id_dep'])): ?>
        <input type="hidden" name="id_dep" value="<?= htmlspecialchars($_GET['id_dep']) ?>">
    <?php endif; ?>
</form>

<?php
include_once "../database/db_connection.php";
require_once "../includes/depart_storage.php";
global $conn;
// Отримуємо ID відділу з GET параметра
$id_dep = isset($_GET['id_dep']) ? intval($_GET['id_dep']) : 0;

// Отримуємо назву медикаменту з GET параметра
$search_med = isset($_GET['search_med']) ? $conn->real_escape_string($_GET['search_med']) : '';

// SQL запит для вибору даних з таблиці Department_storage з можливістю пошуку за назвою медикаменту
$sql = "SELECT * FROM Department_storage WHERE id_department = $id_dep";
if (!empty($search_med)) {
    $sql .= " AND name_med LIKE '%$search_med%'";
}
$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID відділу</th><th>Назва медикаменту</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Кількість на складі</th><th>Дата оновлення</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_department"] . "</td>";
        echo "<td>" . $row["name_med"] . "</td>";
        echo "<td>" . $row["med_form"] . "</td>";
        echo "<td>" . $row["dosage"] . "</td>";
        echo "<td>" . $row["producer"] . "</td>";
        echo "<td>" . $row["count_dep"] . "</td>";
        echo "<td>" . $row["date_dep"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних для вибраного відділу";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<?php if ($_SESSION['login'] !== 'admin'): ?>
<button onclick="toggleForm('transferForm')">Видати ліки</button>
<button onclick="toggleForm('addPersonForm')">Додати нову особу</button>
<div id="addPersonForm" style="display: none;">
    <h2>Додати нову особу</h2>
    <form method="post" action="../includes/add_person.php">
        Ім'я: <input type="text" name="name" required><br><br>
        Прізвище: <input type="text" name="surname" required><br><br>
        По батькові: <input type="text" name="patronim" required><br><br>
        Вік: <input type="date" name="age" required><br><br>
        Адреса: <input type="text" name="address" required><br><br>
        Телефон: <input type="text" name="telephone" required><br><br>
        <input type="submit" value="Додати особу">
    </form>
</div>
<div id="transferForm" style="display: none;">
    <h1>Передача медикаменту клієнту</h1>
    <form method="post" action="process_transfer_med_to_client.php">
        <h2>Виберіть медикамент, особу та співробітника</h2>

        Медикамент:
        <input type="text" id="medicineInput" name="medicineInput" required>
        <input type="hidden" id="id_med_client"name="id_med_client">
        <div id="autocomplete-medicine-list" class="autocomplete-suggestions"></div><br><br>

        Особа:
        <input type="text" id="personInput" name="personInput" required>
        <input type="hidden" id="id_person" name="id_person">
        <div id="autocomplete-person-list" class="autocomplete-suggestions"></div><br><br>

        Кількість: <input type="number" name="count_to_person" required><br><br>
        Дата: <input type="date" name="date_output_to_client" required><br><br>
        <input type="submit" value="Передати медикамент клієнту">
    </form>
</div>
<?php endif; ?>

<script>
    function toggleForm(formId) {
        var form = document.getElementById(formId);
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
<?php require_once "../includes/footer.php" ?>

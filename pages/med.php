<?php require_once "../includes/header.php" ?>
<h1 class="main__title">Список Медикаментів</h1>

<form method="get" action="med.php">
    <label for="search">Пошук за назвою:</label>
    <input type="text" id="search" name="search">
    <button type="submit">Пошук</button>
</form>

<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Якщо надіслано пошуковий запит
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    // SQL запит з фільтрацією за назвою, яка починається з введеного символу
    $sql = "SELECT * FROM Medicines WHERE name_med LIKE '$search_term%'";
} else {
    // Звичайний SQL запит для вибору всіх медикаментів
    $sql = "SELECT * FROM Medicines";
}

$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table class='main__table'>";
    echo "<tr class='table__row first-row'>
            <th class='row__item table__header'>ID</th>
            <th class='row__item table__header'>Назва</th>
            <th class='row__item table__header'>Форма</th>
            <th class='row__item table__header'>Дозування</th>
            <th class='row__item table__header'>Виробник</th>
            <th class='row__item table__header'>Дії</th>
           </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='table__row'>";
        echo "<td class='row__item table__header'>" . $row["id_med"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["name_med"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["med_form"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["dosage"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["producer"] . "</td>";

        // Перевірка, чи поточний користувач - адміністратор, перед виведенням посилань на редагування та видалення
        if ($_SESSION['login'] == "admin") {
            echo "<td><a href='edit_med.php?id=" . $row["id_med"] . "'>Редагувати</a></td>";
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

<!-- Форма для додавання нового медикаменту -->
<?php if ($_SESSION['login'] == "admin") { ?>
    <button onclick="toggleForm()">Додати новий медикамент</button>
<?php } else { ?>

<?php } ?>

<div id="addMedForm" style="display: none;">
    <h2>Додати новий медикамент</h2>
    <form method="post" action="../includes/add_med.php">
        <label for="name_med">Назва медикаменту:</label>
        <input type="text" id="name_med" name="name_med" required><br><br>
        <label for="med_form">Форма медикаменту:</label>
        <input type="text" id="med_form" name="med_form" required><br><br>
        <label for="dosage">Дозування:</label>
        <input type="text" id="dosage" name="dosage" required><br><br>
        <label for="producer">Виробник:</label>
        <input type="text" id="producer" name="producer" required><br><br>
        <input type="submit" value="Додати медикамент">
    </form>
</div>

<script>
    function toggleForm() {
        var form = document.getElementById('addMedForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>

</script>

<?php require_once "../includes/footer.php" ?>

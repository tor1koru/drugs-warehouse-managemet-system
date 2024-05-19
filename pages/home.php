<?php require_once "../includes/header.php" ?>
<h1 class="main__title">Ліки на складі</h1>

<?php
include_once "../database/db_connection.php";
include_once "../includes/main_storage.php";
 //Створення таблиці Main_storage, якщо вона не існує

// SQL запит для вибору всіх даних з таблиці Main_storage
// Перевірка, чи була надіслана форма фільтрування
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    // Отримання значення з поля форми
    $search_term = $_POST['search_term'];

    // SQL-запит для фільтрації за назвою ліків
    $sql = "SELECT * FROM Main_storage WHERE name_med LIKE '$search_term%'";
} else {
    // Запит без фільтрації
    $sql = "SELECT * FROM Main_storage";
}

$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<form method='post' action=''>";
    echo "<label for='search_term'>Фільтрувати за назвою ліків:</label>";
    echo "<input type='text' name='search_term' id='search_term'>";
    echo "<input type='submit' name='filter' value='Фільтрувати'>";
    echo "</form>";

    echo "<table class='main__table'>";
    echo "<tr class='table__row first-row'>
            <th class='row__item table__header'>ID</th>
            <th class='row__item table__header'>Назва</th>
            <th class='row__item table__header'>Форма</th>
            <th class='row__item table__header'>Дозування</th>
            <th class='row__item table__header'>Виробник</th>
            <th class='row__item table__header'>Кількість на складі</th>
            <th class='row__item table__header'>Дата останнього оновлення</th>
           </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='table__row'>";
        echo "<td class='row__item table__header'>" . $row["id_med_main_storage"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["name_med"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["med_form"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["dosage"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["producer"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["count_store"] . "</td>";
        echo "<td class='row__item table__header'>" . $row["data_store"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<?php require_once "../includes/footer.php" ?>

<?php require_once "../includes/header.php" ?>
<h1 class="main__title">Ліки на складі</h1>

<?php
include_once "../database/db_connection.php";
include_once "../includes/main_storage.php";
// Створення таблиці Main_storage, якщо вона не існує
//$create_table_sql = "
//            CREATE TABLE IF NOT EXISTS Main_storage (
//                id_med_main_storage INT PRIMARY KEY AUTO_INCREMENT,
//                name_med VARCHAR(255),
//                med_form VARCHAR(255),
//                dosage VARCHAR(255),
//                producer VARCHAR(255),
//                count_store INT,
//                data_store DATE
//            )";
//if (!$conn->query($create_table_sql)) {
//    die("Помилка створення таблиці: " . $conn->error);
//}

//// Оновлення даних в Main_storage при додаванні нових записів в Input_to_main та Output_from_main
//function updateMainStorage($conn) {
//    // Очищаємо Main_storage перед оновленням
//    if (!$conn->query("TRUNCATE TABLE Main_storage")) {
//        die("Помилка очищення таблиці: " . $conn->error);
//    }
//
//    // SQL-запит для обчислення count_store та data_store
//    $sql = "
//                    SELECT
//                        m.id_med,
//                        m.name_med,
//                        m.med_form,
//                        m.dosage,
//                        m.producer,
//                        COALESCE((SELECT SUM(count) FROM Input_to_main WHERE id_med_input = m.id_med), 0) -
//                        COALESCE((SELECT SUM(count) FROM Output_from_main WHERE id_med_output = m.id_med), 0) AS count_store,
//                        GREATEST(
//                            (SELECT IFNULL(MAX(date_input), '0000-00-00') FROM Input_to_main WHERE id_med_input = m.id_med),
//                            (SELECT IFNULL(MAX(date_output), '0000-00-00') FROM Output_from_main WHERE id_med_output = m.id_med)
//                        ) AS data_store
//                    FROM
//                        Medicines m
//                ";
//
//    $result = $conn->query($sql);
//
//    if ($result->num_rows > 0) {
//        while ($row = $result->fetch_assoc()) {
//            $name_med = $row['name_med'];
//            $med_form = $row['med_form'];
//            $dosage = $row['dosage'];
//            $producer = $row['producer'];
//            $count_store = $row['count_store'];
//            $data_store = $row['data_store'];
//
//            $insert_sql = "
//                            INSERT INTO Main_storage (name_med, med_form, dosage, producer, count_store, data_store)
//                            VALUES ('$name_med', '$med_form', '$dosage', '$producer', $count_store, '$data_store')
//                        ";
//            if (!$conn->query($insert_sql)) {
//                die("Помилка вставки даних: " . $conn->error);
//            }
//        }
//    }
//}
//
//// Виклик функції оновлення Main_storage
//updateMainStorage($conn);
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
<a href="output_to_client_form.php">Видати ліки </a><br><br>


<?php require_once "../includes/footer.php" ?>

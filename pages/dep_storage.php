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
// Функція для оновлення даних в таблиці Department_storage
//function updateDepartmentStorage($conn) {
//    if (!$conn->query("TRUNCATE TABLE Department_storage")) {
//        die("Помилка очищення таблиці: " . $conn->error);
//    }
//
//    $sql = "
//        SELECT
//            m.id_med,
//            os.id_dep_out AS id_department,
//            m.name_med,
//            m.med_form,
//            m.dosage,
//            m.producer,
//            SUM(os.count) AS count_dep,
//            MAX(os.date_output) AS date_dep
//        FROM
//            Output_from_main os
//        INNER JOIN
//            Medicines m ON os.id_med_output = m.id_med
//        GROUP BY
//            os.id_dep_out, m.id_med
//        UNION ALL
//        SELECT
//            m.id_med,
//            st.id_dep AS id_department,
//            m.name_med,
//            m.med_form,
//            m.dosage,
//            m.producer,
//            -SUM(ot.count_to_person) AS count_dep,
//            MAX(ot.date_output_to_client) AS date_dep
//        FROM
//            Output_to_client ot
//        INNER JOIN
//            Medicines m ON ot.id_med_client = m.id_med
//        INNER JOIN
//            Staff st ON ot.id_staff_client = st.id_staff
//        GROUP BY
//            st.id_dep, m.id_med
//    ";
//
//    $result = $conn->query($sql);
//
//    if ($result->num_rows > 0) {
//        $medicines = array();
//
//        while ($row = $result->fetch_assoc()) {
//            $id_department = $row['id_department'];
//            $id_med = $row['id_med'];
//            $key = $id_department . '-' . $id_med;
//
//            if (!isset($medicines[$key])) {
//                $medicines[$key] = array(
//                    'id_department' => $row['id_department'],
//                    'name_med' => $row['name_med'],
//                    'med_form' => $row['med_form'],
//                    'dosage' => $row['dosage'],
//                    'producer' => $row['producer'],
//                    'count_dep' => 0,
//                    'date_dep' => '0000-00-00'
//                );
//            }
//
//            $medicines[$key]['count_dep'] += $row['count_dep'];
//            if ($row['date_dep'] > $medicines[$key]['date_dep']) {
//                $medicines[$key]['date_dep'] = $row['date_dep'];
//            }
//        }
//
//        foreach ($medicines as $medicine) {
//            $id_department = $medicine['id_department'];
//            $name_med = $medicine['name_med'];
//            $med_form = $medicine['med_form'];
//            $dosage = $medicine['dosage'];
//            $producer = $medicine['producer'];
//            $count_dep = $medicine['count_dep'];
//            $date_dep = $medicine['date_dep'];
//
//            $insert_sql = "
//                INSERT INTO Department_storage (id_department, name_med, med_form, dosage, producer, count_dep, date_dep)
//                VALUES ($id_department, '$name_med', '$med_form', '$dosage', '$producer', $count_dep, '$date_dep')
//            ";
//            if (!$conn->query($insert_sql)) {
//                die("Помилка вставки даних: " . $conn->error);
//            }
//        }
//    }
//}
//
//// Виклик функції оновлення Department_storage
//updateDepartmentStorage($conn);

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

<?php require_once "../includes/footer.php" ?>
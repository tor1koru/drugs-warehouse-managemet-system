<?php require_once "../includes/header.php" ?>

<title>Output from Main</title>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>
<h1>Output from Main</h1>

<form action="" method="GET">
    <label for="search_med">Пошук за назвою медикаменту:</label>
    <input type="text" id="search_med" name="search_med">

    <label for="search_dep">Пошук за назвою відділу:</label>
    <input type="text" id="search_dep" name="search_dep">

    <input type="submit" value="Пошук">
</form>

<?php
include_once "../database/db_connection.php";

// Перевіряємо, чи був введений пошуковий запит
if (isset($_GET['search_med']) && isset($_GET['search_dep'])) {
    $search_med = $_GET['search_med'];
    $search_dep = $_GET['search_dep'];
    // SQL-запит для вибору даних з таблиці Output_from_main з відповідними даними з таблиць Medicines та Department з урахуванням пошуку
    $sql = "
        SELECT 
            ofm.id_output,
            ofm.count,
            ofm.date_output,
            m.name_med,
            m.med_form,
            m.dosage,
            m.producer,
            d.name_dep
        FROM 
            Output_from_main ofm
        INNER JOIN 
            Medicines m ON ofm.id_med_output = m.id_med
        INNER JOIN 
            Department d ON ofm.id_dep_out = d.id_dep
        WHERE 
            m.name_med LIKE '%$search_med%'
        AND 
            d.name_dep LIKE '%$search_dep%'
    ";
} else {
    // SQL-запит для вибору всіх даних з таблиці Output_from_main з відповідними даними з таблиць Medicines та Department
    $sql = "
        SELECT 
            ofm.id_output,
            ofm.count,
            ofm.date_output,
            m.name_med,
            m.med_form,
            m.dosage,
            m.producer,
            d.name_dep
        FROM 
            Output_from_main ofm
        INNER JOIN 
            Medicines m ON ofm.id_med_output = m.id_med
        INNER JOIN 
            Department d ON ofm.id_dep_out = d.id_dep
    ";
}

// Додали умову сортування за спаданням ID
$sql .= " ORDER BY ofm.id_output DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Медикамент</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Відділ</th><th>Кількість</th><th>Дата</th><th>Дії</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_output"] . "</td>";
        echo "<td>" . $row["name_med"] . "</td>";
        echo "<td>" . $row["med_form"] . "</td>";
        echo "<td>" . $row["dosage"] . "</td>";
        echo "<td>" . $row["producer"] . "</td>";
        echo "<td>" . $row["name_dep"] . "</td>";
        echo "<td>" . $row["count"] . "</td>";
        echo "<td>" . $row["date_output"] . "</td>";
        // Додаємо посилання на редагування та видалення з параметром ID запису
        echo "<td><a href='edit_output.php?id=" . $row["id_output"] . "'>Редагувати</a> | <a href='delete_output.php?id=" . $row["id_output"] . "'>Видалити</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Немає даних для відображення.";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<?php require_once "../includes/footer.php" ?>

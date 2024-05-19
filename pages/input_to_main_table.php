<?php require_once "../includes/header.php" ?>
    <title>Input to Main</title>
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
<h1>Input to Main</h1>

<?php
include_once "../database/db_connection.php";

// SQL-запит для вибору даних з таблиці Input_to_main з відповідними даними з таблиць Medicines та Providers
$sql = "
    SELECT 
        itm.id_input_to_main,
        itm.count,
        itm.date_input,
        m.name_med,
        m.med_form,
        m.dosage,
        m.producer,
        p.name_provider,
        p.address,
        p.teleph,
        p.fullName_manager
    FROM 
        Input_to_main itm
    INNER JOIN 
        Medicines m ON itm.id_med_input = m.id_med
    INNER JOIN 
        Providers p ON itm.id_med_post = p.id_provider
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Медикамент</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Кількість</th><th>Дата</th><th>Постачальник</th><th>Адреса</th><th>Телефон</th><th>Менеджер</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_input_to_main"] . "</td>";
        echo "<td>" . $row["name_med"] . "</td>";
        echo "<td>" . $row["med_form"] . "</td>";
        echo "<td>" . $row["dosage"] . "</td>";
        echo "<td>" . $row["producer"] . "</td>";
        echo "<td>" . $row["count"] . "</td>";
        echo "<td>" . $row["date_input"] . "</td>";
        echo "<td>" . $row["name_provider"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td>" . $row["teleph"] . "</td>";
        echo "<td>" . $row["fullName_manager"] . "</td>";
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
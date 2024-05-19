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

<?php
include_once "../database/db_connection.php";

// SQL-запит для вибору даних з таблиці Output_from_main з відповідними даними з таблиць Medicines та Department
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

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Медикамент</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Відділ</th><th>Кількість</th><th>Дата</th></tr>";

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
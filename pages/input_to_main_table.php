<?php require_once "../includes/header.php" ?>


<h1>Input to Main</h1>

<form method="GET" action="">
    <label for="search">Пошук за назвою медикаменту:</label>
    <input type="text" id="search" name="search">
    <button type="submit">Пошук</button>
</form>

<?php
include_once "../database/db_connection.php";
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

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
    WHERE 
        m.name_med LIKE '%".$search."%'
    ORDER BY
        itm.id_input_to_main DESC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Медикамент</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Кількість</th><th>Дата</th><th>Постачальник</th><th>Адреса</th><th>Телефон</th><th>Менеджер</th><th>Дії</th></tr>";

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
        echo "<td><a href='edit_input_main.php?id=" . $row["id_input_to_main"] . "'>Редагувати</a> | <a href='delete_input_main.php?id=" . $row["id_input_to_main"] . "'>Видалити</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Немає результатів для введеної назви медикаменту.";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<?php require_once "../includes/footer.php" ?>

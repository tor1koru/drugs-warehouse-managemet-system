<?php require_once "../includes/header.php" ?>


    <h1>Output to Client</h1>

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
    // SQL-запит для вибору даних з таблиці Output_to_client з відповідними даними з таблиць Medicines, Person, Staff та Department з урахуванням пошуку
    $sql = "
    SELECT 
        otc.id_output_client,
        otc.count_to_person,
        otc.date_output_to_client,
        m.name_med,
        m.med_form,
        m.dosage,
        m.producer,
        p.name AS person_name,
        p.surname AS person_surname,
        p.patronim AS person_patronim,
        p.age AS person_age,
        p.address AS person_address,
        p.telephone AS person_telephone,
        s.position,
        d.name_dep,
        sp.name AS staff_name,
        sp.surname AS staff_surname,
        sp.patronim AS staff_patronim,
        sp.age AS staff_age
    FROM 
        Output_to_client otc
    INNER JOIN 
        Medicines m ON otc.id_med_client = m.id_med
    INNER JOIN 
        Person p ON otc.id_person = p.id_person
    INNER JOIN 
        Staff s ON otc.id_staff_client = s.id_staff
    INNER JOIN 
        Department d ON s.id_dep = d.id_dep
    INNER JOIN 
        Person sp ON s.id_person = sp.id_person
    WHERE 
        m.name_med LIKE '%$search_med%'
    AND 
        d.name_dep LIKE '%$search_dep%'
";
} else {
    // SQL-запит для вибору всіх даних з таблиці Output_to_client з відповідними даними з таблиць Medicines, Person, Staff та Department
    $sql = "
    SELECT 
        otc.id_output_client,
        otc.count_to_person,
        otc.date_output_to_client,
        m.name_med,
        m.med_form,
        m.dosage,
        m.producer,
        p.name AS person_name,
        p.surname AS person_surname,
        p.patronim AS person_patronim,
        p.age AS person_age,
        p.address AS person_address,
        p.telephone AS person_telephone,
        s.position,
        d.name_dep,
        sp.name AS staff_name,
        sp.surname AS staff_surname,
        sp.patronim AS staff_patronim,
        sp.age AS staff_age
    FROM 
        Output_to_client otc
    INNER JOIN 
        Medicines m ON otc.id_med_client = m.id_med
    INNER JOIN 
        Person p ON otc.id_person = p.id_person
    INNER JOIN 
        Staff s ON otc.id_staff_client = s.id_staff
    INNER JOIN 
        Department d ON s.id_dep = d.id_dep
    INNER JOIN 
        Person sp ON s.id_person = sp.id_person
";
}

// Додали умову сортування за спаданням ID
$sql .= " ORDER BY otc.id_output_client DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Медикамент</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Клієнт</th><th>Адреса</th><th>Телефон</th><th>Відділ</th><th>Персонал</th><th>Посада</th><th>Кількість</th><th>Дата</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_output_client"] . "</td>";
        echo "<td>" . $row["name_med"] . "</td>";
        echo "<td>" . $row["med_form"] . "</td>";
        echo "<td>" . $row["dosage"] . "</td>";
        echo "<td>" . $row["producer"] . "</td>";
        echo "<td>" . $row["person_name"] . " " . $row["person_surname"] . " " . $row["person_patronim"] . " (" . $row["person_age"] . ")</td>";
        echo "<td>" . $row["person_address"] . "</td>";
        echo "<td>" . $row["person_telephone"] . "</td>";
        echo "<td>" . $row["name_dep"] . "</td>";
        echo "<td>" . $row["staff_name"] . " " . $row["staff_surname"] . " " . $row["staff_patronim"] . " (" . $row["staff_age"] . ")</td>";
        echo "<td>" . $row["position"] . "</td>";
        echo "<td>" . $row["count_to_person"] . "</td>";
        echo "<td>" . $row["date_output_to_client"] . "</td>";
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
<?php require_once "../includes/header.php"; ?>

<h1>Персонал відділу</h1>

<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Отримання id_dep з GET параметра
$id_dep = isset($_GET['id_dep']) ? intval($_GET['id_dep']) : 0;

if ($id_dep > 0) {
    // SQL запит для вибору персоналу відділу за id_dep
    $sql = "
        SELECT 
            p.id_person, 
            p.name, 
            p.surname, 
            p.patronim, 
            p.age, 
            p.address, 
            p.telephone,
            s.position
        FROM 
            Person p
        INNER JOIN 
            Staff s ON p.id_person = s.id_person
        WHERE 
            s.id_dep = $id_dep
    ";
    $result = $conn->query($sql);

    // Виведення даних, якщо є результат
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я</th><th>Прізвище</th><th>По батькові</th><th>Вік</th><th>Адреса</th><th>Телефон</th><th>Посада</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_person"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["surname"] . "</td>";
            echo "<td>" . $row["patronim"] . "</td>";
            echo "<td>" . $row["age"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["telephone"] . "</td>";
            echo "<td>" . $row["position"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Немає даних про персонал для цього відділу";
    }
} else {
    echo "Некоректний ID відділу";
}

// Закриття з'єднання з базою даних
$conn->close();
?>


<?php require_once "../includes/footer.php"; ?>

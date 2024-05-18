<?php require_once "../includes/header.php" ?>
<h1>Список відділів</h1>
<!-- Форма для пошуку -->
<form method="get" action="">
    <label for="search">Пошук за назвою:</label>
    <input type="text" id="search" name="search">
    <button type="submit">Пошук</button>
</form>
<?php
include_once "../database/db_connection.php";

// Пошук за назвою
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql = "SELECT * FROM Department WHERE name_dep LIKE '%$search_term%'";
} else {
    $sql = "SELECT * FROM Department";
}

$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Назва Відділу</th><th>Склад відділу</th><th>Персонал</th><th>Дії</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_dep"] . "</td>";
        echo "<td>" . $row["name_dep"] . "</td>";
        echo "<td><a href='dep_storage.php?id_dep=" . $row["id_dep"] . "'>Склад відділу</a></td>";
        echo "<td><a href='staf_dep.php?id_dep=" . $row["id_dep"] . "'>Персонал відділу</a></td>";
        echo "<td><a href='edit_dep.php?id=" . $row["id_dep"] . "'>Редагувати</a> | <a href='../includes/delete_dep.php?id=" . $row["id_dep"] . "'>Видалити</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних";
}

// Закриття з'єднання з базою даних
$conn->close();
?>


<!-- Прихована форма для додавання нового відділу -->
<button id="toggleFormBtn">Додати новий відділ</button>
<div id="addDepForm" style="display: none;">
    <h2>Додати новий відділ</h2>
    <form method="post" action="../includes/add_dep.php">
        <label for="name_dep">Назва відділу:</label>
        <input type="text" id="name_dep" name="name_dep"><br><br>
        <input type="submit" value="Додати відділ">
    </form>
</div>
<a href="add_staff.php">Додати персонал до відділу </a><br><br>
<script>
    // JavaScript для переключення видимості форми додавання відділу
    document.getElementById("toggleFormBtn").addEventListener("click", function() {
        var form = document.getElementById("addDepForm");
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });
</script>


<?php require_once "../includes/footer.php" ?>

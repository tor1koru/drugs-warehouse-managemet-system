<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Перевірка, чи було передано ID відділу
if (!isset($_GET['id'])) {
    echo "Не вказано ID відділу.";
    exit;
}

// Отримання ID відділу з параметрів запиту
$id_dep = $_GET['id'];

// Отримання даних відділу з бази даних за його ID
$sql = "SELECT * FROM Department WHERE id_dep = $id_dep";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Відділ з вказаним ID не знайдено.";
    exit;
}

$row = $result->fetch_assoc();

// Якщо форма була відправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання нових даних з форми
    $new_name_dep = $_POST['new_name_dep'];

    // Перевірка наявності такого самого відділу в базі даних
    $check_sql = "SELECT * FROM Department WHERE name_dep = '$new_name_dep' AND id_dep != $id_dep";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Відділ з такою назвою вже існує.";
    } else {
        // SQL-запит для оновлення даних відділу
        $update_sql = "UPDATE Department SET name_dep = '$new_name_dep' WHERE id_dep = $id_dep";

        // Виконання запиту
        if ($conn->query($update_sql) === TRUE) {
            echo "Дані відділу успішно оновлено";
            // Перенаправлення на список відділів
            header("Location: dep.php");
            exit;
        } else {
            echo "Помилка оновлення даних відділу: " . $conn->error;
        }
    }
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<?php require_once "../includes/header.php";  ?>
<h1>Редагування відділу</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id_dep; ?>">
    Нова назва відділу: <input type="text" name="new_name_dep" value="<?php echo $row['name_dep']; ?>"><br><br>
    <input type="submit" value="Зберегти зміни">
</form>
<?php require_once "../includes/footer.php";  ?>

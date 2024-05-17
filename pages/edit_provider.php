<?php
include_once "../database/db_connection.php";

// Перевірка, чи переданий параметр id
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Запит до бази даних для отримання даних про постачальника за вказаним id
    $sql = "SELECT * FROM Providers WHERE id_provider = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name_provider = $row['name_provider'];
        $address = $row['address'];
        $teleph = $row['teleph'];
        $fullName_manager = $row['fullName_manager'];
    } else {
        echo "Постачальника з таким ID не знайдено";
        exit();
    }
} else {
    echo "Не передано параметр ID";
    exit();
}

// Перевірка, чи була надіслана форма для оновлення даних
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $name_provider_new = $_POST['name_provider'];
    $address_new = $_POST['address'];
    $teleph_new = $_POST['teleph'];
    $fullName_manager_new = $_POST['fullName_manager'];

    // SQL-запит для оновлення даних про постачальника
    $update_sql = "UPDATE Providers SET name_provider='$name_provider_new', address='$address_new', teleph='$teleph_new', fullName_manager='$fullName_manager_new' WHERE id_provider=$id";

    if ($conn->query($update_sql) === TRUE) {
        echo "Дані успішно оновлено";
        // Перенаправлення на сторінку providers.php після оновлення
        header("Location: providers.php");
        exit();
    } else {
        echo "Помилка під час оновлення даних: " . $conn->error;
    }
}
$conn->close();
?>

<?php require_once "../includes/header.php"; ?>
<h1>Редагувати постачальника</h1>
<a href="providers.php">Назад до списку постачальників</a><br><br>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id=<?php echo $id; ?>">
    Назва виробника: <input type="text" name="name_provider" value="<?php echo $name_provider; ?>"><br><br>
    Адреса: <input type="text" name="address" value="<?php echo $address; ?>"><br><br>
    Телефон: <input type="text" name="teleph" value="<?php echo $teleph; ?>"><br><br>
    ПІБ менеджера: <input type="text" name="fullName_manager" value="<?php echo $fullName_manager; ?>"><br><br>
    <input type="submit" value="Оновити дані">
</form>

<?php require_once "../includes/footer.php"; ?>

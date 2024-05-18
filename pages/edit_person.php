<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Перевірка, чи отримано id особи для редагування
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL-запит для вибору даних про особу з бази даних за її id
    $sql = "SELECT * FROM Person WHERE id_person=$id";
    $result = $conn->query($sql);

    // Перевірка, чи є результат запиту
    if ($result->num_rows > 0) {
        // Отримання даних про особу
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $surname = $row["surname"];
        $patronim = $row["patronim"];
        $age = $row["age"];
        $address = $row["address"];
        $telephone = $row["telephone"];
    } else {
        // Повідомлення про помилку, якщо особу не знайдено
        echo "Помилка: Особу не знайдено";
        exit(); // Завершення виконання скрипту
    }
} else {
    // Повідомлення про помилку, якщо не отримано id
    echo "Помилка: Не отримано ID для редагування";
    exit(); // Завершення виконання скрипту
}

// Обробка даних, якщо форма відправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання та очищення даних з форми
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $patronim = htmlspecialchars($_POST['patronim']);
    $age = htmlspecialchars($_POST['age']);
    $address = htmlspecialchars($_POST['address']);
    $telephone = htmlspecialchars($_POST['telephone']);

    // SQL-запит для оновлення даних про особу
    $sql_update = "UPDATE Person SET name='$name', surname='$surname', patronim='$patronim', age='$age', address='$address', telephone='$telephone' WHERE id_person=$id";

    if ($conn->query($sql_update) === TRUE) {
        // Повідомлення про успішне оновлення даних
        header("Location: pers.php");
    } else {
        // Повідомлення про помилку оновлення даних
        echo "Помилка оновлення даних: " . $conn->error;
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Редагування особи</title>
    </head>
    <body>
    <h1>Редагування особи</h1>

    <form method="post" action="">
        Ім'я: <input type="text" name="name" value="<?php echo $name; ?>"><br><br>
        Прізвище: <input type="text" name="surname" value="<?php echo $surname; ?>"><br><br>
        По батькові: <input type="text" name="patronim" value="<?php echo $patronim; ?>"><br><br>
        Вік: <input type="date" name="age" value="<?php echo $age; ?>"><br><br>
        Адреса: <input type="text" name="address" value="<?php echo $address; ?>"><br><br>
        Телефон: <input type="text" name="telephone" value="<?php echo $telephone; ?>"><br><br>
        <input type="submit" value="Оновити дані">
    </form>

    </body>
    </html>

<?php
// Закриття з'єднання з базою даних
$conn->close();
?>

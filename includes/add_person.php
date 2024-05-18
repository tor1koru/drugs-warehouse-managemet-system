<?php
// Підключення до бази даних
include_once "../database/db_connection.php";

// Перевірка, чи відправлена форма з даними для додавання
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $patronim = $_POST['patronim'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $telephone = $_POST['telephone'];

    // Перетворення дати в формат, який використовує база даних MySQL
    $formatted_age = date('Y-m-d', strtotime($age));

    // Перевірка наявності такої ж особи в базі
    $check_query = "SELECT * FROM Person WHERE name='$name' AND surname='$surname' AND patronim='$patronim' AND age='$formatted_age' AND address='$address' AND telephone='$telephone'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo "Така особа вже існує в базі даних.";
    } else {
        // SQL-запит для вставки нової особи
        $sql = "INSERT INTO Person (name, surname, patronim, age, address, telephone) 
                VALUES ('$name', '$surname', '$patronim', '$formatted_age', '$address', '$telephone')";

        // Виконання запиту
        if ($conn->query($sql) === TRUE) {
            header("Location: ../pages/pers.php");
            exit;
        } else {
            echo "Помилка: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Дані не були відправлені методом POST.";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

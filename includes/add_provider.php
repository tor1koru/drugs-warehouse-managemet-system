<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $name_provider = $_POST['name_provider'];
    $address = $_POST['address'];
    $teleph = $_POST['teleph'];
    $fullName_manager = $_POST['fullName_manager'];

    // Перевірка на унікальність запису перед вставкою
    $check_sql = "SELECT * FROM Providers WHERE name_provider = '$name_provider' AND address = '$address' AND teleph = '$teleph' AND fullName_manager = '$fullName_manager'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Запис з такими даними вже існує.";
        header("Location: ../pages/providers.php");
    } else {
        // SQL-запит для вставки нового постачальника
        $sql = "INSERT INTO Providers (name_provider, address, teleph, fullName_manager) VALUES ('$name_provider', '$address', '$teleph', '$fullName_manager')";

        if ($conn->query($sql) === TRUE) {
            // Після успішного додавання перенаправляємо користувача на сторінку providers.php
            header("Location: ../pages/providers.php");
            exit();
        } else {
            echo "Помилка при додаванні постачальника: " . $conn->error;
        }
    }
}

$conn->close();
?>

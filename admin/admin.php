<?php
require_once '../database/db_connection.php';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Підготовлений вираз для запиту
    $sql = "SELECT id_dep, password, id_staff FROM Staff WHERE login = ?";
    $stmt = $conn->prepare($sql);

    // Перевірка підготовки виразу
    if ($stmt === false) {
        die("Failed to prepare the statement: " . $conn->error);
    }

    // Прив'язування параметра
    $stmt->bind_param("s", $login);

    // Виконання запиту
    $stmt->execute();

    // Отримання результату
    $result = $stmt->get_result();

    // Перевірка, чи знайдений користувач з таким логіном
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Перевірка пароля
            if ($password == $row['password']) {
            // Збереження інформації про користувача в сесії
            $_SESSION['login'] = $login;
            $_SESSION['id_dep'] = $row['id_dep'];
            $_SESSION['id_staff'] = $row['id_staff'];
            if($_SESSION['login'] == "admin"){
                header('Location: ../pages/home.php');
                exit();
            }else {
                // Перенаправлення на іншу сторінку
                header("Location: ../pages/dep_storage.php?id_dep=" . $row['id_dep']);
                exit;
            }
        } else {
                echo "<script>
                alert('Не правильний пароль. Спробуйте ще раз.');
                window.location.href = '../pages/login.php';
              </script>";
        }
    } else {
        echo "<script>
                alert('Користувача з таким логіном не існує. Спробуйте ще раз.');
                window.location.href = '../pages/login.php';
              </script>";
    }

    // Закриття виразу
    $stmt->close();
}

// Закриття з'єднання
$conn->close();
?>

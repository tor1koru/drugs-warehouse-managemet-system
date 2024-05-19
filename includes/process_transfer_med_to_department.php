<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $id_med_output = $_POST['id_med_output'];
    $id_dep_out = $_POST['id_dep_out'];
    $count = $_POST['count'];
    $date_output = $_POST['date_output'];

    // Вставка даних в таблицю Output_from_main
    $sql = "INSERT INTO Output_from_main (id_med_output, id_dep_out, count, date_output)
            VALUES ('$id_med_output', '$id_dep_out', '$count', '$date_output')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../pages/home.php");
    } else {

//        echo "Помилка: " . $sql . "<br>" . $conn->error;
        echo "<script>
                alert('Помилка: " . $conn->error . "');
                window.location.href = '../pages/transfer_to_department.php';
              </script>";
    }
//        header("Location: ../pages/transfer_to_department.php");
    // Закриття з'єднання з базою даних
    $conn->close();
}
?>

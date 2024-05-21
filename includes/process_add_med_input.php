<?php
include_once "../database/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["id_med_input"], $_POST['count'], $_POST['date_input'], $_POST['id_med_post'])) {
        $id_med_input = intval($_POST['id_med_input']);
        $count = intval($_POST['count']);
        $date_input = $conn->real_escape_string($_POST['date_input']);
        $id_med_post = intval($_POST['id_med_post']);
        if(!empty($id_med_input) && !empty($count) && !empty($date_input) && !empty($id_med_post)) {
            // Вставка нового запису в таблицю Input_to_main
            $insert_med_input_sql = "
                INSERT INTO Input_to_main (id_med_input, count, date_input, id_med_post)
                VALUES ($id_med_input, $count, '$date_input', $id_med_post)
            ";

            if ($conn->query($insert_med_input_sql) === TRUE) {
                header("Location: ../pages/home.php");
            } else {
                echo "Помилка при додаванні медикаменту: " . $conn->error;
            }

            // Закриття з'єднання з базою даних
            $conn->close();
        }else{
            echo "<script>
                    alert('Помилка: Одне або декілька значень надійшли порожніми. Перевірте дані, які ви вводите');
                    window.location.href = '../pages/home.php';
                  </script>";
        }
    }else{
       echo "<script>
                    alert('Помилка: Не всі дані надійшли.');
                    window.location.href = '../pages/home.php';
                  </script>";
    }
} else {
    echo "<script>
                    alert('Помилка: Неправильний метод запиту.');
                    window.location.href = '../pages/home.php';
                  </script>";
}
?>

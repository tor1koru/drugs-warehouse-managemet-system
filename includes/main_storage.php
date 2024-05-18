<?php
require_once "../database/db_connection.php";

// Функція для оновлення або вставки записів в таблицю main_storage
function updateMainStorage($conn) {
    // Початок транзакції
    $conn->begin_transaction();

    try {
        // Вибірка даних з таблиці medicines
        $medicines_sql = "SELECT * FROM medicines";
        $medicines_result = $conn->query($medicines_sql);

        // Перевірка наявності даних
        if ($medicines_result->num_rows > 0) {
            while ($medicine = $medicines_result->fetch_assoc()) {
                $id_med = $medicine['id_med'];
                $name_med = $medicine['name_med'];
                $med_form = $medicine['med_form'];
                $dosage = $medicine['dosage'];
                $producer = $medicine['producer'];

                // Підрахунок загальної кількості медикаментів, що надійшли
                $input_sql = "SELECT SUM(count) AS total_input FROM input_to_main WHERE id_med_input = $id_med";
                $input_result = $conn->query($input_sql);
                $input_count = $input_result->fetch_assoc()['total_input'] ?? 0;

                // Підрахунок загальної кількості медикаментів, що були видані
                $output_sql = "SELECT SUM(count) AS total_output FROM output_from_main WHERE id_med_output = $id_med";
                $output_result = $conn->query($output_sql);
                $output_count = $output_result->fetch_assoc()['total_output'] ?? 0;

                // Підрахунок залишку медикаментів
                $count_store = $input_count - $output_count;

                // Перевірка, що залишок не є негативним
                if ($count_store < 0) {
                    throw new Exception("Недостатньо медикаментів на складі для медикаменту ID: $id_med");
                }

                // Отримання останньої дати оновлення
                $last_update_sql = "SELECT MAX(date) AS last_update FROM (
                    SELECT date_input AS date FROM input_to_main WHERE id_med_input = $id_med
                    UNION
                    SELECT date_output AS date FROM output_from_main WHERE id_med_output = $id_med
                ) AS updates";
                $last_update_result = $conn->query($last_update_sql);
                $data_store = $last_update_result->fetch_assoc()['last_update'] ?? null;

                // Перевірка наявності запису в main_storage
                $check_sql = "SELECT * FROM main_storage WHERE id_med_main_storage = $id_med";
                $check_result = $conn->query($check_sql);

                if ($check_result->num_rows > 0) {
                    // Оновлення запису
                    $update_sql = "UPDATE main_storage SET
                        count_store = $count_store,
                        data_store = '$data_store'
                        WHERE id_med_main_storage = $id_med";
                    $conn->query($update_sql);
                } else {
                    // Вставка нового запису
                    $insert_sql = "INSERT INTO main_storage (id_med_main_storage, name_med, med_form, dosage, producer, count_store, data_store) VALUES (
                        $id_med, '$name_med', '$med_form', '$dosage', '$producer', $count_store, '$data_store')";
                    $conn->query($insert_sql);
                }
            }
        }

        // Фіксація транзакції
        $conn->commit();
    } catch (Exception $e) {
        // Відкат транзакції у разі помилки
        $conn->rollback();
        echo "Помилка: " . $e->getMessage();
    }
}

// Виклик функції для оновлення або вставки записів
updateMainStorage($conn);

// Закриття з'єднання з базою даних
//$conn->close();
?>


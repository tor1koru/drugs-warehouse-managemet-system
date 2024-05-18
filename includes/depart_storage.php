<?php
//require_once "../database/db_connection.php";
//
//// Функція для оновлення або вставки записів в таблицю department_storage
//function updateDepartmentStorage($conn) {
//    // Початок транзакції
//    $conn->begin_transaction();
//
//    try {
//        // Вибірка даних з таблиці medicines
//        $medicines_sql = "SELECT * FROM medicines";
//        $medicines_result = $conn->query($medicines_sql);
//
//        // Перевірка наявності даних
//        if ($medicines_result->num_rows > 0) {
//            while ($medicine = $medicines_result->fetch_assoc()) {
//                $id_med = $medicine['id_med'];
//                $name_med = $medicine['name_med'];
//                $med_form = $medicine['med_form'];
//                $dosage = $medicine['dosage'];
//                $producer = $medicine['producer'];
//
//                // Вибірка всіх департаментів
//                $departments_sql = "SELECT * FROM department";
//                $departments_result = $conn->query($departments_sql);
//
//                if ($departments_result->num_rows > 0) {
//                    while ($department = $departments_result->fetch_assoc()) {
//                        $id_dep = $department['id_dep'];
//
//                        // Підрахунок загальної кількості медикаментів, що були видані до департаменту
//                        $output_main_sql = "SELECT SUM(count) AS total_output_main FROM output_from_main WHERE id_med_output = $id_med AND id_dep_out = $id_dep";
//                        $output_main_result = $conn->query($output_main_sql);
//                        $total_output_main = $output_main_result->fetch_assoc()['total_output_main'] ?? 0;
//
//                        // Підрахунок загальної кількості медикаментів, що були видані клієнтам з департаменту
//                        $output_client_sql = "SELECT SUM(oc.count_to_person) AS total_output_client FROM output_to_client oc
//                                              JOIN staff s ON oc.id_staff_client = s.id_staff
//                                              WHERE oc.id_med_client = $id_med AND s.id_dep = $id_dep";
//                        $output_client_result = $conn->query($output_client_sql);
//                        $total_output_client = $output_client_result->fetch_assoc()['total_output_client'] ?? 0;
//
//                        // Підрахунок залишку медикаментів у департаменті
//                        $count_dep = $total_output_main - $total_output_client;
//
//                        // Перевірка, що залишок не є негативним
//                        if ($count_dep < 0) {
//                            throw new Exception("Недостатньо медикаментів у департаменті ID: $id_dep для медикаменту ID: $id_med");
//                        }
//
//                        // Отримання останньої дати оновлення
//                        $last_update_sql = "SELECT MAX(date) AS last_update FROM (
//                            SELECT date_output AS date FROM output_from_main WHERE id_med_output = $id_med AND id_dep_out = $id_dep
//                            UNION
//                            SELECT date_output_to_client AS date FROM output_to_client oc
//                            JOIN staff s ON oc.id_staff_client = s.id_staff
//                            WHERE oc.id_med_client = $id_med AND s.id_dep = $id_dep
//                        ) AS updates";
//                        $last_update_result = $conn->query($last_update_sql);
//                        $date_dep = $last_update_result->fetch_assoc()['last_update'] ?? null;
//
//                        // Перевірка наявності запису в department_storage
//                        $check_sql = "SELECT * FROM department_storage WHERE id_med_dep_storage = $id_med AND id_department = $id_dep";
//                        $check_result = $conn->query($check_sql);
//
//                        if ($check_result->num_rows > 0) {
//                            // Оновлення запису
//                            $update_sql = "UPDATE department_storage SET
//                                count_dep = $count_dep,
//                                date_dep = '$date_dep'
//                                WHERE id_med_dep_storage = $id_med AND id_department = $id_dep";
//                            $conn->query($update_sql);
//                        } else {
//                            // Вставка нового запису
//                            $insert_sql = "INSERT INTO department_storage (id_med_dep_storage, id_department, name_med, med_form, dosage, producer, count_dep, date_dep) VALUES (
//                                $id_med, $id_dep, '$name_med', '$med_form', '$dosage', '$producer', $count_dep, '$date_dep')";
//                            $conn->query($insert_sql);
//                        }
//                    }
//                }
//            }
//        }
//
//        // Фіксація транзакції
//        $conn->commit();
//    } catch (Exception $e) {
//        // Відкат транзакції у разі помилки
//        $conn->rollback();
//        echo "Помилка: " . $e->getMessage();
//    }
//}
//
//// Виклик функції для оновлення або вставки записів
//updateDepartmentStorage($conn);
//
//// Закриття з'єднання з базою даних
////$conn->close();
//?>
<?php
require_once "../database/db_connection.php";

// Функція для оновлення або вставки записів в таблицю department_storage
function updateDepartmentStorage($conn) {
    // Початок транзакції
    $conn->begin_transaction();

    try {
        // Очищення таблиці Department_storage
        if (!$conn->query("TRUNCATE TABLE Department_storage")) {
            throw new Exception("Помилка очищення таблиці: " . $conn->error);
        }

        // Запит для отримання даних
        $sql = "
            SELECT
                m.id_med,
                os.id_dep_out AS id_department,
                m.name_med,
                m.med_form,
                m.dosage,
                m.producer,
                SUM(os.count) AS count_dep,
                MAX(os.date_output) AS date_dep
            FROM
                output_from_main os
            INNER JOIN
                medicines m ON os.id_med_output = m.id_med
            GROUP BY
                os.id_dep_out, m.id_med
            UNION ALL
            SELECT
                m.id_med,
                st.id_dep AS id_department,
                m.name_med,
                m.med_form,
                m.dosage,
                m.producer,
                -SUM(ot.count_to_person) AS count_dep,
                MAX(ot.date_output_to_client) AS date_dep
            FROM
                output_to_client ot
            INNER JOIN
                medicines m ON ot.id_med_client = m.id_med
            INNER JOIN
                staff st ON ot.id_staff_client = st.id_staff
            GROUP BY
                st.id_dep, m.id_med
        ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $medicines = array();

            while ($row = $result->fetch_assoc()) {
                $id_department = $row['id_department'];
                $id_med = $row['id_med'];
                $key = $id_department . '-' . $id_med;

                if (!isset($medicines[$key])) {
                    $medicines[$key] = array(
                        'id_department' => $row['id_department'],
                        'name_med' => $row['name_med'],
                        'med_form' => $row['med_form'],
                        'dosage' => $row['dosage'],
                        'producer' => $row['producer'],
                        'count_dep' => 0,
                        'date_dep' => '0000-00-00'
                    );
                }

                $medicines[$key]['count_dep'] += $row['count_dep'];
                if ($row['date_dep'] > $medicines[$key]['date_dep']) {
                    $medicines[$key]['date_dep'] = $row['date_dep'];
                }
            }

            foreach ($medicines as $medicine) {
                $id_department = $medicine['id_department'];
                $name_med = $medicine['name_med'];
                $med_form = $medicine['med_form'];
                $dosage = $medicine['dosage'];
                $producer = $medicine['producer'];
                $count_dep = $medicine['count_dep'];
                $date_dep = $medicine['date_dep'];

                if ($count_dep < 0) {
                    throw new Exception("Недостатньо медикаментів у департаменті ID: $id_department для медикаменту ID: $id_med");
                }

                $insert_sql = "
                    INSERT INTO department_storage (id_department, name_med, med_form, dosage, producer, count_dep, date_dep)
                    VALUES ($id_department, '$name_med', '$med_form', '$dosage', '$producer', $count_dep, '$date_dep')
                ";
                if (!$conn->query($insert_sql)) {
                    throw new Exception("Помилка вставки даних: " . $conn->error);
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

// Виклик функції оновлення department_storage
updateDepartmentStorage($conn);

// Закриття з'єднання з базою даних
//$conn->close();
?>


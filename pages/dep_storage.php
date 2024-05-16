<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Склад відділу</title>
</head>
<body>
<h1>Склад відділу</h1>

<?php
include_once "../database/db_connection.php";

// Функція для оновлення даних в таблиці Department_storage
// Функція для оновлення даних в таблиці Department_storage
function updateDepartmentStorage($conn) {
    // Очищаємо Department_storage перед оновленням
    if (!$conn->query("TRUNCATE TABLE Department_storage")) {
        die("Помилка очищення таблиці: " . $conn->error);
    }

    // SQL-запит для оновлення даних в Department_storage
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
            Output_from_main os
        INNER JOIN 
            Medicines m ON os.id_med_output = m.id_med
        GROUP BY 
            os.id_dep_out, m.id_med
        UNION ALL
        SELECT 
            m.id_med,
            st.id_dep,
            m.name_med,
            m.med_form,
            m.dosage,
            m.producer,
            -SUM(ot.count_to_person) AS count_dep,
            MAX(ot.date_output_to_client) AS date_dep
        FROM 
            Output_to_client ot
        INNER JOIN 
            Medicines m ON ot.id_med_client = m.id_med
        INNER JOIN 
            Staff st ON ot.id_staff_client = st.id_staff
        GROUP BY 
            st.id_dep, m.id_med
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $medicines = array(); // Асоціативний масив для збереження даних про медикаменти

        // Заповнюємо асоціативний масив
        while ($row = $result->fetch_assoc()) {
            $id_med = $row['id_med'];
            if (!isset($medicines[$id_med])) {
                $medicines[$id_med] = array(
                    'id_department' => $row['id_department'],
                    'name_med' => $row['name_med'],
                    'med_form' => $row['med_form'],
                    'dosage' => $row['dosage'],
                    'producer' => $row['producer'],
                    'count_dep' => 0,
                    'date_dep' => '0000-00-00'
                );
            }

            $medicines[$id_med]['count_dep'] += $row['count_dep'];
            if ($row['date_dep'] > $medicines[$id_med]['date_dep']) {
                $medicines[$id_med]['date_dep'] = $row['date_dep'];
            }
        }

        // Вставляємо дані в Department_storage
        foreach ($medicines as $medicine) {
            $id_department = $medicine['id_department'];
            $name_med = $medicine['name_med'];
            $med_form = $medicine['med_form'];
            $dosage = $medicine['dosage'];
            $producer = $medicine['producer'];
            $count_dep = $medicine['count_dep'];
            $date_dep = $medicine['date_dep'];

            $insert_sql = "
                INSERT INTO Department_storage (id_department, name_med, med_form, dosage, producer, count_dep, date_dep)
                VALUES ($id_department, '$name_med', '$med_form', '$dosage', '$producer', $count_dep, '$date_dep')
            ";
            if (!$conn->query($insert_sql)) {
                die("Помилка вставки даних: " . $conn->error);
            }
        }
    }
}




// Виклик функції оновлення Department_storage
updateDepartmentStorage($conn);

// SQL запит для вибору всіх даних з таблиці Department_storage

$id_dep = isset($_GET['id_dep']) ? intval($_GET['id_dep']) : 0;

if ($id_dep > 0) {
    // SQL запит для вибору даних з Department_storage для конкретного відділу
    $sql = "
        SELECT 
            name_med, 
            med_form, 
            dosage, 
            producer, 
            count_dep, 
            date_dep 
        FROM 
            Department_storage 
        WHERE 
            id_department = $id_dep
    ";
    $result = $conn->query($sql);

    // Виведення даних, якщо є результат
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Назва медикаменту</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Кількість на складі</th><th>Дата оновлення</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name_med"] . "</td>";
            echo "<td>" . $row["med_form"] . "</td>";
            echo "<td>" . $row["dosage"] . "</td>";
            echo "<td>" . $row["producer"] . "</td>";
            echo "<td>" . $row["count_dep"] . "</td>";
            echo "<td>" . $row["date_dep"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Немає даних для цього відділу";
    }
} else {
    echo "Некоректний ID відділу";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

</body>
</html>

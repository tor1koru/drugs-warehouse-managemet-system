<?php require_once "../includes/header.php" ?>
<h1>Склад відділу</h1>
    <form method="GET" action="">
        <label for="search_med">Пошук за назвою медикаменту:</label>
        <input type="text" id="search_med" name="search_med">
        <button type="submit">Пошук</button>
        <?php if (isset($_GET['id_dep'])): ?>
            <input type="hidden" name="id_dep" value="<?= htmlspecialchars($_GET['id_dep']) ?>">
        <?php endif; ?>
    </form>
<?php
include_once "../database/db_connection.php";
require_once "../includes/depart_storage.php";
global $conn;
// Функція для оновлення даних в таблиці Department_storage
//function updateDepartmentStorage($conn) {
//    if (!$conn->query("TRUNCATE TABLE Department_storage")) {
//        die("Помилка очищення таблиці: " . $conn->error);
//    }
//
//    $sql = "
//        SELECT
//            m.id_med,
//            os.id_dep_out AS id_department,
//            m.name_med,
//            m.med_form,
//            m.dosage,
//            m.producer,
//            SUM(os.count) AS count_dep,
//            MAX(os.date_output) AS date_dep
//        FROM
//            Output_from_main os
//        INNER JOIN
//            Medicines m ON os.id_med_output = m.id_med
//        GROUP BY
//            os.id_dep_out, m.id_med
//        UNION ALL
//        SELECT
//            m.id_med,
//            st.id_dep AS id_department,
//            m.name_med,
//            m.med_form,
//            m.dosage,
//            m.producer,
//            -SUM(ot.count_to_person) AS count_dep,
//            MAX(ot.date_output_to_client) AS date_dep
//        FROM
//            Output_to_client ot
//        INNER JOIN
//            Medicines m ON ot.id_med_client = m.id_med
//        INNER JOIN
//            Staff st ON ot.id_staff_client = st.id_staff
//        GROUP BY
//            st.id_dep, m.id_med
//    ";
//
//    $result = $conn->query($sql);
//
//    if ($result->num_rows > 0) {
//        $medicines = array();
//
//        while ($row = $result->fetch_assoc()) {
//            $id_department = $row['id_department'];
//            $id_med = $row['id_med'];
//            $key = $id_department . '-' . $id_med;
//
//            if (!isset($medicines[$key])) {
//                $medicines[$key] = array(
//                    'id_department' => $row['id_department'],
//                    'name_med' => $row['name_med'],
//                    'med_form' => $row['med_form'],
//                    'dosage' => $row['dosage'],
//                    'producer' => $row['producer'],
//                    'count_dep' => 0,
//                    'date_dep' => '0000-00-00'
//                );
//            }
//
//            $medicines[$key]['count_dep'] += $row['count_dep'];
//            if ($row['date_dep'] > $medicines[$key]['date_dep']) {
//                $medicines[$key]['date_dep'] = $row['date_dep'];
//            }
//        }
//
//        foreach ($medicines as $medicine) {
//            $id_department = $medicine['id_department'];
//            $name_med = $medicine['name_med'];
//            $med_form = $medicine['med_form'];
//            $dosage = $medicine['dosage'];
//            $producer = $medicine['producer'];
//            $count_dep = $medicine['count_dep'];
//            $date_dep = $medicine['date_dep'];
//
//            $insert_sql = "
//                INSERT INTO Department_storage (id_department, name_med, med_form, dosage, producer, count_dep, date_dep)
//                VALUES ($id_department, '$name_med', '$med_form', '$dosage', '$producer', $count_dep, '$date_dep')
//            ";
//            if (!$conn->query($insert_sql)) {
//                die("Помилка вставки даних: " . $conn->error);
//            }
//        }
//    }
//}
//
//// Виклик функції оновлення Department_storage
//updateDepartmentStorage($conn);

// Отримуємо ID відділу з GET параметра
$id_dep = isset($_GET['id_dep']) ? intval($_GET['id_dep']) : 0;

// Отримуємо назву медикаменту з GET параметра
$search_med = isset($_GET['search_med']) ? $conn->real_escape_string($_GET['search_med']) : '';

// SQL запит для вибору даних з таблиці Department_storage з можливістю пошуку за назвою медикаменту
$sql = "SELECT * FROM Department_storage WHERE id_department = $id_dep";
if (!empty($search_med)) {
    $sql .= " AND name_med LIKE '%$search_med%'";
}
$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID відділу</th><th>Назва медикаменту</th><th>Форма</th><th>Дозування</th><th>Виробник</th><th>Кількість на складі</th><th>Дата оновлення</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_department"] . "</td>";
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
    echo "Немає даних для вибраного відділу";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

    <button onclick="toggleForm()">Видати ліки</button>

    <div id="transferForm" style="display: none;">
        <h1>Передача медикаменту клієнту</h1>
        <form method="post" action="process_transfer_med_to_client.php">
            <h2>Виберіть медикамент, особу та співробітника</h2>

            Медикамент:
            <input type="text" id="medicineInput" name="medicineInput">
            <input type="hidden" id="id_med_client" name="id_med_client">
            <div id="autocomplete-medicine-list" class="autocomplete-suggestions"></div><br><br>

            Особа:
            <input type="text" id="personInput" name="personInput">
            <input type="hidden" id="id_person" name="id_person">
            <div id="autocomplete-person-list" class="autocomplete-suggestions"></div><br><br>

            Співробітник:
            <input type="text" id="staffInput" name="staffInput">
            <input type="hidden" id="id_staff_client" name="id_staff_client">
            <div id="autocomplete-staff-list" class="autocomplete-suggestions"></div><br><br>

            Кількість: <input type="number" name="count_to_person"><br><br>
            Дата: <input type="date" name="date_output_to_client"><br><br>
            <input type="submit" value="Передати медикамент клієнту">
        </form>
    </div>

    <script>
        function toggleForm() {
            const form = document.getElementById('transferForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        document.getElementById('medicineInput').addEventListener('input', function() {
            const query = this.value;

            if (query.length >= 2) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '../includes/get_medicines.php?query=' + query, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        const medicines = JSON.parse(this.responseText);
                        const autocompleteList = document.getElementById('autocomplete-medicine-list');
                        autocompleteList.innerHTML = '';

                        medicines.forEach(medicine => {
                            const item = document.createElement('div');
                            item.classList.add('autocomplete-suggestion');
                            item.textContent = `${medicine.name_med} ${medicine.med_form} ${medicine.dosage} ${medicine.producer}`;
                            item.dataset.id = medicine.id_med;
                            item.addEventListener('click', function() {
                                document.getElementById('medicineInput').value = this.textContent;
                                document.getElementById('id_med_client').value = this.dataset.id;
                                autocompleteList.innerHTML = '';
                            });
                            autocompleteList.appendChild(item);
                        });
                    }
                }
                xhr.send();
            } else {
                document.getElementById('autocomplete-medicine-list').innerHTML = '';
            }
        });

        document.getElementById('personInput').addEventListener('input', function() {
            const query = this.value;

            if (query.length >= 2) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_persons.php?query=' + query, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        const persons = JSON.parse(this.responseText);
                        const autocompleteList = document.getElementById('autocomplete-person-list');
                        autocompleteList.innerHTML = '';

                        persons.forEach(person => {
                            const item = document.createElement('div');
                            item.classList.add('autocomplete-suggestion');
                            item.textContent = person.full_name;
                            item.dataset.id = person.id_person;
                            item.addEventListener('click', function() {
                                document.getElementById('personInput').value = this.textContent;
                                document.getElementById('id_person').value = this.dataset.id;
                                autocompleteList.innerHTML = '';
                            });
                            autocompleteList.appendChild(item);
                        });
                    }
                }
                xhr.send();
            } else {
                document.getElementById('autocomplete-person-list').innerHTML = '';
            }
        });

        document.getElementById('staffInput').addEventListener('input', function() {
            const query = this.value;

            if (query.length >= 2) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_staff.php?query=' + query, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        const staff = JSON.parse(this.responseText);
                        const autocompleteList = document.getElementById('autocomplete-staff-list');
                        autocompleteList.innerHTML = '';

                        staff.forEach(person => {
                            const item = document.createElement('div');
                            item.classList.add('autocomplete-suggestion');
                            item.textContent = `${person.full_name} ${person.position} ${person.name_dep}`;
                            item.dataset.id = person.id_staff;
                            item.addEventListener('click', function() {
                                document.getElementById('staffInput').value = this.textContent;
                                document.getElementById('id_staff_client').value = this.dataset.id;
                                autocompleteList.innerHTML = '';
                            });
                            autocompleteList.appendChild(item);
                        });
                    }
                }
                xhr.send();
            } else {
                document.getElementById('autocomplete-staff-list').innerHTML = '';
            }
        });
    </script>

<?php require_once "../includes/footer.php" ?>
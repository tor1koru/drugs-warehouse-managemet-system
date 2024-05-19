<?php require_once "../includes/header.php" ?>
<h1>Список відділів</h1>

<!-- Форма для пошуку -->
<form method="get" action="">
    <label for="search">Пошук за назвою:</label>
    <input type="text" id="search" name="search">
    <button type="submit">Пошук</button>
</form>

<?php
include_once "../database/db_connection.php";

// Пошук за назвою
if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql = "SELECT * FROM Department WHERE name_dep LIKE '%$search_term%'";
} else {
    $sql = "SELECT * FROM Department";
}

$result = $conn->query($sql);

// Виведення даних, якщо є результат
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Назва Відділу</th><th>Склад відділу</th><th>Персонал</th><th>Дії</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_dep"] . "</td>";
        echo "<td>" . $row["name_dep"] . "</td>";
        echo "<td><a href='dep_storage.php?id_dep=" . $row["id_dep"] . "'>Склад відділу</a></td>";
        echo "<td><a href='staf_dep.php?id_dep=" . $row["id_dep"] . "'>Персонал відділу</a></td>";
        echo "<td><a href='edit_dep.php?id=" . $row["id_dep"] . "'>Редагувати</a> | <a href='../includes/delete_dep.php?id=" . $row["id_dep"] . "'>Видалити</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Немає даних";
}

// Закриття з'єднання з базою даних
$conn->close();
?>

<!-- Кнопки для показу форм -->
<button id="toggleDepFormBtn">Додати новий відділ</button>
<button id="toggleStaffFormBtn">Додати персонал до відділу</button>

<!-- Прихована форма для додавання нового відділу -->
<div id="addDepForm" style="display: none;">
    <h2>Додати новий відділ</h2>
    <form method="post" action="../includes/add_dep.php">
        <label for="name_dep">Назва відділу:</label>
        <input type="text" id="name_dep" name="name_dep"><br><br>
        <input type="submit" value="Додати відділ">
    </form>
</div>

<!-- Прихована форма для додавання нового персоналу -->
<div id="addStaffForm" style="display: none;">
    <h2>Додати новий персонал</h2>
    <form method="post" action="../includes/process_add_staff.php">
        <label for="departmentInput">Відділ:</label>
        <input type="text" id="departmentInput" name="departmentInput">
        <input type="hidden" id="id_dep" name="id_dep">
        <div id="autocomplete-department-list" class="autocomplete-suggestions"></div><br><br>

        <label for="personInput">Особа:</label>
        <input type="text" id="personInput" name="personInput">
        <input type="hidden" id="id_person" name="id_person">
        <div id="autocomplete-person-list" class="autocomplete-suggestions"></div><br><br>

        <label for="position">Посада:</label>
        <input type="text" id="position" name="position"><br><br>
        <label for="login">Логін:</label>
        <input type="text" id="login" name="login"><br><br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Додати персонал">
    </form>
</div>

<script>
    // JavaScript для переключення видимості форми додавання відділу
    document.getElementById("toggleDepFormBtn").addEventListener("click", function() {
        var form = document.getElementById("addDepForm");
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });

    // JavaScript для переключення видимості форми додавання персоналу
    document.getElementById("toggleStaffFormBtn").addEventListener("click", function() {
        var form = document.getElementById("addStaffForm");
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });

    document.getElementById('departmentInput').addEventListener('input', function() {
        const query = this.value;

        if (query.length >= 2) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../includes/get_departments.php?query=' + query, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const departments = JSON.parse(this.responseText);
                    const autocompleteList = document.getElementById('autocomplete-department-list');
                    autocompleteList.innerHTML = '';

                    departments.forEach(department => {
                        const item = document.createElement('div');
                        item.classList.add('autocomplete-suggestion');
                        item.textContent = department.name_dep;
                        item.dataset.id = department.id_dep;
                        item.addEventListener('click', function() {
                            document.getElementById('departmentInput').value = this.textContent;
                            document.getElementById('id_dep').value = this.dataset.id;
                            autocompleteList.innerHTML = '';
                        });
                        autocompleteList.appendChild(item);
                    });
                }
            }
            xhr.send();
        } else {
            document.getElementById('autocomplete-department-list').innerHTML = '';
        }
    });

    document.getElementById('personInput').addEventListener('input', function() {
        const query = this.value;

        if (query.length >= 2) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../includes/get_persons.php?query=' + query, true);
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
</script>

<?php require_once "../includes/footer.php" ?>

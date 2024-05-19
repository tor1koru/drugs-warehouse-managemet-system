<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати новий персонал</title>
    <style>
        .autocomplete-suggestions {
            border: 1px solid #e0e0e0;
            max-height: 150px;
            overflow-y: auto;
            background: #fff;
        }
        .autocomplete-suggestion {
            padding: 8px;
            cursor: pointer;
        }
        .autocomplete-suggestion:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
<h1>Додати новий персонал</h1>

<h1>Додати новий персонал</h1>

<form method="post" action="../includes/process_add_staff.php">
    <h2>Виберіть відділ та особу</h2>

    Відділ:
    <input type="text" id="departmentInput" name="departmentInput">
    <input type="hidden" id="id_dep" name="id_dep">
    <div id="autocomplete-department-list" class="autocomplete-suggestions"></div><br><br>

    Особа:
    <input type="text" id="personInput" name="personInput">
    <input type="hidden" id="id_person" name="id_person">
    <div id="autocomplete-person-list" class="autocomplete-suggestions"></div><br><br>

    Посада: <input type="text" name="position"><br><br>
    Логін: <input type="text" name="login"><br><br>
    Пароль: <input type="password" name="password"><br><br>
    <input type="submit" value="Додати персонал">
</form>


<script>
    document.getElementById('departmentInput').addEventListener('input', function() {
        const query = this.value;

        if (query.length >= 2) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_departments.php?query=' + query, true);
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
</script>

</body>
</html>

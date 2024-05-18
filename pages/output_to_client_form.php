<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Передача медикаменту клієнту</title>
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

<script>
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

</body>
</html>

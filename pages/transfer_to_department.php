<?php require_once "../includes/header.php"; ?>
<h1>Передача медикаменту на відділення</h1>

<form method="post" action="../includes/process_transfer_med_to_department.php">
    <h2>Виберіть медикамент та відділення</h2>

    Медикамент:
    <input type="text" id="medicineInput" name="medicineInput" required>
    <input type="hidden" id="id_med_output" name="id_med_output">
    <div id="autocomplete-medicine-list" class="autocomplete-suggestions"></div><br><br>

    Відділення:
    <input type="text" id="departmentInput" name="departmentInput" required>
    <input type="hidden" id="id_dep_out" name="id_dep_out">
    <div id="autocomplete-department-list" class="autocomplete-suggestions"></div><br><br>

    Кількість: <input type="number" name="count" min="1" required><br><br>
    Дата: <input type="date" name="date_output" required><br><br>
    <input type="submit" value="Передати медикамент на відділення">
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
                            document.getElementById('id_med_output').value = this.dataset.id;
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
                            document.getElementById('id_dep_out').value = this.dataset.id;
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
</script>
<?php require_once "../includes/footer.php" ?>

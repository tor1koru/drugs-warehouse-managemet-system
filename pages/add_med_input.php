<?php require_once "../includes/header.php"; ?>
<h1>Додати новий медикамент на склад</h1>

<form method="post" action="../includes/process_add_med_input.php">
    <h2>Виберіть медикамент та постачальника</h2>

    Медикамент:
    <input type="text" id="medicineInput" name="medicineInput">
    <input type="hidden" id="id_med_input" name="id_med_input">
    <div id="autocomplete-medicine-list" class="autocomplete-suggestions"></div><br><br>

    Постачальник:
    <input type="text" id="providerInput" name="providerInput">
    <input type="hidden" id="id_med_post" name="id_med_post">
    <div id="autocomplete-provider-list" class="autocomplete-suggestions"></div><br><br>

    Кількість: <input type="number" name="count"><br><br>
    Дата: <input type="date" name="date_input"><br><br>
    <input type="submit" value="Додати медикамент">
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
                            document.getElementById('id_med_input').value = this.dataset.id;
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

    document.getElementById('providerInput').addEventListener('input', function() {
        const query = this.value;

        if (query.length >= 2) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../includes/get_providers.php?query=' + query, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const providers = JSON.parse(this.responseText);
                    const autocompleteList = document.getElementById('autocomplete-provider-list');
                    autocompleteList.innerHTML = '';

                    providers.forEach(provider => {
                        const item = document.createElement('div');
                        item.classList.add('autocomplete-suggestion');
                        item.textContent = provider.name_provider;
                        item.dataset.id = provider.id_provider;
                        item.addEventListener('click', function() {
                            document.getElementById('providerInput').value = this.textContent;
                            document.getElementById('id_med_post').value = this.dataset.id;
                            autocompleteList.innerHTML = '';
                        });
                        autocompleteList.appendChild(item);
                    });
                }
            }
            xhr.send();
        } else {
            document.getElementById('autocomplete-provider-list').innerHTML = '';
        }
    });
</script>
<?php require_once "../includes/footer.php"; ?>

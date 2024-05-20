<?php
// Підключення до бази даних та інші необхідні файли
require_once "../includes/header.php";
include_once "../database/db_connection.php";

// Перевірка, чи був переданий параметр ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Запит на вибірку даних про видачу медикаментів клієнтам за ID
    $sql = "SELECT * FROM Output_to_client WHERE id_output_client = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>
        <h2>Редагувати видачу медикаменту клієнтам</h2>
        <form action="update_output_client.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_output_client']); ?>">
            <label for="count_to_person">Кількість:</label>
            <input type="number" id="count_to_person" name="count_to_person" value="<?php echo htmlspecialchars($row['count_to_person']); ?>" required min="0"><br><br>
            <label for="date_output_to_client">Дата видачі:</label>
            <input type="date" id="date_output_to_client" name="date_output_to_client" value="<?php echo htmlspecialchars($row['date_output_to_client']); ?>" required><br><br>
            <input type="submit" value="Зберегти зміни">
        </form>
        <?php
    } else {
        echo "Запис з ID $id не знайдено.";
    }
} else {
    echo "Не вказаний параметр ID.";
}
$conn->close();
?>
<?php require_once "../includes/footer.php" ?>

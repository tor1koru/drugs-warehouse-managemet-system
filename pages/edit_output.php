<?php
// Підключення до бази даних та інші необхідні файли
require_once "../includes/header.php";
include_once "../database/db_connection.php";

// Перевірка, чи був переданий параметр ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Запит на вибірку даних про видачу медикаментів за ID
    $sql = "SELECT * FROM Output_from_main WHERE id_output = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>
        <h2>Редагувати видачу медикаменту</h2>
        <form action="update_output.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_output']); ?>">
            <label for="count">Кількість:</label>
            <input type="number" id="count" name="count" value="<?php echo htmlspecialchars($row['count']); ?>" required min="0"><br><br>
            <label for="date_output">Дата видачі:</label>
            <input type="date" id="date_output" name="date_output" value="<?php echo htmlspecialchars($row['date_output']); ?>" required><br><br>
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

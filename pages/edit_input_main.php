<?php
// Підключення до бази даних та інші необхідні файли
require_once "../includes/header.php";
include_once "../database/db_connection.php";

// Перевірка, чи був переданий параметр ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Запит на вибірку даних про видачу медикаментів основному складу за ID
    $sql = "SELECT * FROM Input_to_main WHERE id_input_to_main = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>
        <h2>Редагувати видачу медикаменту основному складу</h2>
        <form action="update_input_main.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_input_to_main']); ?>">
            <label for="count">Кількість:</label>
            <input type="number" id="count" name="count" value="<?php echo htmlspecialchars($row['count']); ?>" required min="0"><br><br>
            <label for="date_input">Дата видачі:</label>
            <input type="date" id="date_input" name="date_input" value="<?php echo htmlspecialchars($row['date_input']); ?>" required><br><br>
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

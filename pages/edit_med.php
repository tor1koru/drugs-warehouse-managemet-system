<?php require_once "../includes/header.php" ?>
<h1 class="main__title">Редагувати Медикамент</h1>

<?php
include_once "../database/db_connection.php";

// Перевірка, чи передано id медикаменту
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL-запит для вибору медикаменту за його id
    $sql = "SELECT * FROM Medicines WHERE id_med=$id";
    $result = $conn->query($sql);

    // Перевірка, чи є результат
    if ($result->num_rows > 0) {
        $medicine = $result->fetch_assoc();
        ?>
        <form method="post" action="../includes/update_med.php">
            <input type="hidden" name="id" value="<?php echo $medicine['id_med']; ?>">
            Назва: <input type="text" name="name_med" value="<?php echo $medicine['name_med']; ?>"><br><br>
            Форма: <input type="text" name="med_form" value="<?php echo $medicine['med_form']; ?>"><br><br>
            Дозування: <input type="text" name="dosage" value="<?php echo $medicine['dosage']; ?>"><br><br>
            Виробник: <input type="text" name="producer" value="<?php echo $medicine['producer']; ?>"><br><br>
            <input type="submit" value="Зберегти зміни">
        </form>
        <?php
    } else {
        echo "Медикамент не знайдено.";
    }
} else {
    echo "Не передано параметр id.";
}
?>

<?php require_once "../includes/footer.php" ?>

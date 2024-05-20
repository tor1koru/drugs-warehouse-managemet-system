<?php
session_start(); // починаємо сесію, щоб мати доступ до змінних сесії

// Перевіряємо, чи є користувачем з логіном "admin"
$logged_in_user = "admin"; // Логін користувача, який вже увійшов в систему
$id_dep = $_SESSION['id_dep'];
?>

<nav class="nav">
    <h1 class="nav__title">Menu</h1>
    <ul class="nav__menu-list">
        <?php
        // Перевіряємо, чи поточний користувач - "admin", якщо так, тоді показуємо посилання
        if ($_SESSION['login'] == $logged_in_user) {
            ?>
            <li class="menu-list__item"><a href="../pages/home.php" class="menu-list__link">Home</a></li>
            <li class="menu-list__item"><a href="../pages/dep.php" class="menu-list__link">Departments</a></li>
            <li class="menu-list__item"><a href="../pages/med.php" class="menu-list__link">Medicines</a></li>
            <li class="menu-list__item"><a href="../pages/providers.php" class="menu-list__link">Providers</a></li>
            <li class="menu-list__item"><a href="../pages/add_med_input.php" class="menu-list__link">Input to main storage</a></li>
            <li class="menu-list__item"><a href="../pages/transfer_to_department.php" class="menu-list__link">Output from main storage</a></li>
            <li class="menu-list__item"><a href="../pages/input_to_main_table.php" class="menu-list__link">Report on the supply of medicines to the main storage</a></li>
            <li class="menu-list__item"><a href="../pages/output_from_main_table.php" class="menu-list__link">Report on the distribution of medicines from the main storage to departments </a></li>
            <li class="menu-list__item"><a href="../pages/output_to_client_table.php" class="menu-list__link">Report on the dispensing of medicines to clients </a></li>
            <?php
        }else{
            ?>
            <li class="menu-list__item"><a href='../pages/dep_storage.php?id_dep=<?php echo $id_dep; ?>' class="menu-list__link">Storage</a></li>
        <?php }
        ?>
        <li class="menu-list__item"><a href="../pages/pers.php" class="menu-list__link next-to-last">Persons</a></li>
        <li class="menu-list__item"><a href="../includes/logout.php" class="menu-list__link last">Log out</a></li>
    </ul>
</nav>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh; /* Зробити сторінку повноекранною */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0; /* Додати фон */
        }
        form {
            width: 300px; /* Задати ширину форми */
            padding: 20px;
            background-color: #fff; /* Додати фон формі */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Додати тінь для ефекту плавності */
        }
        h2 {
            text-align: center;
        }
        input[type="text"], input[type="password"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff; /* Колір кнопки */
            color: #fff; /* Колір тексту на кнопці */
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3; /* Колір кнопки при наведенні */
        }
    </style>
</head>
<body>
<form action="../admin/admin.php" method="post">
    <h2>Login</h2>
    <div>
        <input type="text" placeholder="Enter Login" name="login">
    </div>
    <div>
        <input type="password" placeholder="Enter password" name="password">
    </div>
    <br>
    <button type="submit">Submit</button>
</form>
</body>
</html>

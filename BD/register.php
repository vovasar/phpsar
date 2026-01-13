<?php 
// Подключаем файлы
require_once('BD.php'); // Подключаем базу данных
require_once('header.php');

$errors = [];
$success = '';

// Проверяем, есть ли подключение к БД
if (!isset($mysqli)) {
    die("Ошибка: Не удалось подключиться к базе данных");
}

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные
    $user_name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Валидация
    if (empty($user_name)) {
        $errors[] = 'Имя пользователя обязательно';
    } elseif (strlen($user_name) < 3) {
        $errors[] = 'Имя пользователя должно быть не менее 3 символов';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $user_name)) {
        $errors[] = 'Имя пользователя может содержать только буквы, цифры и подчеркивания';
    }
    
    if (empty($email)) {
        $errors[] = 'Email обязателен';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат email';
    }
    
    if (empty($password)) {
        $errors[] = 'Пароль обязателен';
    } elseif (strlen($password) < 6) {ш9иу
        $errors[] = 'Пароль должен быть не менее 6 символов';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Пароли не совпадают';
    }

    // Если нет ошибок валидации
    if (empty($errors)) {
        // Проверяем, существует ли пользователь
        $check_sql = "SELECT id FROM user WHERE user_name = ? OR email = ?";
        $check_stmt = $mysqli->prepare($check_sql);
        
        if ($check_stmt) {
            $check_stmt->bind_param("ss", $user_name, $email);
            $check_stmt->execute();
            $check_stmt->store_result();
            
            if ($check_stmt->num_rows > 0) {
                $errors[] = 'Пользователь с таким именем или email уже существует';
            } else {
                // Хешируем пароль
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Вставляем нового пользователя
                $insert_sql = "INSERT INTO user (user_name, email, password) VALUES (?, ?, ?)";
                $insert_stmt = $mysqli->prepare($insert_sql);
                
                if ($insert_stmt) {
                    $insert_stmt->bind_param("sss", $user_name, $email, $hashed_password);
                    
                    if ($insert_stmt->execute()) {
                        $success = 'Регистрация прошла успешно! Теперь вы можете <a href="login.php">войти</a>.';
                        // Очищаем поля формы
                        $user_name = $email = '';
                    } else {
                        $errors[] = 'Ошибка при регистрации: ' . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                } else {
                    $errors[] = 'Ошибка подготовки запроса: ' . $mysqli->error;
                }
            }
            $check_stmt->close();
        } else {
            $errors[] = 'Ошибка подготовки запроса: ' . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        
        .error {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #d32f2f;
        }
        
        .error ul {
            margin-left: 20px;
            margin-top: 5px;
        }
        
        .success {
            color: #388e3c;
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #388e3c;
        }
        
        .success a {
            color: #388e3c;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .btn:hover {
            background: linear-gradient(135deg, #45a049 0%, #4CAF50 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 15px;
        }
        
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> Регистрация</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Ошибки:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="user_name"> Имя пользователя:</label>
                <input type="text" id="user_name" name="user_name" 
                       value="<?php echo isset($user_name) ? htmlspecialchars($user_name) : ''; ?>" 
                       placeholder="Введите имя пользователя"
                       required>
                <div class="password-requirements">Минимум 3 символа, только буквы, цифры и _</div>
            </div>
            
            <div class="form-group">
                <label for="email"> Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" 
                       placeholder="example@mail.com"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password"> Пароль:</label>
                <input type="password" id="password" name="password" 
                       placeholder="Минимум 6 символов"
                       required>
                <div class="password-requirements">Минимум 6 символов</div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password"> Подтвердите пароль:</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Повторите пароль"
                       required>
            </div>
            
            <button type="submit" class="btn"> Зарегистрироваться</button>
        </form>
        
        <div class="login-link">
            Уже есть аккаунт? <a href="login.php">Войти в систему</a>
        </div>
        
        <div class="form-footer">
            <a href="index.php" class="text-muted" style="text-decoration: none;">
                ← Вернуться на главную
            </a>
        </div>
    </div>
    
    <script>
        // Валидация пароля в реальном времени
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePasswords() {
                if (password.value && confirmPassword.value) {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.style.borderColor = '#d32f2f';
                    } else {
                        confirmPassword.style.borderColor = '#4CAF50';
                    }
                }
            }
            
            password.addEventListener('input', validatePasswords);
            confirmPassword.addEventListener('input', validatePasswords);
            
            // Автофокус на первое поле
            document.getElementById('user_name').focus();
        });
    </script>
</body>
</html>
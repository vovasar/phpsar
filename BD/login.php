<?php 
require_once('BD.php');
require_once('header.php');
// Проверяем подключение к базе данных
if (!isset($mysqli)) {
    die("<div class='alert alert-danger'>Ошибка подключения к базе данных</div>");
}

// Если пользователь уже авторизован, перенаправляем на главную
if (isset($_SESSION['user_id'])) {
    header("Location: content.php");
    exit();
}

$error = '';
$success = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Очистка и получение данных
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Валидация email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Пожалуйста, введите корректный email адрес";
    } elseif (empty($password)) {
        $error = "Пожалуйста, введите пароль";
    } else {
        // Проверяем существование таблицы
        $table_check = $mysqli->query("SHOW TABLES LIKE 'users'");
        if ($table_check->num_rows == 0) {
            $error = "Таблица пользователей не найдена. Пожалуйста, сначала зарегистрируйтесь.";
        } else {
            // Поиск пользователя в базе данных
            $sql = "SELECT id, username, email, password, role FROM users WHERE email = ?";
            $stmt = $mysqli->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    // Проверка пароля
                    if (password_verify($password, $row['password'])) {
                        // Установка сессии
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = htmlspecialchars($row['username']);
                        $_SESSION['email'] = htmlspecialchars($row['email']);
                        $_SESSION['role'] = $row['role'];
                        
                        // Запоминаем логин в cookies (опционально)
                        if (isset($_POST['remember'])) {
                            setcookie('remember_email', $email, time() + 86400 * 30, '/');
                        }
                        
                        // Перенаправление
                        header("Location: content.php");
                        exit();
                    } else {
                        $error = "Неверный пароль";
                    }
                } else {
                    $error = "Пользователь с таким email не найден";
                }
                $stmt->close();
            } else {
                $error = "Ошибка базы данных: " . $mysqli->error;
            }
        }
        $table_check->free();
    }
}

// Если есть сообщение об успешной регистрации
if (isset($_GET['registered']) && $_GET['registered'] == '1') {
    $success = "Регистрация успешна! Теперь вы можете войти в систему.";
}

// Восстановление email из cookies для "Запомнить меня"
$remember_email = isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .login-container {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .login-header h2 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }
        
        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .input-group:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .input-group-text {
            background: white;
            border: none;
            color: #6c757d;
            padding: 15px;
        }
        
        .form-control {
            border: none;
            box-shadow: none;
            padding: 15px;
            font-size: 16px;
        }
        
        .form-control:focus {
            box-shadow: none;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
            font-size: 16px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .footer-links {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-links a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .password-toggle-btn {
            background: transparent;
            border: none;
            color: #6c757d;
            padding: 15px;
            cursor: pointer;
        }
        
        .password-toggle-btn:hover {
            color: #495057;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Заголовок -->
            <div class="login-header">
                <h2><i class="fas fa-sign-in-alt"></i> Добро пожаловать!</h2>
                <p>Войдите в свой аккаунт</p>
            </div>
            
            <!-- Тело формы -->
            <div class="login-body">
                <!-- Сообщения об ошибках/успехе -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Форма входа -->
                <form method="POST" action="">
                    <input type="hidden" name="login" value="1">
                    
                    <!-- Поле Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email адрес</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="name@example.com" 
                                   value="<?php echo $remember_email; ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <!-- Поле Пароль -->
                    <div class="form-group">
                        <label for="password" class="form-label">Пароль</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Пароль" 
                                   required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Опции -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="remember" 
                                   name="remember"
                                   <?php echo $remember_email ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="remember">
                                Запомнить меня
                            </label>
                        </div>
                        <div>
                            <a href="forgot_password.php" class="text-decoration-none">
                                Забыли пароль?
                            </a>
                        </div>
                    </div>
                    
                    <!-- Кнопка входа -->
                    <button type="submit" class="btn btn-login mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Войти
                    </button>
                    
                    <!-- Ссылки -->
                    <div class="footer-links">
                        <p>Нет аккаунта? 
                            <a href="register.php" class="fw-bold">Зарегистрируйтесь</a>
                        </p>
                        <p class="mt-2">
                            <a href="content.php" class="text-muted">
                                <i class="fas fa-home me-1"></i>Вернуться на главную
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Переключение видимости пароля
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Автофокус на поле email
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField.value === '') {
                emailField.focus();
            }
        });
    </script>
</body>
</html>
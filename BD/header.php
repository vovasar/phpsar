<?php 
session_start();

// Проверяем авторизацию пользователя
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS (убрал дублирование) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    
    <!-- Ваши стили -->
    <!-- <link rel="stylesheet" href="style.css"> -->
    
    <title>Contact Manager</title>


</head>

<body>
    <header>
        <nav class="navigation d-flex justify-content-between align-items-center">
            <div class="left-buttons">
                <button onclick="location.href='content.php'" 
                        type="button" 
                        class="btn btn-dark">
                    Главная
                </button>
                <button onclick="location.href='create.php'" 
                        type="button" 
                        class="btn btn-outline-dark">
                    Создать
                </button>
            </div>
            
            <div class="right-buttons">
                <?php if($is_logged_in): ?>
                    <!-- Пользователь авторизован -->
                    <span class="user-info">
                        <span class="welcome-text">Привет, <?php echo $username; ?>!</span>
                    </span>
                    
                    <?php if($is_admin): ?>
                        <button onclick="location.href='admin.php'" 
                                type="button" 
                                class="btn btn-danger">
                            Админ
                        </button>
                    <?php endif; ?>
                    
                    <button onclick="location.href='logout.php'" 
                            type="button" 
                            class="btn btn-outline-secondary">
                        Выйти
                    </button>
                    
                <?php else: ?>
                    <!-- Пользователь не авторизован -->
                    <button onclick="location.href='register.php'" 
                            type="button" 
                            class="btn btn-primary">
                        Регистрация
                    </button>
                    <button onclick="location.href='login.php'" 
                            type="button" 
                            class="btn btn-outline-primary">
                        Вход
                    </button>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <div class="container">


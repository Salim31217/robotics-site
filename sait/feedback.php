<?php
// Включение отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "robotics_feedback";

// Инициализация переменных
$error = '';
$success = false;

try {
    // Создаем подключение
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
    
    // Обработка отправки формы
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name']) && !empty($_POST['message'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : NULL;
        $message = htmlspecialchars(trim($_POST['message']));
        $rating = !empty($_POST['rating']) ? intval($_POST['rating']) : NULL;
        
        // Подготовленный запрос для безопасности
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message, rating) 
                               VALUES (:name, :email, :message, :rating)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $success = true;
            header("Location: feedback.php?success=1");
            exit();
        }
    }
    
    // Получение всех отзывов
    $stmt = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = "Ошибка базы данных: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отзывы | Мир Робототехники</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Стили из предыдущего примера остаются без изменений */
        .feedback-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .feedback-form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        /* ... остальные стили ... */
    </style>
</head>
<body>
    <div class="site-container">
        <header class="site-header">
            <h1>Мир Робототехники</h1>
        </header>
        
        <div class="site-body">
            <nav class="site-menu">
                <ul>
                    <li><a href="index.html">Главная</a></li>
                    <li><a href="history.html">История</a></li>
                    <li><a href="projects.html">Проекты</a></li>
                    <li><a href="components.html">Комплектующие</a></li>
                    <li><a href="contacts.html">Контакты</a></li>
                    <li><a href="feedback.php">Отзывы</a></li>
                </ul>
            </nav>
            
            <main class="site-content">
                <div class="feedback-container">
                    <h2>Оставьте ваш отзыв</h2>
                    
                    <?php if (isset($_GET['success'])): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i> Спасибо! Ваш отзыв успешно отправлен.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="feedback-form" method="POST" action="feedback.php">
                        <div class="form-group">
                            <label for="name">Ваше имя*</label>
                            <input type="text" id="name" name="name" required minlength="2" maxlength="50"
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" maxlength="50"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Оценка</label>
                            <div class="rating-stars">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>"
                                        <?php echo (isset($_POST['rating']) && $_POST['rating'] == $i) ? 'checked' : ''; ?>>
                                    <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Ваш отзыв*</label>
                            <textarea id="message" name="message" required minlength="10" maxlength="1000"><?php 
                                echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; 
                            ?></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Отправить отзыв
                        </button>
                    </form>
                    
                    <div class="reviews-list">
                        <h2><i class="fas fa-comments"></i> Отзывы наших посетителей</h2>
                        
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <span class="review-author">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($review['name']); ?>
                                        </span>
                                        <span class="review-date">
                                            <i class="far fa-clock"></i> <?php echo date('d.m.Y H:i', strtotime($review['created_at'])); ?>
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($review['rating'])): ?>
                                        <div class="review-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star<?php echo ($i <= $review['rating']) ? '' : '-alt'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="review-text">
                                        <?php echo nl2br(htmlspecialchars($review['message'])); ?>
                                    </div>
                                    
                                    <?php if (!empty($review['email'])): ?>
                                        <div class="review-email">
                                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($review['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-reviews"><i class="far fa-frown"></i> Пока нет отзывов. Будьте первым!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المساعد القانوني الذكي 2026</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="glass-container">
        <header class="chat-header">
            <div class="bot-info">
                <div class="bot-avatar"><i class="fas fa-balance-scale"></i></div>
                <div class="bot-details">
                    <h1>المساعد القانوني</h1>
                    <span class="status"><i class="fas fa-circle"></i> متصل الآن</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="admin.php" class="btn-dashboard" title="لوحة الإدارة">
                    <i class="fas fa-chart-pie"></i> لوحة الإدارة
                </a>
            </div>
        </header>

        <div class="chat-window" id="chat-window">
            <div class="message-wrapper bot">
                <div class="message">مرحباً بك في المنصة القانونية الرقمية. كيف يمكنني إرشادك اليوم؟</div>
                <span class="time">الآن</span>
            </div>
        </div>

        <div class="suggestions">
            <button onclick="quickQuery('جواز سفر')">جواز سفر</button>
            <button onclick="quickQuery('بطاقة وطنية')">بطاقة وطنية</button>
            <button onclick="quickQuery('زواج')">عقد زواج</button>
        </div>

        <div class="input-area">
            <input type="text" id="chat-input" placeholder="اكتب استفسارك هنا..." autocomplete="off">
            <button id="send-btn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>

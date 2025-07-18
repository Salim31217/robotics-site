document.addEventListener('DOMContentLoaded', function() {
    const botResponses = {
        "привет": "Здравствуйте! Я виртуальный помощник по робототехнике. Чем могу помочь?",
        "здравствуйте": "Приветствую! Задайте ваш вопрос о робототехнике.",
        "как заказать": "Для заказа перейдите в раздел 'Комплектующие', выберите нужные компоненты и заполните форму. Или спросите меня о конкретных товарах!",
        "робототехника": "У нас есть материалы для всех уровней: от Arduino для начинающих до сложных роботов с ИИ! Что вас интересует?",
        "контакты": "Наши контакты:<br><i class='fas fa-phone'></i> +7 (987) 654-32-10<br><i class='fas fa-envelope'></i> robotics-world@example.com<br><i class='fab fa-telegram'></i> @robotics_world",
        "доставка": "Доставка осуществляется по всей России:<br>- Стандартная: 3-5 рабочих дней<br>- Экспресс: 1-2 дня<br>- Самовывоз: Москва, ул. Роботостроителей, 15",
        "гарантия": "На все комплектующие предоставляется гарантия:<br>- Arduino/Raspberry: 12 месяцев<br>- Датчики: 6 месяцев<br>- Серводвигатели: 12 месяцев",
        "ардуино": "Arduino - отличная платформа для начинающих:<br>- Uno: 1500 руб.<br>- Nano: 1200 руб.<br>- Стартовый набор: 3500 руб.",
        "raspberry": "Raspberry Pi - мини-компьютер для сложных проектов:<br>- Pi 4B 2GB: 4500 руб.<br>- Pi 4B 4GB: 6500 руб.<br>- Полный набор: 8500 руб.",
        "датчики": "У нас в наличии:<br>- Ультразвуковые (200 руб.)<br>- Инфракрасные (150 руб.)<br>- Температуры (180 руб.)<br>- Движения (250 руб.)",
        "сервоприводы": "Сервомоторы разных типов:<br>- SG90 (300 руб.)<br>- MG996R (800 руб.)<br>- Двухосевой (1200 руб.)",
        "спасибо": "Пожалуйста! Если у вас есть еще вопросы - обращайтесь!",
        "пока": "До свидания! Возвращайтесь за новыми знаниями по робототехнике!"
    };

    const chatInput = document.getElementById('chat-input');
    const chatOutput = document.getElementById('chat-output');
    const sendButton = document.getElementById('send-message');
    const toggleButton = document.getElementById('toggle-chat');
    let isChatMinimized = false;

    // Отправка сообщения
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message === '') return;
        
        addMessage(message, 'user-message');
        chatInput.value = '';
        
        setTimeout(() => {
            const response = getBotResponse(message);
            addMessage(response, 'bot-message');
        }, 500);
    }

    // Добавление сообщения в чат
    function addMessage(text, className) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', className);
        messageDiv.innerHTML = text;
        chatOutput.appendChild(messageDiv);
        chatOutput.scrollTop = chatOutput.scrollHeight;
    }

    // Получение ответа от бота
    function getBotResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        for (const [key, value] of Object.entries(botResponses)) {
            if (lowerMessage.includes(key)) {
                return value;
            }
        }
        
        return "Извините, я не понял вопрос. Попробуйте спросить о:<br>- Заказе ('как заказать')<br>- Комплектующих ('ардуино', 'датчики')<br>- Доставке ('доставка')<br>- Контактах ('контакты')";
    }

    // Сворачивание/разворачивание чата
    function toggleChat() {
        isChatMinimized = !isChatMinimized;
        const chatMessages = chatOutput;
        const chatInputContainer = document.querySelector('.chat-input');
        
        if (isChatMinimized) {
            chatMessages.style.display = 'none';
            chatInputContainer.style.display = 'none';
            toggleButton.innerHTML = '<i class="fas fa-plus"></i>';
        } else {
            chatMessages.style.display = 'flex';
            chatInputContainer.style.display = 'flex';
            toggleButton.innerHTML = '<i class="fas fa-minus"></i>';
            chatOutput.scrollTop = chatOutput.scrollHeight;
        }
    }

    // Обработчики событий
    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
    toggleButton.addEventListener('click', toggleChat);

    // Приветственное сообщение
    setTimeout(() => {
        addMessage(botResponses["привет"], 'bot-message');
    }, 1000);
});
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Real-Time Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2 class="text-center mb-4">Real-Time Notifications</h2>
    <div class="card shadow-sm p-4">
        <div class="text-end">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Notifications <span id="unreadCount" class="badge bg-danger">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" id="notificationList">
                    <li><a class="dropdown-item text-center">No new notifications</a></li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        var conn = new WebSocket('ws://localhost:8082');

        console.log("🔗 Tentando conectar ao WebSocket para notificações...");

        conn.onopen = function() {
            console.log('✅ WebSocket conectado para notificações!');
        };

        conn.onmessage = function(event) {
            console.log('📨 Notificação recebida:', event.data);
            var data = JSON.parse(event.data);
            if (data.type === 'notification') {
                console.log('📋 Atualizando notificações:', data);
                updateNotificationDropdown(data.notifications);
            }
        };

        conn.onerror = function(error) {
            console.error('❌ Erro no WebSocket de notificações:', error);
        };

        conn.onclose = function() {
            console.log('🔌 Conexão WebSocket de notificações fechada');
        };

        function updateNotificationDropdown(notifications) {
            var notificationList = document.getElementById('notificationList');
            var unreadBadge = document.getElementById('unreadCount');

            notificationList.innerHTML = '';

            if (notifications.length === 0) {
                notificationList.innerHTML = `<li><a class="dropdown-item text-center">No new notifications</a></li>`;
                unreadBadge.style.display = 'none';
                return;
            }

            let count = 0;

            notifications.forEach(function(notification) {

                let li = document.createElement('li');
                let a = document.createElement('a');

                a.className = 'dropdown-item';

                a.href = '#';

                a.innerHTML = `<strong>${notification.comment_subject}</strong>:${notification.comment_text}`;

                if (notification.comment_status == 0) {
                    console.log('test');
                    a.style.fontWeight = 'bold';
                    count++;
                }

                // Mark as read on click
                a.onclick = function() {
                    markAsRead(notification.comment_id);
                };

                li.appendChild(a);
                notificationList.appendChild(li);
            });

            unreadBadge.textContent = count;
            unreadBadge.style.display = count > 0 ? "inline" : "none";
        }

        function markAsRead(commentId) {
            conn.send(JSON.stringify({
                type: "mark_as_read",
                comment_id: commentId
            }));
        }
    </script>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
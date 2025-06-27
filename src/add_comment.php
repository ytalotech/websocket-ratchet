<!DOCTYPE html>
<html>

<head>
    <title>Add Comment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2 class="text-center mb-4">Add a Comment</h2>
    <div class="card shadow-sm p-4">
        <form id="commentForm" onsubmit="event.preventDefault(); sendComment(); ">
            <div class="mb-3">
                <label class="form-label">Enter Subject</label>
                <input type="text" class="form-control" id="subject" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Enter Comment</label>
                <textarea class="form-control" id="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Post Comment</button>
        </form>
    </div>
    <script>
        var ws = new WebSocket("ws://localhost:8082");

        console.log("Tentando conectar ao WebSocket...");

        ws.onopen = function() {
            console.log("✅ WebSocket conectado com sucesso!");
        };

        ws.onmessage = function(event) {
            console.log("📨 Mensagem recebida:", event.data);
        };

        ws.onerror = function(error) {
            console.error("❌ Erro no WebSocket:", error);
        };

        ws.onclose = function() {
            console.log("🔌 Conexão WebSocket fechada");
        };

        function sendComment() {
            var subject = document.getElementById('subject').value;
            var comment = document.getElementById('comment').value;

            if (subject && comment) {
                var data = {
                    type: "new_comment",
                    subject: subject,
                    comment: comment
                };

                console.log("📤 Enviando dados:", data);

                if (ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify(data));
                    console.log("✅ Dados enviados com sucesso!");

                    document.getElementById("commentForm").reset();
                    alert("Comment Added!");
                } else {
                    console.error("❌ WebSocket não está conectado. Estado:", ws.readyState);
                    alert("Erro: WebSocket não está conectado!");
                }

            } else {
                alert("Both fields are required!");
            }
        }
    </script>
</body>

</html>
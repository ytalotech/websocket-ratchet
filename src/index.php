<?php
// Arquivo de teste para verificar se o PHP está funcionando

echo "<h1>🚀 WebSocket Ratchet Project</h1>";
echo "<p>✅ PHP está funcionando corretamente!</p>";

// Verificar extensões PHP
echo "<h2>📋 Extensões PHP instaladas:</h2>";
echo "<ul>";
$extensions = ['pdo_mysql', 'mbstring', 'zip', 'curl', 'json'];
foreach ($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<li>{$status} {$ext}</li>";
}
echo "</ul>";

// Informações do sistema
echo "<h2>🔧 Informações do sistema:</h2>";
echo "<p><strong>Versão do PHP:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Servidor:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Data/Hora:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Testar conexão com MySQL
echo "<h2>🗄️ Teste de conexão MySQL:</h2>";
try {
    $pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    echo "<p>✅ Conexão com MySQL estabelecida com sucesso!</p>";
    echo "<p><strong>Versão do MySQL:</strong> " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "</p>";
} catch (PDOException $e) {
    echo "<p>❌ Erro na conexão com MySQL: " . $e->getMessage() . "</p>";
}

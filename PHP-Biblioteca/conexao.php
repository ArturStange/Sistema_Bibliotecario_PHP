<?php
$host = 'localhost';
$dbname = 'biblioteca_a3';
$usuario = 'root'; // Mude se o seu usuário do MySQL for diferente
$senha = 'ceub123456';       // Mude se o seu MySQL tiver senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha);
    // Configura o PDO para mostrar erros na tela, o que ajuda muito no desenvolvimento
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>
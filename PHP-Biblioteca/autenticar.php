<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Busca o usuário no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios_admin WHERE email = :email AND senha = :senha");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Se achou, cria a sessão de logado
        $_SESSION['logado'] = true;
        $_SESSION['nome_admin'] = $usuario['nome'];
        
        // Redireciona para o painel principal
        header("Location: dashboard.php");
        exit;
    } else {
        // Se errou, volta para o index com erro (você pode melhorar essa mensagem depois)
        echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='index.php';</script>";
        exit;
    }
}
?>
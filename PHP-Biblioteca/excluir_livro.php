<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['logado'])) { header("Location: index.php"); exit; }

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM livros WHERE id_livro = :id");
    $stmt->execute([':id' => $_GET['id']]);
}

header("Location: listar_livros.php");
exit;
?>
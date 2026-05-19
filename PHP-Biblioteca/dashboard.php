<?php
session_start();

// Proteção da página: se não existe a sessão 'logado', expulsa para o login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca A3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Biblioteca A3</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="listar_livros.php">Livros (CRUD)</a>
                    </li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Autores</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Categorias</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Leitores</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="#">Empréstimos</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-light me-3">Olá, <?php echo htmlspecialchars($_SESSION['nome_admin']); ?>!</span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center p-5">
                <h1 class="display-5">Painel de Gerenciamento</h1>
                <p class="lead mt-3">Bem-vindo ao sistema de controle da biblioteca acadêmica.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">Módulo de Livros</h5>
                            <p class="card-text text-muted">Acesse o gerenciamento do acervo, faça buscas avançadas com filtros e gerencie os registros.</p>
                        </div>
                        <a href="listar_livros.php" class="btn btn-primary mt-3">Acessar Livros</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100 bg-light text-muted">
                    <div class="card-body">
                        <h5 class="card-title">Outros Módulos</h5>
                        <p class="card-text small">As demais tabelas do domínio (Autores, Categorias, Leitores e Empréstimos) seguem a mesma lógica estrutural do CRUD de Livros.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
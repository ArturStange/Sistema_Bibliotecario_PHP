<?php
// login.php
require_once 'config.php';

// Se já estiver logado, redireciona para o painel correspondente
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['nivel_acesso'] == 3) {
        header("Location: painel_admin.php");
    } else {
        header("Location: painel_usuario.php");
    }
    exit;
}

$erro = '';

// =========================================================
// LÓGICA 1: LOGIN TRADICIONAL (E-mail e Senha)
// =========================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        // Busca o usuário no banco
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validação simples (senhas em texto limpo conforme script SQL de carga inicial)
        if ($usuario && $usuario['senha'] === $senha) {
            // Criação das variáveis de sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

            // Redirecionamento por nível de acesso
            if ($usuario['nivel_acesso'] == 3) {
                header("Location: painel_admin.php");
            } else {
                header("Location: painel_usuario.php");
            }
            exit;
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}

// =========================================================
// LÓGICA 2: LOGIN COM GOOGLE OAUTH 2.0 (Recebendo o JWT)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['credential'])) {
    $jwt = $_POST['credential'];
    
    // Divide o token em 3 partes e pega o payload (dados do utilizador)
    $jwt_parts = explode('.', $jwt);
    $payload = json_decode(base64_decode($jwt_parts[1]), true);
    
    $google_email = $payload['email'];
    $google_nome = $payload['name'];
    
    // Verifica se o email do Google já existe no nosso banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $google_email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Conta existe: Faz o Login
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];
    } else {
        // Conta não existe: Regista automaticamente (Nível 2 = Usuário Padrão)
        // Definimos uma senha aleatória que ele nunca vai usar, pois sempre fará login com o Google
        $senha_aleatoria = bin2hex(random_bytes(8)); 
        
        $stmt_insert = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, nivel_acesso, status_conta) VALUES (?, ?, ?, 2, 'ativo')");
        $stmt_insert->execute([$google_nome, $google_email, $senha_aleatoria]);
        
        // Loga o utilizador recém-criado
        $_SESSION['usuario_id'] = $pdo->lastInsertId();
        $_SESSION['usuario_nome'] = $google_nome;
        $_SESSION['nivel_acesso'] = 2;
    }

    // Redireciona
    if ($_SESSION['nivel_acesso'] == 3) {
        header("Location: painel_admin.php");
    } else {
        header("Location: painel_usuario.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca A3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow" style="width: 100%; max-width: 400px;">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Acesso ao Sistema</h3>
        
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>

        <div class="mb-3 text-center">
            <div id="g_id_onload"
                 data-client_id="818513717763-fi0qsebcf8iit2eklueva6oic6fjrrhn.apps.googleusercontent.com"
                 data-login_uri="https://localhost/SistemaBibliotecarioPHP/login.php"
                 data-auto_prompt="false">
            </div>
            <div class="g_id_signin"
                 data-type="standard"
                 data-size="large"
                 data-theme="outline"
                 data-text="sign_in_with"
                 data-shape="rectangular"
                 data-logo_alignment="center"
                 data-width="350">
            </div>
        </div>

        <div class="d-flex align-items-center mb-3">
            <hr class="flex-grow-1"><span class="mx-2 text-muted">OU</span><hr class="flex-grow-1">
        </div>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha">
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
            <div class="text-center mb-2">
                <span>Não tem uma conta? <a href="cadastro.php" class="text-decoration-none">Crie uma aqui</a></span>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
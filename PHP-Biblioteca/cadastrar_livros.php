<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['logado'])) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO livros (titulo, ano_publicacao, id_autor, id_categoria) VALUES (:titulo, :ano, :autor, :categoria)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $_POST['titulo'],
        ':ano' => $_POST['ano_publicacao'],
        ':autor' => $_POST['id_autor'],
        ':categoria' => $_POST['id_categoria']
    ]);
    header("Location: listar_livros.php");
    exit;
}

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$autores = $pdo->query("SELECT * FROM autores")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Livro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-4">Cadastrar Novo Livro</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Ano de Publicação</label>
                        <input type="number" name="ano_publicacao" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Autor</label>
                        <select name="id_autor" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php foreach($autores as $a): ?><option value="<?php echo $a['id_autor']; ?>"><?php echo $a['nome']; ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Categoria</label>
                        <select name="id_categoria" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php foreach($categorias as $c): ?><option value="<?php echo $c['id_categoria']; ?>"><?php echo $c['nome_categoria']; ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <a href="listar_livros.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
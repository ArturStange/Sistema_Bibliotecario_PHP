<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['logado']) || !isset($_GET['id'])) { header("Location: listar_livros.php"); exit; }

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE livros SET titulo = :titulo, ano_publicacao = :ano, id_autor = :autor, id_categoria = :categoria WHERE id_livro = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $_POST['titulo'], ':ano' => $_POST['ano_publicacao'], 
        ':autor' => $_POST['id_autor'], ':categoria' => $_POST['id_categoria'], ':id' => $id
    ]);
    header("Location: listar_livros.php");
    exit;
}

$livro = $pdo->prepare("SELECT * FROM livros WHERE id_livro = :id");
$livro->execute([':id' => $id]);
$livro = $livro->fetch(PDO::FETCH_ASSOC);

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$autores = $pdo->query("SELECT * FROM autores")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Editar Livro</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm"><div class="card-body">
            <h4 class="mb-4">Editar Livro</h4>
            <form method="POST">
                <div class="mb-3"><label>Título</label><input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($livro['titulo']); ?>" required></div>
                <div class="mb-3"><label>Ano</label><input type="number" name="ano_publicacao" class="form-control" value="<?php echo $livro['ano_publicacao']; ?>"></div>
                <div class="mb-3"><label>Autor</label><select name="id_autor" class="form-select" required>
                    <?php foreach($autores as $a): ?><option value="<?php echo $a['id_autor']; ?>" <?php echo $livro['id_autor'] == $a['id_autor'] ? 'selected' : ''; ?>><?php echo $a['nome']; ?></option><?php endforeach; ?>
                </select></div>
                <div class="mb-3"><label>Categoria</label><select name="id_categoria" class="form-select" required>
                    <?php foreach($categorias as $c): ?><option value="<?php echo $c['id_categoria']; ?>" <?php echo $livro['id_categoria'] == $c['id_categoria'] ? 'selected' : ''; ?>><?php echo $c['nome_categoria']; ?></option><?php endforeach; ?>
                </select></div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="listar_livros.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div></div>
    </div>
</body>
</html>
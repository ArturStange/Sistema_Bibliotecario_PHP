<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['logado'])) { header("Location: index.php"); exit; }

// Lógica da Busca Avançada
$where = [];
$params = [];

if (!empty($_GET['titulo'])) {
    $where[] = "l.titulo LIKE :titulo";
    $params[':titulo'] = '%' . $_GET['titulo'] . '%';
}
if (!empty($_GET['id_categoria'])) {
    $where[] = "l.id_categoria = :id_categoria";
    $params[':id_categoria'] = $_GET['id_categoria'];
}
if (!empty($_GET['id_autor'])) {
    $where[] = "l.id_autor = :id_autor";
    $params[':id_autor'] = $_GET['id_autor'];
}

$sql = "SELECT l.*, a.nome as nome_autor, c.nome_categoria 
        FROM livros l 
        LEFT JOIN autores a ON l.id_autor = a.id_autor 
        LEFT JOIN categorias c ON l.id_categoria = c.id_categoria";

if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar categorias e autores para preencher os selects do filtro
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$autores = $pdo->query("SELECT * FROM autores")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Livros - Biblioteca A3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Biblioteca A3</a>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Voltar</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gerenciamento de Livros</h2>
            <a href="cadastrar_livro.php" class="btn btn-success">+ Novo Livro</a>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="titulo" class="form-control" placeholder="Filtrar por Título" value="<?php echo $_GET['titulo'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="id_categoria" class="form-select">
                            <option value="">Todas as Categorias</option>
                            <?php foreach($categorias as $c): ?>
                                <option value="<?php echo $c['id_categoria']; ?>" <?php echo (isset($_GET['id_categoria']) && $_GET['id_categoria'] == $c['id_categoria']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['nome_categoria']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="id_autor" class="form-select">
                            <option value="">Todos os Autores</option>
                            <?php foreach($autores as $a): ?>
                                <option value="<?php echo $a['id_autor']; ?>" <?php echo (isset($_GET['id_autor']) && $_GET['id_autor'] == $a['id_autor']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($a['nome']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-striped table-hover bg-white shadow-sm rounded">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Título</th><th>Ano</th><th>Autor</th><th>Categoria</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($livros as $livro): ?>
                <tr>
                    <td><?php echo $livro['id_livro']; ?></td>
                    <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                    <td><?php echo $livro['ano_publicacao']; ?></td>
                    <td><?php echo htmlspecialchars($livro['nome_autor']); ?></td>
                    <td><?php echo htmlspecialchars($livro['nome_categoria']); ?></td>
                    <td>
                        <a href="editar_livro.php?id=<?php echo $livro['id_livro']; ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="excluir_livro.php?id=<?php echo $livro['id_livro']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
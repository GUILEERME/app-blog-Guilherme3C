<?php

require_once '../../setup/connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT 
            U.*,
            L.name AS level_name
        FROM users AS U
        INNER JOIN level_users AS L
            ON U.id_level_users = L.id
        WHERE U.id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: index.php");
        exit;
    }

    $levels = $pdo->query("
        SELECT *
        FROM level_users
        ORDER BY level ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erro: " . $e->getMessage());

}

include_once '../_inc/_header.php';

?>

<div class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <h4 class="mb-4">
                Editar Level
            </h4>

            <form action="update.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $user['id']; ?>"
                >

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            ID
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($user['id']); ?>"
                            disabled
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Level
                        </label>

                        <select
                            name="id_level_users"
                            class="form-select"
                            required
                        >

                            <?php foreach ($levels as $level): ?>

                                <option
                                    value="<?= $level['id']; ?>"
                                    <?= $level['id'] == $user['id_level_users'] ? 'selected' : ''; ?>
                                >
                                    <?= htmlspecialchars($level['name']); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Valor
                        </label>

                        <input
                            type="number"
                            name="valor"
                            class="form-control"
                            value="<?= htmlspecialchars($user['valor']); ?>"
                            required
                        >

                    </div>

                </div>

                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >
                        Atualizar
                    </button>

                    <a
                        href="index.php"
                        class="btn btn-outline-secondary px-4"
                    >
                        Voltar
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include_once '../_inc/_footer.php'; ?>
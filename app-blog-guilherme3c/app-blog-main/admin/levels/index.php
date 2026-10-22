 <?php

require_once '../../setup/connect.php';

try {

    $sql = "SELECT 
                U.id,
                U.valor,
                L.name AS level_name
            FROM users AS U
            INNER JOIN level_users AS L
                ON U.id_level_users = L.id
            ORDER BY U.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erro na consulta: " . $e->getMessage());

}

include_once '../_inc/_header.php';

?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-0">Levels</h4>
        </div>

    </div>

    <div class="card card-full border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="bg-light">

                        <tr class="text-muted small">

                            <th class="ps-4">
                                ID
                            </th>

                            <th>
                                LEVEL
                            </th>

                            <th>
                                VALOR
                            </th>

                            <th class="text-end pe-4">
                                AÇÕES
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (count($users) > 0): ?>

                            <?php foreach ($users as $user): ?>

                                <tr>

                                    <!-- ID -->
                                    <td class="ps-4">
                                        <?= htmlspecialchars($user['id']); ?>
                                    </td>


                                    <!-- LEVEL -->
                                    <td>

                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">

                                            <?= htmlspecialchars($user['level_name']); ?>

                                        </span>

                                    </td>


                                    <!-- VALOR NUMÉRICO DO USUÁRIO -->
                                    <td>

                                        <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">

                                            <?= htmlspecialchars($user['valor']); ?>

                                        </span>

                                    </td>


                                    <!-- AÇÕES -->
                                    <td class="text-end pe-4">

                                        <div class="btn-group shadow-sm">

                                            <a
                                                href="form_update.php?id=<?= $user['id']; ?>"
                                                class="btn btn-white btn-sm border"
                                                title="Editar"
                                            >
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>

                                            <a
                                                href="javascript:void(0)"
                                                class="btn btn-white btn-sm border"
                                                title="Excluir"
                                                onclick="confirmarExclusao(
                                                    <?= $user['id']; ?>,
                                                    '<?= addslashes(htmlspecialchars($user['level_name'])); ?>'
                                                )"
                                            >
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="4" class="text-center py-5 text-muted">
                                    Nenhum usuário encontrado.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>

function confirmarExclusao(id, nome) {

    Swal.fire({

        title: 'Tem certeza?',

        text: `Você deseja excluir o usuário: "${nome}"?`,

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#d33',

        cancelButtonColor: '#3085d6',

        confirmButtonText: 'Sim, excluir!',

        cancelButtonText: 'Cancelar'

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = `delete.php?id=${id}`;

        }

    });

}

</script>


<?php include_once '../_inc/_footer.php'; ?>
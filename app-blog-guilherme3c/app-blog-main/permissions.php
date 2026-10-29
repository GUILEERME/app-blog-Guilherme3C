
<?php

/*
 * ATENÇÃO:
 * Os IDs abaixo são exemplos.
 * Confirme os IDs reais dos níveis no seu banco.
 */

const NIVEL_ADMIN = 1;
const NIVEL_EDITOR = 2;

$permissoes = [
    NIVEL_ADMIN => [
        'blogs' => [
            'create',
            'edit',
            'view',
            'delete'
        ]
    ],

    NIVEL_EDITOR => [
        'blogs' => [
            'create',
            'edit',
            'view'
        ]
    ]
];

function temPermissao(string $modulo, string $acao): bool
{
    global $permissoes;

    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }

    $nivel = $_SESSION['id_level_users'] ?? null;

    if ($nivel === null) {
        return false;
    }

    return in_array(
        $acao,
        $permissoes[(int) $nivel][$modulo] ?? [],
        true
    );
}

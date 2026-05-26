<?php
// views/partials/head.php
// Uso: <?php $titulo = 'Inicio'; require_once __DIR__ . '/../partials/head.php'; ?>
$titulo = $titulo ?? 'UTS';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTS — <?= htmlspecialchars($titulo) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styles.css">
<script src="<?= BASE_URL ?>/public/js/auth.js"></script>

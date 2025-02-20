<?php
include 'includes/db.php';
include 'includes/funciones.php';

$ciudadUsuario = $_GET['ciudad'];
$coordsUsuario = geocodeCiudad($ciudadUsuario);

if (!$coordsUsuario) die("Ciudad no encontrada");

// Obtener vendedoras
$vendedoras = $pdo->query("SELECT * FROM vendedoras")->fetchAll();

// Calcular distancias
foreach ($vendedoras as &$vend) {
    $vend['distancia'] = calcularDistancia(
        $coordsUsuario['lat'], $coordsUsuario['lon'],
        $vend['lat'], $vend['lon']
    );
}
unset($vend);

// Ordenar por distancia
usort($vendedoras, function($a, $b) {
    return $a['distancia'] <=> $b['distancia'];
});

// Obtener último PDF
$catalogo = $pdo->query("SELECT * FROM catalogos ORDER BY fecha_upload DESC LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3 class="mb-4">Vendedoras cerca de <?= htmlspecialchars($ciudadUsuario) ?></h3>
    
    <!-- Listado Vendedoras -->
    <div class="row">
        <?php foreach ($vendedoras as $vend): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5><?= htmlspecialchars($vend['nombre']) ?></h5>
                    <p>📍 <?= htmlspecialchars($vend['direccion']) ?>, <?= htmlspecialchars($vend['ciudad']) ?></p>
                    <p>📱 <a href="https://wa.me/<?= $vend['telefono'] ?>" class="text-decoration-none" target="_blank">
                        <?= $vend['telefono'] ?>
                    </a></p>
                    <p class="text-muted">Distancia: <?= round($vend['distancia'], 1) ?> km</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Visualizador PDF -->
    <?php if ($catalogo): ?>
    <div class="mt-5">
        <h4>Catálogo Actual</h4>
        <embed src="catalogos/<?= $catalogo['pdf_path'] ?>" type="application/pdf" width="100%" height="600px">
    </div>
    <?php endif; ?>

    <!-- Atribución OSM -->
    <footer class="mt-5 text-center text-muted">
        © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors
    </footer>
</body>
</html>
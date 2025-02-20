<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <img src="logo.png" alt="Logo" class="mb-4" style="max-width: 200px;">
            <h1 class="mb-4">Encuentra tu vendedora más cercana</h1>
            <form action="resultados.php" method="get">
                <div class="input-group">
                    <input type="text" name="ciudad" placeholder="Ingresa tu ciudad" required class="form-control">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
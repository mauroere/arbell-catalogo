<?php
session_start();
include '../includes/db.php';

// Verificar autenticación
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Subir PDF
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['catalogo'])) {
    $targetDir = "../catalogos/";
    $fileName = uniqid() . '_' . basename($_FILES['catalogo']['name']);
    $targetFile = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES['catalogo']['tmp_name'], $targetFile)) {
        $stmt = $pdo->prepare("INSERT INTO catalogos (pdf_path, fecha_upload) VALUES (?, NOW())");
        $stmt->execute([$fileName]);
        $success_pdf = "Catálogo subido exitosamente!";
    }
}

// Agregar Vendedora
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
    include '../includes/funciones.php';
    $coords = geocodeCiudad($_POST['ciudad']);
    
    if ($coords) {
        $stmt = $pdo->prepare("INSERT INTO vendedoras (nombre, telefono, direccion, ciudad, lat, lon) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nombre'],
            '+549' . preg_replace('/\D/', '', $_POST['telefono']), // Formato Argentina
            $_POST['direccion'],
            $_POST['ciudad'],
            $coords['lat'],
            $coords['lon']
        ]);
        $success_vend = "Vendedora agregada!";
    } else {
        $error = "Error al geocodificar la ciudad";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Bienvenido Admin</h2>
    
    <!-- Formulario Subir PDF -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Subir Catálogo PDF</h5>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="catalogo" accept="application/pdf" required class="form-control mb-2">
                <button type="submit" class="btn btn-primary">Subir</button>
            </form>
            <?= isset($success_pdf) ? "<div class='alert alert-success mt-2'>$success_pdf</div>" : '' ?>
        </div>
    </div>

    <!-- Formulario Agregar Vendedora -->
    <div class="card">
        <div class="card-body">
            <h5>Nueva Vendedora</h5>
            <form method="post">
                <input type="text" name="nombre" placeholder="Nombre Completo" required class="form-control mb-2">
                <input type="tel" name="telefono" placeholder="1122334455" pattern="[0-9]{10}" title="10 dígitos sin código" required class="form-control mb-2">
                <input type="text" name="direccion" placeholder="Calle y Número" required class="form-control mb-2">
                <input type="text" name="ciudad" placeholder="Ciudad" required class="form-control mb-2">
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>
            <?= isset($success_vend) ? "<div class='alert alert-success mt-2'>$success_vend</div>" : '' ?>
            <?= isset($error) ? "<div class='alert alert-danger mt-2'>$error</div>" : '' ?>
        </div>
    </div>
</body>
</html>
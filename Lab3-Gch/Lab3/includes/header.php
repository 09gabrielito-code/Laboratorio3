<?php
// includes/header.php - Metadatos, navegación y migas de pan (modular)
// Detectamos el nombre del archivo actual (ej: index.php o procesar.php)
$paginaActual = basename($_SERVER['PHP_SELF']);
$tituloPagina = $tituloPagina ?? 'Sistema de Admisión de la UTP';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="Sistema de admisión de datos para aspirantes">
    <meta name="author" content="Universidad Tecnológica de Panamá / Estudiante">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#212529">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">PortalU</a>
            </div>
        </nav>

        <!-- Breadcrumb dinámico -->
        <div class="bg-white border-bottom py-2">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Inicio</a></li>
                        <?php if ($paginaActual == 'procesar.php'): ?>
                            <!-- En el procesador: paso intermedio con enlace de regreso -->
                            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Registro</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Procesando Datos</li>
                        <?php else: ?>
                            <!-- En el inicio -->
                            <li class="breadcrumb-item active" aria-current="page">Registro de Aspirante</li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
        </div>
    </header>

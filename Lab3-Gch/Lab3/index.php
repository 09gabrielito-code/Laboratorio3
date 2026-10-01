<?php
// index.php - Página principal con el formulario visual de registro
$tituloPagina = 'Sistema de Admisión de la UTP';
include 'includes/header.php';
?>

    <main class="container flex-grow-1 py-4">
        <section class="mx-auto" style="max-width: 520px;">
            <h1 class="h4 fw-bold text-center mb-3">Formulario de Registro de Aspirantes</h1>
            <?php include 'includes/formulario.php'; ?>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>

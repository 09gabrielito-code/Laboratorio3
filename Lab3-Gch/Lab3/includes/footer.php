<?php
// includes/footer.php - Pie de página modular común
?>
    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <!-- Eslogan institucional -->
            <p class="mb-1 fw-semibold">Portal de Gestión de Aspirantes — Facultad de Ingeniería de Sistemas Computacionales — Universidad Tecnológica de Panamá</p>

            <!-- Redes sociales y contacto -->
            <div class="mb-2">
                <a href="https://github.com/" target="_blank" rel="noopener" class="text-white mx-2 fs-5" aria-label="GitHub"><i class="bi bi-github"></i></a>
                <a href="https://www.linkedin.com/" target="_blank" rel="noopener" class="text-white mx-2 fs-5" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="mailto:soporte@utp.ac.pa" class="text-white mx-2 fs-5" aria-label="Correo de soporte"><i class="bi bi-envelope-fill"></i></a>
            </div>

            <!-- Enlaces rápidos (opcional) -->
            <div class="mb-2">
                <a href="index.php" class="text-white text-decoration-none mx-2 small">Inicio</a> |
                <a href="#" class="text-white text-decoration-none mx-2 small">Políticas de Privacidad</a> |
                <a href="#" class="text-white text-decoration-none mx-2 small">Términos de Uso</a>
            </div>

            <!-- Copyright con año dinámico -->
            <p class="text-white-50 small mb-0">&copy; <?php echo date('Y'); ?> Universidad Tecnológica de Panamá. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

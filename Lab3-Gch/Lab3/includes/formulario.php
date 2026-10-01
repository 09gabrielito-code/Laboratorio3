<?php
// includes/formulario.php - Formulario de registro (se incluye dentro de <main><section>)
// enctype="multipart/form-data" es obligatorio para poder enviar la imagen al servidor.
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="procesar.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre (Requerido):</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                       placeholder="Ej: Sofía" maxlength="50" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label fw-bold">Apellido (Requerido):</label>
                <input type="text" class="form-control" id="apellido" name="apellido"
                       placeholder="Ej: Pérez" maxlength="50" required>
            </div>

            <div class="mb-3">
                <label for="identificacion" class="form-label fw-bold">Identificación (Requerido):</label>
                <input type="text" class="form-control" id="identificacion" name="identificacion"
                       placeholder="Ej: 8-123-456" maxlength="20" required>
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label fw-bold">Fecha de Nacimiento (Requerido):</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                       max="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <span class="form-label fw-bold d-block">Sexo (Requerido):</span>
                <div class="d-flex gap-2">
                    <input type="radio" class="btn-check" name="sexo" id="sexo_h" value="Hombre" autocomplete="off" required>
                    <label class="btn btn-outline-secondary flex-fill" for="sexo_h">Hombre</label>

                    <input type="radio" class="btn-check" name="sexo" id="sexo_m" value="Mujer" autocomplete="off">
                    <label class="btn btn-outline-secondary flex-fill" for="sexo_m">Mujer</label>
                </div>
            </div>

            <div class="mb-4">
                <label for="foto" class="form-label fw-bold">Fotografía del Aspirante (png, jpg, jpeg, gif, webp):</label>
                <input type="file" class="form-control" id="foto" name="foto"
                       accept=".png,.jpg,.jpeg,.gif,.webp,image/png,image/jpeg,image/gif,image/webp" required>
                <div class="form-text">Tamaño máximo: 2 MB.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar Aspirante</button>
        </form>
    </div>
</div>

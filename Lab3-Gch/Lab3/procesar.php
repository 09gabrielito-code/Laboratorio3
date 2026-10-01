<?php
// procesar.php - Backend: valida, normaliza, calcula la edad y guarda la foto.

// Solo se acepta el envío del formulario por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/** Limpieza básica: quita espacios y etiquetas HTML/PHP (htmlspecialchars se aplica al imprimir). */
function limpiar($valor): string
{
    return strip_tags(trim((string) $valor));
}

$errores = [];

/* ---------- 1. Recibir y sanear ---------- */
$nombre         = limpiar($_POST['nombre'] ?? '');
$apellido       = limpiar($_POST['apellido'] ?? '');
$identificacion = limpiar($_POST['identificacion'] ?? '');
$fechaNac       = limpiar($_POST['fecha_nacimiento'] ?? '');
$sexo           = limpiar($_POST['sexo'] ?? '');

/* ---------- 2. Validar que no estén vacíos ---------- */
if ($nombre === '')         $errores[] = 'El nombre es obligatorio.';
if ($apellido === '')       $errores[] = 'El apellido es obligatorio.';
if ($identificacion === '') $errores[] = 'La identificación es obligatoria.';
if ($fechaNac === '')       $errores[] = 'La fecha de nacimiento es obligatoria.';
if ($sexo === '')           $errores[] = 'El sexo es obligatorio.';

/* ---------- 3. Estandarizar textos ---------- */
// ucwords(strtolower()) en versión multibyte para respetar tildes y la ñ: "sofía" -> "Sofía"
$nombre   = mb_convert_case(mb_strtolower($nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
$apellido = mb_convert_case(mb_strtolower($apellido, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
// Identificación en mayúsculas
$identificacion = mb_strtoupper($identificacion, 'UTF-8');

if ($nombre !== '' && !preg_match("/^[\p{L}][\p{L}\s'.-]{0,49}$/u", $nombre)) {
    $errores[] = 'El nombre solo puede contener letras y espacios.';
}
if ($apellido !== '' && !preg_match("/^[\p{L}][\p{L}\s'.-]{0,49}$/u", $apellido)) {
    $errores[] = 'El apellido solo puede contener letras y espacios.';
}
if ($identificacion !== '' && !preg_match('/^[A-Z0-9-]{3,20}$/', $identificacion)) {
    $errores[] = 'La identificación solo admite letras, números y guiones (3 a 20 caracteres).';
}
if ($sexo !== '' && !in_array($sexo, ['Hombre', 'Mujer'], true)) {
    $errores[] = 'El valor de sexo no es válido.';
}

/* ---------- 4. Fecha de nacimiento y edad (18 a 70 años) ---------- */
$edad = null;
if ($fechaNac !== '') {
    $nac = DateTime::createFromFormat('Y-m-d', $fechaNac);
    $hoy = new DateTime('today');
    if (!$nac || $nac->format('Y-m-d') !== $fechaNac) {
        $errores[] = 'La fecha de nacimiento no es válida.';
    } elseif ($nac > $hoy) {
        $errores[] = 'La fecha de nacimiento no puede ser futura.';
    } else {
        $edad = $nac->diff($hoy)->y;
        if ($edad < 18 || $edad > 70) {
            $errores[] = "La edad ($edad años) debe estar entre 18 y 70 años.";
        }
    }
}

/* ---------- 5. Validar la fotografía ---------- */
$permitidas = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
];
$maxBytes = 2 * 1024 * 1024; // 2 MB
$foto     = $_FILES['foto'] ?? null;
$extension = '';

if (!$foto || $foto['error'] === UPLOAD_ERR_NO_FILE) {
    $errores[] = 'Debe seleccionar una fotografía.';
} elseif ($foto['error'] === UPLOAD_ERR_INI_SIZE || $foto['error'] === UPLOAD_ERR_FORM_SIZE) {
    $errores[] = 'La fotografía excede el tamaño máximo permitido.';
} elseif ($foto['error'] !== UPLOAD_ERR_OK) {
    $errores[] = 'Ocurrió un error al subir la fotografía.';
} elseif (!is_uploaded_file($foto['tmp_name'])) {
    $errores[] = 'Archivo de fotografía no válido.';
} else {
    $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime      = $finfo->file($foto['tmp_name']);

    if (!array_key_exists($extension, $permitidas)) {
        $errores[] = 'Extensión no permitida. Use: jpg, jpeg, png, gif o webp.';
    } elseif ($mime !== $permitidas[$extension]) {
        $errores[] = 'El contenido del archivo no coincide con una imagen válida.';
    } elseif (@getimagesize($foto['tmp_name']) === false) {
        $errores[] = 'El archivo no es una imagen válida.';
    } elseif ($foto['size'] > $maxBytes) {
        $errores[] = 'La fotografía no debe superar los 2 MB.';
    }
}

/* ---------- 6. Guardar la foto (solo si todo es válido) ---------- */
$rutaFoto  = '';
$fotoBase64 = '';
if (empty($errores)) {
    $carpeta = __DIR__ . '/uploaded_files/';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }
    // Nombre aleatorio: evita colisiones y que el usuario controle el nombre del archivo
    $nombreSeguro = bin2hex(random_bytes(16)) . '.' . $extension;
    $rutaFoto     = $carpeta . $nombreSeguro;

    if (move_uploaded_file($foto['tmp_name'], $rutaFoto)) {
        // La carpeta está protegida (.htaccess), por eso la mostramos incrustada en base64
        $fotoBase64 = 'data:' . $permitidas[$extension] . ';base64,' . base64_encode(file_get_contents($rutaFoto));
    } else {
        $errores[] = 'No se pudo guardar la fotografía en el servidor.';
    }
}

$tituloPagina = 'Resultado del Registro';
include 'includes/header.php';
?>

    <main class="container flex-grow-1 py-4">
        <section class="mx-auto" style="max-width: 520px;">

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger" role="alert">
                    <h2 class="h5 alert-heading">No se pudo completar el registro</h2>
                    <ul class="mb-0">
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="index.php" class="btn btn-secondary w-100">Volver al formulario</a>

            <?php else: ?>
                <div class="alert alert-success" role="alert">
                    <strong>¡Aspirante registrado correctamente!</strong>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <img src="<?php echo $fotoBase64; ?>" alt="Fotografía del aspirante"
                             class="img-thumbnail rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <h2 class="h5 mb-3">
                            <?php echo htmlspecialchars($nombre . ' ' . $apellido, ENT_QUOTES, 'UTF-8'); ?>
                        </h2>
                        <ul class="list-group list-group-flush text-start">
                            <li class="list-group-item"><strong>Identificación:</strong>
                                <?php echo htmlspecialchars($identificacion, ENT_QUOTES, 'UTF-8'); ?></li>
                            <li class="list-group-item"><strong>Fecha de nacimiento:</strong>
                                <?php echo htmlspecialchars($fechaNac, ENT_QUOTES, 'UTF-8'); ?></li>
                            <li class="list-group-item"><strong>Edad:</strong> <?php echo (int) $edad; ?> años</li>
                            <li class="list-group-item"><strong>Sexo:</strong>
                                <?php echo htmlspecialchars($sexo, ENT_QUOTES, 'UTF-8'); ?></li>
                            <li class="list-group-item"><strong>Foto guardada como:</strong>
                                <code><?php echo htmlspecialchars($nombreSeguro, ENT_QUOTES, 'UTF-8'); ?></code></li>
                        </ul>
                    </div>
                </div>
                <a href="index.php" class="btn btn-primary w-100 mt-3">Registrar otro aspirante</a>
            <?php endif; ?>

        </section>
    </main>

<?php include 'includes/footer.php'; ?>

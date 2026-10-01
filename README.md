Laboratorio #3 Registro de Aspirantes

Universidad Tecnológica de Panamá Facultad de Ingeniería de Sistemas Computacionales
Módulo II: Diseño Web con HTML5 y CSS3 · Módulo III: Programación de Aplicaciones Web
Profesora: Ing. Irina Fong · 1 de octubre de 2026

Estudiante:Gabriel Chifundo · Cédula: 3-760-1894 · Grupo:1S3122



#1. Descripción

Aplicación web en **PHP + Bootstrap 5.3.8** para registrar aspirantes. El usuario completa un formulario con nombre, apellido, identificación, fecha de nacimiento, sexo y fotografía. El backend valida y normaliza los datos, calcula la edad, guarda la foto en una carpeta protegida (sin base de datos) y muestra el resultado.

## 2. Estructura del proyecto

```
Lab3/
├── includes/
│   ├── header.php        # Metadatos, <header>, navbar y breadcrumb dinámico
│   ├── footer.php        # <footer> con eslogan, redes, enlaces y año dinámico
│   └── formulario.php    # Formulario de registro (se incluye dentro de <main><section>)
├── uploaded_files/       # Fotos subidas (protegida con .htaccess)
│   ├── .htaccess         # Bloquea el acceso desde el navegador
│   ├── index.html        # Respaldo si el servidor no usa .htaccess
│   └── .gitkeep          # Mantiene la carpeta vacía en Git
├── index.php             # Página principal con el formulario
├── procesar.php          # Backend: valida, procesa y muestra el resultado
└── README.md
```

## 3. Requisitos y ejecución

- Servidor local con **Apache y PHP 7.4 o superior** (WAMP, XAMPP, etc.). La extensión `fileinfo` debe estar activa (viene activada por defecto).
- Conexión a internet para cargar Bootstrap y Bootstrap Icons desde CDN.
- Apache con `AllowOverride All` para que el `.htaccess` surta efecto (viene así en WAMP).

Pasos:

1. Copiar la carpeta `Lab3` en `C:\wamp64\www\`.
2. Iniciar Apache desde WAMP.
3. Abrir `http://localhost/Lab3` en el navegador.

## 4. Cumplimiento de la rúbrica

| Requerimiento | Cómo se resolvió |
|---|---|
| Interfaz con Bootstrap y etiquetas semánticas | Se usa Bootstrap 5.3.8 (cards, grid, botones) y las etiquetas `<header>`, `<main>`, `<section>` y `<footer>`. |
| Formulario dentro de `<main><section>` | `index.php` coloca `include 'includes/formulario.php'` dentro de `<main><section>`. |
| Menú y migas de pan modulares con `include` | `includes/header.php` contiene el navbar y el breadcrumb. Se detecta la página con `basename($_SERVER['PHP_SELF'])` y la miga cambia entre `index.php` y `procesar.php`. |
| Edad entre 18 y 70 años | `procesar.php` calcula la edad con `DateTime::diff()` y rechaza valores fuera del rango, fechas inválidas y fechas futuras. |
| Nombre y apellido en formato título | `mb_convert_case(mb_strtolower(...), MB_CASE_TITLE)`: "sofía" pasa a "Sofía". Es la versión multibyte de `ucwords(strtolower())` para respetar tildes y la ñ. |
| Subida segura de la foto sin base de datos | Se valida el error de subida, la extensión (jpg, jpeg, png, gif, webp), el tipo MIME real (`finfo`), `getimagesize()` y el tamaño máximo (2 MB). El archivo se guarda con nombre aleatorio (`random_bytes`) en `uploaded_files/`. |
| Carpeta de fotos inaccesible desde el navegador | `uploaded_files/.htaccess` deniega todo acceso (`Require all denied`), desactiva el listado y los handlers de PHP. Para mostrar la foto en el resultado se incrusta en base64, ya que no se puede abrir por URL. |
| Footer profesional | Eslogan institucional, íconos de GitHub, LinkedIn y correo, enlaces rápidos y copyright con `date('Y')`. |

## 5. Flujo de la aplicación

1. `index.php` muestra el formulario (`method="post"` y `enctype="multipart/form-data"`, obligatorio para enviar la imagen).
2. Los datos se envían por **POST** a `procesar.php`.
3. `procesar.php` ejecuta en orden: recibir y sanear → validar campos vacíos → normalizar textos → validar fecha y edad → validar la foto → guardar la foto → mostrar el resultado o la lista de errores.
4. Si hay errores, se listan y se ofrece volver al formulario. Si todo es válido, se muestra la ficha del aspirante.

## 6. Funciones de PHP utilizadas

**Saneamiento y seguridad**
- `strip_tags()`: elimina etiquetas HTML y PHP de la entrada.
- `htmlspecialchars()`: convierte caracteres especiales en entidades HTML al imprimir. Previene ataques **XSS**.

**Normalización y limpieza**
- `trim()`: quita espacios al inicio y al final.
- `mb_strtolower()` + `mb_convert_case(..., MB_CASE_TITLE)`: formato título para nombre y apellido.
- `mb_strtoupper()`: identificación en mayúsculas (equivale a `strtoupper()`).

**Archivos y fechas**
- `is_uploaded_file()` y `move_uploaded_file()`: comprueban y mueven el archivo subido.
- `finfo` y `getimagesize()`: verifican que el contenido sea realmente una imagen.
- `pathinfo()`: obtiene la extensión.
- `DateTime::createFromFormat()` y `diff()`: validan la fecha y calculan la edad.
- `basename()`: detecta la página actual para el breadcrumb.
- `include`: reutiliza header, footer y formulario.

## 7. Medidas de seguridad

- **XSS:** todo dato del usuario se escapa con `htmlspecialchars()` antes de imprimirse.
- **Subida de archivos:** lista blanca de extensiones, verificación del tipo MIME real, límite de tamaño y nombre aleatorio generado por el servidor. Los archivos no se ejecutan y no se pueden consultar desde el navegador.
- **Método HTTP:** `procesar.php` solo acepta **POST** y redirige a `index.php` en cualquier otro caso.
- **Validación doble:** el HTML5 (`required`, `type="date"`, `accept`) mejora la experiencia, pero la validación real se hace en el servidor.
- **Metadato `robots`** con `noindex, nofollow` para evitar la indexación de las páginas de pruebas.

## 8. Metadatos incluidos

`charset`, `viewport` (`initial-scale=1.0`), `description`, `author`, `robots` y `theme-color` (`#212529`).

## 9. Notas

- Los enlaces de redes sociales y el correo del footer son de ejemplo.
- La ruta de la carpeta de fotos es `./uploaded_files/`.

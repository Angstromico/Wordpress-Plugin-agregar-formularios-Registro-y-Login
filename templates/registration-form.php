<?php
if (!defined('ABSPATH')) exit;
?>

<form id="registration-form" enctype="multipart/form-data" method="POST">
    <label>Nombre de Usuario:</label>
    <input type="text" name="username" required>

    <label>Correo Electrónico:</label>
    <input id="email" type="email" name="email" required>

    <label>Confirmar Correo:</label>
    <input id="confirm-email" type="email" name="confirm_email" required>

    <label>Contraseña:</label>
    <input id="password" type="password" name="password" required>

    <label>Confirmar Contraseña:</label>
    <input id="confirm-password" type="password" name="confirm_password" required>

    <!-- Mensaje de error para las contraseñas -->
    <div id="password-error" style="display: none; color: red; font-size: 0.9em;"></div>

    <label>Imagen de Perfil (opcional):</label>
    <input type="file" name="profile_image" accept="image/*">

    <input type="submit" name="submit_registration" value="Registrarse">
</form>

<!-- Div para el mensaje de éxito o error -->
<div id="registration-message" style="color: green; font-weight: bold; padding-top: 10px;">
    <?php echo isset($message) ? esc_html($message) : ''; ?>
</div>

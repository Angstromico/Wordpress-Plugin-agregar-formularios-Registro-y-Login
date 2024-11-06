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

    <label>Rol de Usuario:</label>
    <select name="user_role" id="user_role" required>
        <option value="customer">Cliente</option>
        <option value="vendedor">Vendedor</option>
    </select>

    <div id="vendedor-message" style="display: none; color: red; font-size: 0.9em;">
        Al registrarse como vendedor, debe aceptar nuestro programa de descuentos con Obbaracoins.
    </div>

    <label>Imagen de Perfil (opcional):</label>
    <input type="file" name="profile_image" accept="image/*">

    <input type="submit" name="submit_registration" value="Registrarse">
</form>

<div id="registration-message" style="color: green; font-weight: bold; padding-top: 10px;">
    <?php echo isset($message) ? esc_html($message) : ''; ?>
</div>

<!-- Mostrar mensaje de error si existe -->
<?php if (!empty($_SESSION['custom_registration_error'])): ?>
    <div id="error-message" style="color: red; font-weight: bold; padding-top: 10px;">
        <?php echo esc_html($_SESSION['custom_registration_error']); unset($_SESSION['custom_registration_error']); ?>
    </div>
<?php endif; ?>


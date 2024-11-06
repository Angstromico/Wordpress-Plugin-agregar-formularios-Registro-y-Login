<?php
 if (!defined('ABSPATH')) exit;
?>

<form id="login-form" method="POST">
    <label for="username">Nombre de Usuario o Correo Electrónico:</label>
    <input type="text" name="username" required>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" required>

    <input type="submit" name="submit_login" value="Iniciar Sesión">
</form>

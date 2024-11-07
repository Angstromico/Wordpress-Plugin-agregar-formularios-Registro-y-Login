<?php
 if (!defined('ABSPATH')) exit;
?>

<form id="login-form" method="POST">
    <label for="username">Nombre de Usuario o Correo Electrónico:</label>
    <input type="text" name="username" required>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" required>

    <input type="submit" name="submit_login" value="Iniciar Sesión" style="width: 100%; padding: 1rem; background: #4a90e2; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background-color 0.3s;">
</form>

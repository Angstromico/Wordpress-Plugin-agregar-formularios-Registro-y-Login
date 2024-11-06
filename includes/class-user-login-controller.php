<?php

if (!defined('ABSPATH')) exit;

class User_Login_Controller {
    public function __construct() {
        add_shortcode('custom_login_form', [$this, 'display_login_form']);
    }

    public function display_login_form() {
        ob_start();
        ?>
        <form id="login-form" method="POST">
            <label>Nombre de Usuario o Correo:</label>
            <input type="text" name="username" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <input type="submit" name="submit_login" value="Iniciar Sesión">
        </form>
        <?php
        return ob_get_clean();
    }
}

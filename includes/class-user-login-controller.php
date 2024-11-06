<?php

if (!defined('ABSPATH')) exit;

class User_Login_Controller {
    public function __construct() {
        add_shortcode('custom_login_form', [$this, 'display_login_form']);
        add_action('init', [$this, 'process_login_form']); // Procesar el formulario en 'init'
        if (!session_id()) session_start(); // Iniciar sesión si no ha sido iniciada
    }

    public function display_login_form() {
        // Obtener el mensaje de sesión (éxito o error) si está definido
        $message = isset($_SESSION['custom_login_message']) ? $_SESSION['custom_login_message'] : '';
        unset($_SESSION['custom_login_message']); // Limpiar el mensaje después de mostrarlo
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/login-form.php';  // Asegúrate de tener el formulario en este archivo
        echo "<div id='login-message' style='color: red; font-weight: bold; padding-top: 10px;'>{$message}</div>";
        return ob_get_clean();
    }

    public function process_login_form() {
        if (isset($_POST['submit_login'])) {
            $username = sanitize_user($_POST['username']);
            $password = sanitize_text_field($_POST['password']);

            // Validar que los campos no estén vacíos
            if (empty($username) || empty($password)) {
                $_SESSION['custom_login_message'] = 'Por favor, ingrese ambos campos.';
                return;
            }

            // Autenticar usuario
            $user = wp_authenticate($username, $password);

            if (is_wp_error($user)) {
                $_SESSION['custom_login_message'] = 'Error en el inicio de sesión: ' . $user->get_error_message();
                return;
            }

            // Iniciar sesión
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            // Redirigir a la página de inicio o a la página solicitada
            wp_redirect(home_url());
            exit;
        }
    }
}

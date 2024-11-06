<?php

if (!defined('ABSPATH')) exit;

class User_Registration_Controller {
    public function __construct() {
        add_shortcode('custom_registration_form', [$this, 'display_registration_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('init', [$this, 'process_registration_form']); // Procesar el formulario en 'init'
        if (!session_id()) session_start(); // Iniciar sesión si no ha sido iniciada
    }

    public function enqueue_scripts() {
        wp_enqueue_style('custom-registration-style', plugin_dir_url(__FILE__) . '../assets/style.css');
        wp_enqueue_script('custom-registration-script', plugin_dir_url(__FILE__) . '../assets/script.js', ['jquery'], null, true);
    }

    public function display_registration_form() {
        // Obtener el mensaje de sesión (éxito o error) si está definido
        $message = isset($_SESSION['custom_registration_message']) ? $_SESSION['custom_registration_message'] : '';
        unset($_SESSION['custom_registration_message']); // Limpiar el mensaje después de mostrarlo
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/registration-form.php';
        echo "<div id='registration-message' style='color: green; font-weight: bold; padding-top: 10px;'>{$message}</div>";
        return ob_get_clean();
    }

    public function process_registration_form() {
        if (isset($_POST['submit_registration'])) {
            $username = sanitize_user($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $confirm_email = sanitize_email($_POST['confirm_email']);
            $password = sanitize_text_field($_POST['password']);
            $confirm_password = sanitize_text_field($_POST['confirm_password']);
            $profile_image = $_FILES['profile_image'];

            // Validación de correos electrónicos
            if ($email !== $confirm_email) {
                $_SESSION['custom_registration_message'] = 'Los correos electrónicos no coinciden.';
                return;
            }

            // Validación de contraseñas
            if ($password !== $confirm_password) {
                $_SESSION['custom_registration_message'] = 'Las contraseñas no coinciden.';
                return;
            }

            // Crear usuario
            $user_id = wp_create_user($username, $password, $email);
            if (is_wp_error($user_id)) {
                $_SESSION['custom_registration_message'] = 'Error en el registro: ' . $user_id->get_error_message();
                return;
            }

            // Asignar rol de cliente al usuario creado
            wp_update_user(['ID' => $user_id, 'role' => 'customer']);

            // Procesar la imagen de perfil si se ha subido
            if (!empty($profile_image['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                $uploaded_image = wp_handle_upload($profile_image, ['test_form' => false]);
                if (!isset($uploaded_image['error'])) {
                    update_user_meta($user_id, 'profile_image', $uploaded_image['url']);
                }
            }

            // Mensaje de éxito en el registro
            $_SESSION['custom_registration_message'] = 'Usuario registrado exitosamente.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }
    }
}



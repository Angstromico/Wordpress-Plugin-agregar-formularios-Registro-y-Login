<?php

if (!defined('ABSPATH')) exit;

class User_Registration_Controller {
    public function __construct() {
        add_shortcode('custom_registration_form', [$this, 'display_registration_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('init', [$this, 'process_registration_form']);
        if (!session_id()) session_start();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('custom-registration-style', plugin_dir_url(__FILE__) . '../assets/style.css');
        wp_enqueue_script('custom-registration-script', plugin_dir_url(__FILE__) . '../assets/script.js', ['jquery'], null, true);
    }

    public function display_registration_form() {
        $message = isset($_SESSION['custom_registration_message']) ? $_SESSION['custom_registration_message'] : '';
        unset($_SESSION['custom_registration_message']);
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
        $role = sanitize_text_field($_POST['user_role']);

        // Inicializar mensajes de error
        $_SESSION['custom_registration_error'] = '';

        if ($email !== $confirm_email) {
            $_SESSION['custom_registration_error'] = 'Los correos electrónicos no coinciden.';
            return;
        }

        if ($password !== $confirm_password) {
            $_SESSION['custom_registration_error'] = 'Las contraseñas no coinciden.';
            return;
        }

        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            $_SESSION['custom_registration_error'] = 'Error en el registro: ' . $user_id->get_error_message();
            return;
        }

        wp_update_user(['ID' => $user_id, 'role' => $role === 'vendedor' ? 'vendedor' : 'customer']);

        if (!empty($profile_image['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploaded_image = wp_handle_upload($profile_image, ['test_form' => false]);
            if (!isset($uploaded_image['error'])) {
                update_user_meta($user_id, 'profile_image', $uploaded_image['url']);
            }
        }

        $_SESSION['custom_registration_message'] = 'Usuario registrado exitosamente.';
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }
}
}


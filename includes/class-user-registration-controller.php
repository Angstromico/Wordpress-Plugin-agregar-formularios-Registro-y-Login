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
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/registration-form.php';
        return ob_get_clean();
    }

    public function process_registration_form() {
        if (!isset($_POST['submit_registration'])) {
            return;
        }

        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $confirm_email = sanitize_email($_POST['confirm_email']);
        $password = sanitize_text_field($_POST['password']);
        $confirm_password = sanitize_text_field($_POST['confirm_password']);
        $profile_image = $_FILES['profile_image'];
        $role = sanitize_text_field($_POST['user_role']);

        // Validate required fields
        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['custom_registration_error'] = 'Todos los campos son obligatorios.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Validate email match
        if ($email !== $confirm_email) {
            $_SESSION['custom_registration_error'] = 'Los correos electrónicos no coinciden.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Validate password match
        if ($password !== $confirm_password) {
            $_SESSION['custom_registration_error'] = 'Las contraseñas no coinciden.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Validate password strength
        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)
            /* Intente con esta contrasena y no me funciono: DvQzxycjg7Y6c5tA, por que no pasa la prueba? */
        ) {
            $_SESSION['custom_registration_error'] = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Check if username or email already exists
        if (username_exists($username) || email_exists($email)) {
            $_SESSION['custom_registration_error'] = 'El nombre de usuario o correo electrónico ya está registrado.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Create user only if all validations pass
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            $_SESSION['custom_registration_error'] = 'Error en el registro: ' . $user_id->get_error_message();
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Set user role
        wp_update_user([
            'ID' => $user_id, 
            'role' => $role === 'vendedor' ? 'vendedor' : 'customer'
        ]);

        // Handle profile image upload
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


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
        if ($role === 'shop manager' && wp_roles()->is_role('shop_manager')) {
            wp_update_user([
                'ID' => $user_id,
                'role' => 'shop_manager'
            ]);
        } else {
        wp_update_user([
            'ID' => $user_id,
            'role' => 'customer'
            ]);
        }

        // Manejo de la subida de la imagen de perfil
    if (!empty($profile_image['name'])) {
        // Validar errores de subida
        if ($profile_image['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['custom_registration_error'] = 'Error al subir la imagen: ' . $this->get_upload_error_message($profile_image['error']);
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Validar tipo de archivo
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = wp_check_filetype($profile_image['name']);
        
        if (!in_array($profile_image['type'], $allowed_types) || !$file_type['ext']) {
            $_SESSION['custom_registration_error'] = 'Tipo de archivo no permitido. Por favor, use JPG, PNG o GIF.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Directorio de subida personalizado
        $upload_dir = wp_upload_dir();
        $user_dirname = $upload_dir['basedir'] . '/profile-images';

        if (!file_exists($user_dirname)) {
            wp_mkdir_p($user_dirname);
        }

        // Asignar un nombre único al archivo que incluya el ID de usuario
        $filename = 'user_id=' . $user_id . '.' . $file_type['ext'];
        $file_path = $user_dirname . '/' . $filename;

        // Mover archivo subido
        if (!move_uploaded_file($profile_image['tmp_name'], $file_path)) {
            $_SESSION['custom_registration_error'] = 'Error al mover el archivo subido.';
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        }

        // Crear post de attachment en WordPress
        $attachment = array(
            'post_mime_type' => $profile_image['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $file_path);

        if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // Generar metadatos de attachment
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            // Guardar el ID de attachment como user meta
            update_user_meta($user_id, 'profile_image', $attachment_id);
        }
    }

        $_SESSION['custom_registration_message'] = 'Usuario registrado exitosamente.';
        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }
    private function get_upload_error_message($error_code) {
        $upload_errors = array(
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por PHP.',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente.',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo en el disco.',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida del archivo.'
        );
        
        return isset($upload_errors[$error_code]) 
            ? $upload_errors[$error_code] 
            : 'Error desconocido al subir el archivo.';
    }
}


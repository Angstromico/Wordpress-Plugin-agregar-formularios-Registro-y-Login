<?php

class User_Profile_Model {
    // Método para registrar un usuario
    public function register_user($username, $email, $password, $profile_image = null) {
        // Verificar que no exista un usuario con el mismo nombre o correo
        if (username_exists($username) || email_exists($email)) {
            return new WP_Error('registration_error', 'El usuario o correo ya están en uso.');
        }

        // Crear un nuevo usuario
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            return $user_id; // Retorna el error si no se pudo crear el usuario
        }

        // Actualizar el rol a "cliente" de WooCommerce
        $user = new WP_User($user_id);
        $user->set_role('customer');

        // Guardar la imagen de perfil si existe
        if ($profile_image) {
            $this->save_profile_image($user_id, $profile_image);
        }

        return $user_id;
    }

    // Método para guardar la imagen de perfil
    private function save_profile_image($user_id, $profile_image) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $uploadedfile = $profile_image;
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            // Asignar la imagen como un meta dato del usuario
            update_user_meta($user_id, 'profile_image', $movefile['url']);
        }
    }

    // Método para obtener la URL de la imagen de perfil del usuario
    public function get_profile_image_url($user_id) {
        return get_user_meta($user_id, 'profile_image', true);
    }
}

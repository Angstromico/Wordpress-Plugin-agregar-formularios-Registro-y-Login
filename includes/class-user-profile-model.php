<?php

if (!defined('ABSPATH')) exit;

class User_Profile_Model {
    public function __construct() {
        add_shortcode('custom_user_profile', [$this, 'display_user_profile']);
    }

    public function display_user_profile() {
        if (!is_user_logged_in()) {
            return '<p class="error-message">Debes iniciar sesión para ver tu perfil.</p>';
        }

        $user = wp_get_current_user();
        $profile_image_id = get_user_meta($user->ID, 'profile_image', true);

        // Verificar si existe una imagen de perfil en el sistema de archivos
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['baseurl'] . '/profile-images/user_id=' . $user->ID;

        if ($profile_image_id) {
            // Si el ID de attachment está en la base de datos, usar la URL de WordPress
            $profile_image_url = wp_get_attachment_url($profile_image_id);
        } elseif (file_exists($file_path)) {
        // Si no está registrado en la base de datos, cargar desde el directorio directamente
        $profile_image_url = $file_path;
        } else {
            // Fallback a una imagen predeterminada si no hay imagen de perfil
            $profile_image_url = $upload_dir['baseurl'] . '/profile-images/default-profile.jpg';
        }

        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/user-profile.php';
        return ob_get_clean();
    }
}



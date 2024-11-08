<?php
/**
 * Plugin Name: Custom User Registration and Login
 * Description: Plugin personalizado para registrar y autenticar usuarios de manera segura.
 * Version: 1.0
 * Author: Manuel Morales
 * Author URI: https://github.com/Angstromico
 * Text Domain: custom-user-registration
 */

if (!defined('ABSPATH')) exit;

// Define plugin constants
define('CUSTOM_USER_REG_PATH', plugin_dir_path(__FILE__));
define('CUSTOM_USER_REG_URL', plugin_dir_url(__FILE__));
define('CUSTOM_USER_REG_VERSION', '1.0.0');

// Cargar archivos principales
include_once CUSTOM_USER_REG_PATH . 'includes/class-user-registration-controller.php';
include_once CUSTOM_USER_REG_PATH . 'includes/class-user-login-controller.php';
include_once CUSTOM_USER_REG_PATH . 'includes/class-user-profile-model.php';

// Función para encolar el CSS y JavaScript
function custom_user_registration_enqueue_scripts() {
    // Encolar el archivo CSS con mayor especificidad
    wp_enqueue_style(
        'custom-user-registration-css',
        CUSTOM_USER_REG_URL . 'assets/css/style.css',
        array(),
        CUSTOM_USER_REG_VERSION
    );

    // Encolar el archivo JavaScript
    wp_enqueue_script(
        'custom-user-registration-js',
        CUSTOM_USER_REG_URL . 'assets/js/script.js',
        array('jquery'),
        CUSTOM_USER_REG_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'custom_user_registration_enqueue_scripts');

// Activar el controlador de registro
$registration_controller = new User_Registration_Controller();
// Activar el controlador de login
$login_controller = new User_Login_Controller();
//Activar Perfil de Usuario 
$user_controller = new User_Profile_Model();
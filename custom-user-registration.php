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

// Cargar archivos principales
include_once plugin_dir_path(__FILE__) . 'includes/class-user-registration-controller.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-user-login-controller.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-user-profile-model.php';

// Función para encolar el CSS y JavaScript
function custom_user_registration_enqueue_scripts() {
    // Encolar el archivo CSS
    wp_enqueue_style(
        'custom-user-registration-css',
        plugin_dir_url(__FILE__) . 'assets/style.css', 
        array(),
        '1.0',
        'all'
    );

    // Encolar el archivo JavaScript
    wp_enqueue_script(
        'custom-user-registration-js',
        plugin_dir_url(__FILE__) . 'assets/script.js',
        array('jquery'),
        '1.0',
        true
    );
}

// Llamar a la función para encolar los archivos en el frontend
add_action('wp_enqueue_scripts', 'custom_user_registration_enqueue_scripts');

// Activar el controlador de registro
$registration_controller = new User_Registration_Controller();
// Activar el controlador de login
$login_controller = new User_Login_Controller();


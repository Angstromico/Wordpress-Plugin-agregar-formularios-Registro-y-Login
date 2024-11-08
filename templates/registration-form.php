<?php
    if (!defined('ABSPATH')) exit;
?>
<?php if (!empty($_SESSION['custom_registration_message']) || !empty($_SESSION['custom_registration_error'])): ?>
    <div class="registration-container" style="max-width: 800px; margin: 2rem auto;">
        <div class="message-container" style="margin-bottom: 1rem;">
            <?php if (!empty($_SESSION['custom_registration_message'])): ?>
                <div class="success-message" style="display: block; width: 100%; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; font-weight: 500; background-color: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); color: #28a745;">
                    <?php echo esc_html($_SESSION['custom_registration_message']); unset($_SESSION['custom_registration_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($_SESSION['custom_registration_error'])): ?>
                <div class="error-message" style="display: block; width: 100%; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; font-weight: 500; background-color: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); color: #dc3545;">
                    <?php echo esc_html($_SESSION['custom_registration_error']); unset($_SESSION['custom_registration_error']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<form id="registration-form" enctype="multipart/form-data" method="POST">
    <label>Nombre de Usuario:</label>
    <input type="text" name="username" required>

    <label>Correo Electrónico:</label>
    <input id="email" type="email" name="email" required>

    <label>Confirmar Correo:</label>
    <input id="confirm-email" type="email" name="confirm_email" required>

    <label>Contraseña:</label>
    <input id="password" type="password" name="password" required>

    <label>Confirmar Contraseña:</label>
    <input id="confirm-password" type="password" name="confirm_password" required>

    <label>Rol de Usuario:</label>
    <select name="user_role" id="user_role" required style="width: 100%; padding: 1rem; border: 2px solid #e1e1e1; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s, box-shadow 0.3s; background-color: #f8f9fa; margin-bottom: 1rem; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 12 12%22%3E%3Cpath fill=%22%23333%22 d=%22M6 8L1 3h10z%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; padding-right: 2.5rem;">
        <option value="customer">Cliente</option>
        <option value="shop manager">Vendedor</option>
    </select>

    <div id="vendedor-message" style="display: none; color: red; font-size: 0.9em;">
        Al registrarse como vendedor, debe aceptar nuestro programa de descuentos con Obbaracoins.
    </div>

    <label>Imagen de Perfil (opcional):</label>
    <input type="file" name="profile_image" accept="image/jpeg,image/png,image/gif" style="width: 100%; padding: 0.6rem; background: #f8f9fa; border: 2px dashed #e1e1e1; border-radius: 8px; margin-bottom: 1rem;">

     <div class="file-requirements" style="margin-top: 0.5rem; font-size: 0.8rem; color: #666;">
                Formatos permitidos: JPG, PNG, GIF (Máx. 5MB)
            </div>

    <input type="submit" name="submit_registration" value="Registrarse" style="width: 100%; padding: 1rem; background: #4a90e2; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background-color 0.3s;">
</form>

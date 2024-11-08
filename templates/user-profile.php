<?php if (!defined('ABSPATH')) exit; ?>

<div id="user-profile" style="max-width: 800px; margin: 2rem auto;">
    <div class="profile-container" style="text-align: center; padding: 2rem; background: #ffffff; border-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 1.5rem; color: #333;">Perfil de Usuario</h2>
        <?php if ($profile_image_url): ?>
            <div class="profile-image" style="margin-top: 1rem;">
                <img src="<?php echo esc_url($profile_image_url); ?>" alt="Imagen de Perfil" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover;">
            </div>
        <?php endif; ?>

        <div class="user-info" style="margin-top: 2rem;">
            <p><strong>Nombre de Usuario:</strong> <?php echo esc_html($user->user_login); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo esc_html($user->user_email); ?></p>

            <?php
            // Traducción de roles específicos
            $roles = array_map(function($role) {
                switch ($role) {
                    case 'customer':
                        return 'Cliente';
                    case 'shop_manager':
                        return 'Vendedor';
                    default:
                        return ucfirst($role); // Deja los otros roles tal como están
                }
            }, $user->roles);
            ?>
            <p><strong>Rol de Usuario:</strong> <?php echo esc_html(implode(', ', $roles)); ?></p>
        </div>

        <div class="custom-actions" style="margin-top: 2rem;">
            <a href="<?php echo wp_logout_url(home_url()); ?>" style="color: #357abd; text-decoration: underline;">Cerrar Sesión</a>
        </div>
    </div>
</div>

<?php
// Obtiene el usuario actualmente conectado en WordPress
$user = wp_get_current_user();

// Verifica si hay un usuario activo y muestra su avatar
if ($user) :
?>
    <!-- Muestra la imagen de perfil (avatar) del usuario actual -->
    <img src="<?php echo esc_url(get_avatar_url($user->ID)); ?>" alt="Perfil del Ponente" class="img-fluid rounded thumb">
<?php endif; ?>



<!---Nombre de autor:--->
<?php echo esc_html( get_the_author() );?>
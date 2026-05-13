<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Carbar
 */

?>

<!-- FOOTER ACTUALIZADO -->
<!-- FOOTER -->
<footer>
    <div class="footer-container">
        <div class="col logo-footer">
            <?php dynamic_sidebar('footer_1') ?>
        </div>

        <div class="col">
            <?php dynamic_sidebar('footer_2') ?>
        </div>

        <div class="col">
            <?php dynamic_sidebar('footer_3') ?>
        </div>

        <div class="col contacto-col">
            <?php dynamic_sidebar('footer_4') ?>
        </div>
    </div>

    <div class="footer-bottom text-black">
<p><i class="fa-regular fa-copyright"></i> <?php echo date('Y'); ?> Carbar Propiedades. Todos los derechos reservados.</p>
    </div>
</footer>


<!-- Botón subir -->
<button class="scroll-toggle" aria-label="Subir/Bajar">
    <i class="fa-solid fa-chevron-up"></i>
</button>
<!-- Botón WhatsApp -->
<a href="https://wa.me/56940948302" class="whatsapp-float text-white" target="_blank" aria-label="Contactar por WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>

</div> <!-- #page -->

<?php wp_footer(); ?>
</body>

</html>
<?php 
function zona_widget()
{
    /*zona de widget 1*/
    register_sidebar(
        array(
            'name' => 'Footer columna 1',
            'id' => 'footer_1', //le damos ID y nombre al footer
            'before_widget' => '<div id="%1$s" class="footer-logo">', //añadimos clases y contenedores
            'after_widget' => '</div>', //cerramos los contenedores 
        )
    );
    /*zona de widget 1*/
    /*zona de widget 2*/
    register_sidebar(
        array(
            'name' => 'Footer columna 2',
            'id' => 'footer_2', //le damos ID y nombre al footer
            'before_widget' => '<div id="%1$s">', //añadimos clases y contenedores
            'after_widget' => '</div>', //cerramos los contenedores 
            'before_title' => '<h4', //añadimos contenedores para titulo
            'after_title' => '</h4>' //cerramos los contenedores de titulo
        )
    );
    /*zona de widget 2*/

/*zona de widget 3*/
      register_sidebar(
        array(
            'name' => 'Footer columna 3',
            'id' => 'footer_3', //le damos ID y nombre al footer
            'before_widget' => '<div id="%1$s">', // CORREGIDO: se añadió el ">" de cierre
            'after_widget' => '</div>', //cerramos los contenedores 
            'before_title' => '<h4>', //añadimos contenedores para titulo
            'after_title' => '</h4>' //cerramos los contenedores de titulo
        )
    );
    /*zona de widget 3*/

      /*zona de widget 4*/
      register_sidebar(
        array(
            'name' => 'Footer columna 4',
            'id' => 'footer_4', //le damos ID y nombre al footer
            'before_widget' => '<div id="%1$s" class="">', //añadimos clases y contenedores
            'after_widget' => '</div>', //cerramos los contenedores 
        )
    );
    /*zona de widget 4*/

}
add_action('widgets_init', 'zona_widget');
/*widget assets*/
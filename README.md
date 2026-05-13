# Estructura General del Proyecto

Los proyectos se desarrollan utilizando una estructura modular en WordPress con el objetivo de mantener una organización clara, escalable y fácil de administrar. Cada sección del tema cumple una función específica para separar correctamente la lógica, estilos, scripts y componentes personalizados del sistema.

## template-parts/

En esta carpeta se encuentran las plantillas reutilizables del proyecto. Aquí se almacenan las estructuras correspondientes a páginas, entradas y distintos componentes utilizados dentro del sitio.

La finalidad de esta organización es mantener una separación clara entre las distintas vistas del proyecto, permitiendo reutilizar componentes y facilitar el mantenimiento del código.

Dentro de esta sección se pueden encontrar:

- plantillas de páginas
- estructuras para entradas
- componentes reutilizables
- secciones dinámicas del sitio

## assets/

La carpeta assets contiene todos los recursos visuales y funcionales utilizados dentro del proyecto.

## assets/libreria/css/

Aquí se almacenan todos los archivos CSS utilizados en el desarrollo del sitio, incluyendo:

- estilos globales
- diseño responsive
- componentes visuales
- animaciones
- personalizaciones del tema

## assets/libreria/js/

Contiene todos los archivos JavaScript del proyecto, encargados de funcionalidades dinámicas como:

- sliders y carruseles
- filtros
- interacciones dinámicas
- peticiones AJAX
- comportamiento personalizado de componentes

## includes/

La carpeta includes se utiliza para centralizar y organizar los hooks y configuraciones generales del tema.

Dentro de esta sección se encuentran archivos encargados de registrar y cargar automáticamente distintas funcionalidades del proyecto, manteniendo una arquitectura más modular y limpia.

Ejemplos:

- carga automática de archivos CSS
- carga de scripts JavaScript
- configuración de menús
- navegación personalizada
- inicialización de módulos
- configuraciones generales del tema

Esta metodología permite separar correctamente la lógica del proyecto y mejorar la mantenibilidad del código.

## modulos/

La carpeta modulos contiene todas las funcionalidades personalizadas que modifican o extienden el comportamiento de WordPress.

Aquí se desarrollan módulos encargados de agregar nuevas características al panel de administración o al funcionamiento general del sitio.

Entre las funcionalidades que pueden encontrarse:

- creación de taxonomías personalizadas
- nuevos apartados en el menú lateral de WordPress
- administración de contenido dinámico
- personalización del panel administrativo
- módulos específicos del proyecto

Esta estructura permite mantener las funcionalidades desacopladas y facilita futuras mejoras o ampliaciones del sistema.

# Objetivo de la Arquitectura

La estructura modular utilizada en los proyectos busca mantener una organización clara entre vistas, estilos, scripts y funcionalidades personalizadas, permitiendo desarrollar sitios más mantenibles, escalables y fáciles de administrar tanto a nivel visual como funcional.

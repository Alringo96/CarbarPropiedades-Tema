
    const menuBtn = document.getElementById('menuBtn');
    const menu = document.querySelector('.navbar ul.menu');

    menuBtn.addEventListener('click', () => {
        menu.classList.toggle('active');

        // Cambiar texto del botón
        if (menu.classList.contains('active')) {
            menuBtn.textContent = 'CERRAR'; // Menú abierto
            menuBtn.style.backgroundColor = '#2d3f73'; // Opcional: cambiar color
        } else {
            menuBtn.textContent = 'MENÚ'; // Menú cerrado
            menuBtn.style.backgroundColor = '#4A60A1'; // Color original
        }
    });
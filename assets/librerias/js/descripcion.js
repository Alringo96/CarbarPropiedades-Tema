    const btn = document.querySelector('.descripcion .ver-mas');
    const p = document.querySelector('.descripcion p');
    if (btn && p) {
        btn.addEventListener('click', () => {
            p.classList.toggle('expandido');
            btn.textContent = p.classList.contains('expandido') ? 'Ver menos' : 'Ver más';
        });
    }
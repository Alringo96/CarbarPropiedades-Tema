  // Animación del Hero - Transición de imágenes
    const heroBgs = document.querySelectorAll('.hero-bg');
    let currentBg = 0;
    
    function changeHeroBg() {
      // Remover clase active de todas las imágenes
      heroBgs.forEach(bg => bg.classList.remove('active'));
      
      // Incrementar índice
      currentBg = (currentBg + 1) % heroBgs.length;
      
      // Agregar clase active a la imagen actual
      heroBgs[currentBg].classList.add('active');
    }
    
    // Cambiar imagen cada 5 segundos
    setInterval(changeHeroBg, 5000);

   
    const faders = document.querySelectorAll('.fade-section');

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          // opcional: dejar de observar una vez visible
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.35 });

    faders.forEach(f => observer.observe(f));

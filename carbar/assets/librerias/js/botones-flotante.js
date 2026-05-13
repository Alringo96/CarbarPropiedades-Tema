document.addEventListener("DOMContentLoaded", () => {
  const scrollBtn = document.querySelector(".scroll-toggle");

  // Al hacer clic, sube suavemente al inicio
  scrollBtn.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });

  // Mostrar u ocultar el botón según la posición del scroll
  window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
      scrollBtn.classList.remove("hidden");
    } else {
      scrollBtn.classList.add("hidden");
    }
  });
});

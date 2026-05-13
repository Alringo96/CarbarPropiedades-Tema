const mvTabs = document.querySelectorAll(".mv-tab");
const mvText = document.getElementById("mv-text");
const dots = document.querySelectorAll(".dot");
const missionVisionSection = document.querySelector(".mission-vision");

mvTabs.forEach((tab, index) => {
  tab.addEventListener("mouseenter", () => updateMV(tab, index));
  tab.addEventListener("click", () => updateMV(tab, index));
});

function updateMV(tab, index) {
  mvTabs.forEach(t => t.classList.remove("active"));
  dots.forEach(d => d.classList.remove("active"));
  tab.classList.add("active");
  dots[index].classList.add("active");

  mvText.style.opacity = 0;
  setTimeout(() => {
    mvText.textContent = textos[tab.dataset.target];
    mvText.style.opacity = 1;
  }, 200);
  
  if (tab.dataset.target === "vision") {
    missionVisionSection.classList.add("vision-active");
  } else {
    missionVisionSection.classList.remove("vision-active");
  }
}

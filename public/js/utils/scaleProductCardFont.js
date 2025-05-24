export function resizeProductCardTitles() {
  const productCards = document.querySelectorAll(".product-card");

  productCards.forEach((card) => {
    const name = card.querySelector(".product-card .name");
    const desc = card.querySelector(".product-card .desc");
    const price = card.querySelector(".product-card .bottom-row");
    const cardWidth = card.clientWidth;
    const nameFontSize = Math.max(Math.floor(cardWidth / 15), 14);
    const descFontSize = Math.floor(cardWidth / 17);
    const priceFontSize = Math.floor(cardWidth / 20);

    name.style.fontSize = nameFontSize + "px";
    desc.style.fontSize = descFontSize + "px";
    price.style.fontSize = Math.max(priceFontSize, 16) + "px";
    
    name.style.maxHeight = (nameFontSize * 1.4 * 2) + "px";
    desc.style.maxHeight = (descFontSize * 1.4 * 3) + "px";
  });  
}

let resizeTimeout;
window.addEventListener("resize", function () {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(resizeProductCardTitles, 100);
});

// Initial load
document.addEventListener("DOMContentLoaded", resizeProductCardTitles);

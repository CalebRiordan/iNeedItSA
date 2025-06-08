export function resizeProductCardTitles() {
  const productCards = document.querySelectorAll(".product-card");

  productCards.forEach((card) => {
    
    const name = card.querySelector(".product-card .name");
    const desc = card.querySelector(".product-card .desc");
    const price = card.querySelector(".product-card .bottom-row");
    const originalPrice = card.querySelector(".product-card .original-price");
    const cardWidth = card.clientWidth;
    const nameFontSize = Math.max(Math.floor(cardWidth / 15), 16);
    const descFontSize = Math.max(Math.floor(cardWidth / 17), 12);
    const priceFontSize = Math.max(Math.floor(cardWidth / 14), 14);

    // Scale font
    name.style.fontSize = nameFontSize + "px";
    desc.style.fontSize = descFontSize + "px";
    // Scale line height
    name.style.lineHeight = nameFontSize * 1.2 + "px";
    desc.style.lineHeight = descFontSize * 1.2 + "px";

    // Scale price
    if (originalPrice){
      originalPrice.style.fontSize = (priceFontSize * 0.75) + "px";
    }
    price.style.fontSize = priceFontSize + "px";
    
    // Show 2 lines of the name text, 3 lines of the desc text
    name.style.maxHeight = (nameFontSize * 2 * 1.25) + "px";
    desc.style.maxHeight = (descFontSize * 3 * 1.2) + "px";
  });  
}

let resizeTimeout;
window.addEventListener("resize", function () {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(resizeProductCardTitles, 200);
});

// Initial load
document.addEventListener("DOMContentLoaded", resizeProductCardTitles);

export function resizeProductCardTitles() {
  const productCards = document.querySelectorAll(".product-card");

  productCards.forEach((card) => {
    const name = card.querySelector(".product-card .name");
    const desc = card.querySelector(".product-card .desc");
    const price = card.querySelector(".product-card .bottom-row");
    const originalPrice = card.querySelector(".product-card .original-price");
    const cardWidth = card.clientWidth;

    // Only scale text if the element exists
    if (name) {
      const nameFontSize = Math.max(Math.floor(cardWidth / 15), 13);
      name.style.fontSize = nameFontSize + "px";
      name.style.lineHeight = nameFontSize * 1.2 + "px";
      name.style.maxHeight = (nameFontSize * 2 * 1.25) + "px";
    }

    if (desc) {
      const descFontSize = Math.max(Math.floor(cardWidth / 17), 12);
      desc.style.fontSize = descFontSize + "px";
      desc.style.lineHeight = descFontSize * 1.2 + "px";
      desc.style.maxHeight = (descFontSize * 3 * 1.2) + "px";
    }

    if (price) {
      const priceFontSize = Math.max(Math.floor(cardWidth / 14), 12);
      price.style.fontSize = priceFontSize + "px";
      if (originalPrice) {
        originalPrice.style.fontSize = (priceFontSize * 0.75) + "px";
      }
    }
  });
}

let resizeTimeout;
window.addEventListener("resize", function () {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(resizeProductCardTitles, 200);
});

// Initial load
document.addEventListener("DOMContentLoaded", resizeProductCardTitles);

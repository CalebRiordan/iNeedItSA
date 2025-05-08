// forEach - used to apply JS to all product rows that might be included on a single page

document.querySelectorAll(".product-row-container").forEach((container) => {
  const inner = container.querySelector(".product-row-inner");
  const leftArrow = container.querySelector(".scroll-btn.left");
  const rightArrow = container.querySelector(".scroll-btn.right");

  const updateArrows = () => {
    
    leftArrow.style.display = inner.scrollLeft > 0 ? "block" : "none";
    
    // adjust for unpredictable variations in inner.scrollWidth
    rightArrow.style.display = inner.scrollLeft + inner.offsetWidth < inner.scrollWidth - 5
        ? "block"
        : "none";
  };

  // Initial check
  updateArrows();

  inner.addEventListener("scroll", updateArrows);

  leftArrow.addEventListener("click", () => {
    inner.scrollBy({ left: -1150, behavior: "smooth" });
  });

  rightArrow.addEventListener("click", () => {
    inner.scrollBy({ left: 1150, behavior: "smooth" });
  });

  inner.addEventListener("scrollend", updateArrows);
});

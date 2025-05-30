document.addEventListener("DOMContentLoaded", function () {
  function updateMenuText() {
    const isMobile = window.innerWidth <= 599; // adjust for your breakpoint
    const lastNavItemLabel = document.querySelector(
      ".wp-block-navigation__container li:last-child .wp-block-navigation-item__label"
    );

    if (!lastNavItemLabel) return;

    const originalText =
      lastNavItemLabel.getAttribute("data-original") ||
      lastNavItemLabel.textContent;
    const mobileText = "Contact us"; // your short version here

    // Store original text once
    if (!lastNavItemLabel.getAttribute("data-original")) {
      lastNavItemLabel.setAttribute("data-original", originalText);
    }

    lastNavItemLabel.textContent = isMobile
      ? mobileText
      : lastNavItemLabel.getAttribute("data-original");
  }

  window.addEventListener("resize", updateMenuText);
  updateMenuText(); // Initial run
});

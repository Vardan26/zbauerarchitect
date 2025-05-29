document.addEventListener("DOMContentLoaded", function () {
  const addressLinks = document.querySelectorAll(".address-marker");
  const defaultPin = `${themeData.themeUrl}/assets/images/pin.png`;
  const activePin = `${themeData.themeUrl}/assets/images/pin-active.png`;

  const waitForMap = setInterval(function () {
    if (typeof WPGMZA !== "undefined" && WPGMZA.maps && WPGMZA.maps[0]) {
      clearInterval(waitForMap);

      const map = WPGMZA.maps[0];
      const markers = map.markers;
      const engine = WPGMZA.settings.engine;

      // Reset all markers to default pin and no active class
      markers.forEach((m) => {
        const el = m.element;
        if (el) {
          const img = el.querySelector("img");
          if (img) {
            img.src = defaultPin;
          }
          el.classList.remove("active-marker");
        }
      });

      // Activate first marker and address
      if (markers[0] && markers[0].element) {
        const el = markers[0].element;
        const img = el.querySelector("img");
        if (img) {
          img.src = activePin;
        }
        el.classList.add("active-marker");
        addressLinks[0]?.classList.add("active-address");
      }

      // Add click listeners
      addressLinks.forEach((link, i) => {
        link.addEventListener("click", function (e) {
          e.preventDefault();

          const index = parseInt(link.getAttribute("data-marker")) - 1;
          const marker = markers[index];

          if (!marker || !marker.element) return;

          // Reset all pins and addresses
          markers.forEach((m) => {
            if (m.element) {
              const img = m.element.querySelector("img");
              if (img) {
                img.src = defaultPin;
              }
              m.element.classList.remove("active-marker");
            }
          });

          addressLinks.forEach((a) => a.classList.remove("active-address"));

          // Activate this marker
          const element = marker.element;
          const img = element.querySelector("img");
          if (img) {
            img.src = activePin;
          }
          element.classList.add("active-marker");
          link.classList.add("active-address");

          map.panTo(marker.getPosition());
        });
      });
    }
  }, 500);
});

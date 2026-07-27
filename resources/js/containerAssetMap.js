/**
 * Leaflet map renderer for the Container Inventory detail modal.
 *
 * Leaflet is fetched via a dynamic import() so its bundle only downloads
 * the first time a container's map is actually opened, not on every page
 * load - this wrapper file is bundled globally like the rest of
 * resources/js (per this app's single-entry-point convention), but the
 * heavy dependency behind it stays genuinely lazy.
 */
let mapInstance = null;

async function loadLeaflet() {
  if (window.__leaflet) return window.__leaflet;
  await import("leaflet/dist/leaflet.css");
  const mod = await import("leaflet");
  const L = mod.default ?? mod;

  // Leaflet's default marker icon auto-detects its image path from a
  // <script src="leaflet.js"> tag, which doesn't exist under a bundler -
  // without this the marker renders broken (404s). The icon URLs are
  // still bundled by Vite as regular asset imports, resolved lazily
  // together with the rest of this dynamic import.
  const [iconUrl, iconRetinaUrl, shadowUrl] = await Promise.all([
    import("leaflet/dist/images/marker-icon.png"),
    import("leaflet/dist/images/marker-icon-2x.png"),
    import("leaflet/dist/images/marker-shadow.png"),
  ]);

  L.Icon.Default.mergeOptions({
    iconUrl: iconUrl.default,
    iconRetinaUrl: iconRetinaUrl.default,
    shadowUrl: shadowUrl.default,
  });

  window.__leaflet = L;
  return L;
}

window.renderContainerAssetMap = async function renderContainerAssetMap(elementId, port) {
  const mapEl = document.getElementById(elementId);
  if (!mapEl) return;

  if (mapInstance) {
    mapInstance.remove();
    mapInstance = null;
  }

  const hasCoordinates =
    port && port.latitude !== null && port.latitude !== undefined &&
    port.longitude !== null && port.longitude !== undefined;

  if (!hasCoordinates) {
    mapEl.innerHTML = "";
    mapEl.classList.add("flex", "items-center", "justify-center", "text-xs", "text-zinc-400");
    mapEl.textContent = port
      ? `Location not mapped for ${port.name} yet.`
      : "This container has no current location set.";
    return;
  }

  mapEl.classList.remove("flex", "items-center", "justify-center", "text-xs", "text-zinc-400");
  mapEl.innerHTML = "";

  const L = await loadLeaflet();
  const lat = Number(port.latitude);
  const lng = Number(port.longitude);

  mapInstance = L.map(mapEl).setView([lat, lng], 11);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
    maxZoom: 18,
  }).addTo(mapInstance);
  L.marker([lat, lng]).addTo(mapInstance).bindPopup(port.name);
};

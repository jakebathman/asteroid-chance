import "./bootstrap";
import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import resize from "@alpinejs/resize";
import { animate, scroll } from "https://cdn.jsdelivr.net/npm/motion@latest/+esm"

window.Alpine = Alpine;
window.animate = animate;
window.scroll = scroll;

Alpine.plugin(persist);
Alpine.plugin(resize);

Alpine.start();

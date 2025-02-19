import "./bootstrap";
import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import resize from "@alpinejs/resize";

window.Alpine = Alpine;

Alpine.plugin(persist);
Alpine.plugin(resize);

Alpine.start();

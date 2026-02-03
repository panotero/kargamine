import Swiper from "swiper";
import { Navigation, Pagination, Zoom } from "swiper/modules";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/zoom";

window.Swiper = Swiper;
window.Navigation = Navigation;
window.Pagination = Pagination;
window.Zoom = Zoom;

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
import "./apihandler";
import "./customAlert";
import "./navmenu";
// import "./notificationController";
import "./mailer";
// import "./toast";

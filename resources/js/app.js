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
import "./datatableHandler";
import "./apihandler";
import "./filler";
import "./activityLogger";
import "./graph";
import "./dragdropzone";
import "./documentHandler";
import "./reports";
import "./documentModalHandler";
import "./notificationController";
import "./dashboard";
import "./navmenu";
import "./mailer";
import "./modalController";
import "./mobileCardRenderer";
import "./toast";
import "./customAlert";
import "./pdftoimageConverter";
import "./pdf.worker.min.mjs";
import "./approvalHandler";

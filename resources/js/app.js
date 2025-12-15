// import "./bootstrap";
// import "flowbite";
// import "flowbite-datatables";
// import { DataTable } from "simple-datatables";
// window.DataTable = DataTable;
// import "simple-datatables/dist/style.css";//

//swiper js
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
//end swiper js

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
import "./datatableHandler";
import "./apihandler";
import "./filler";
import "./activityLogger";
import "./documentModalHandler";
import "./notificationController";
import "./dashboard";
import "./navmenu";
import "./mailer";
import "./modalController";
import "./dragdropzone";
import "./mobileCardRenderer";
import "./toast";
import "./customAlert";
import "./documentHandler";
import "./pdftoimageConverter";
import "./pdf.worker.min.mjs";
import "./routeControl";
import "./approvalHandler";

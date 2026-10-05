import Swiper from "swiper";
import { Navigation, Pagination, Zoom } from "swiper/modules";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/zoom";
// import "flowbite";

window.Swiper = Swiper;
window.Navigation = Navigation;
window.Pagination = Pagination;
window.Zoom = Zoom;

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
import "./customFunctions";
import "./datatableHandler";
import "./apihandler";
import "./customAlert";
import "./navmenu";
import "./menuSettings";
import "./teamManagement";
import "./notificationController";
import "./mailer";
import "./toast";

import "./formatter";
import "./logic_crm";
import "./logic_prospect_modal";
import "./logic_prospect_info_modal";
import "./logic_prospect_add_modals";
import "./logic_prospect_request_proposal";
import "./logic_proposal_requests";
import "./logic_proposal_requests_mine";
import "./logic_client_proposals_shared";
import "./remoteTable";
import "./searchableSelect";
import "./containerAssetMap";
import "./containerAssetQr";
import "./pierCheckin";
import "./containerAssignment";

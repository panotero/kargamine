import "./bootstrap";

import Alpine from "alpinejs";
import $ from "jquery";
window.$ = window.jQuery = $;

import Glide from "@glidejs/glide";
window.Glide = Glide;

import "datatables.net-dt";
import "datatables.net-responsive-dt";

import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

import * as XLSX from "xlsx";
window.XLSX = XLSX;
import "flowbite";

import { jsPDF } from "jspdf";
import "jspdf-autotable";
window.jsPDF = jsPDF;

window.Alpine = Alpine;

Alpine.start();

import "./notificationController";
import "./dashboard";
import "./navmenu";
import "./mailer";
import "./modalController";
import "./dragdropzone";
import "./mobileCardRenderer";
import "./toast";
import "./customAlert";
import "./filler";
import "./activityLogger";
import "./documentHandler";
import "./pdftoimageConverter";
import "./pdf.worker.min.mjs";
import "./routeControl";
import "./approvalHandler";
import "./flatpicker";
import "datatables.net-responsive-dt/css/responsive.dataTables.css";
import "./apihandler";

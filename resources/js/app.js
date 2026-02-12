
// import 'bootstrap';

// import 'tabler/dist/js/tabler.min.js';

// /* PLUGINS */
// import 'select2';
// import flatpickr from 'flatpickr';
// window.flatpickr = flatpickr;

// import $ from "jquery";
// window.$ = window.jQuery = $;

// import 'datatables.net-bs5';
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

/* CSS */
import '../sass/tabler.scss';

/* Core */
import 'bootstrap';
import '@tabler/core/dist/js/tabler.min.js';

/* Plugins */
import 'select2';
import 'flatpickr';

/* DataTables (WAJIB urutan ini) */
import 'datatables.net-bs5';
import 'datatables.net-fixedcolumns-bs5';

import './tabler-init';

// import initProjectPage from './pages/project';

// $(document).ready(function () {
//     initProjectPage();
// });

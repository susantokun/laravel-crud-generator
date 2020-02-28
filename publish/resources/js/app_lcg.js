require('./bootstrap');
require('./sb-admin-2');

const Swal = (window.Swal = require("sweetalert2"));
const Chart = (window.Chart = require("chart.js"));

window.Vue = require('vue');
Vue.component('language-switcher', require('./components/LanguageSwitcher.vue').default);

const app = new Vue({
    el: '#app',
});

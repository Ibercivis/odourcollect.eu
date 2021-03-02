
// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */

require('./bootstrap');

// window.Vue = require('vue');

// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });


import Vue from 'vue';

//CSS Basic Library
import 'vuetify/dist/vuetify.min.css';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster/dist/MarkerCluster.css'
import 'leaflet.markercluster/dist/MarkerCluster.Default.css'
import 'leaflet.locatecontrol/dist/L.Control.Locate.min.css'

//JS Basic Library
import 'leaflet/dist/leaflet.js';
import 'vue/dist/vue.min.js';
import '../../public/js/leaflet-list-markers';
import 'leaflet.locatecontrol/dist/L.Control.Locate.min.js'
import 'leaflet.markercluster/dist/leaflet.markercluster.js'
import 'leaflet.featuregroup.subgroup/dist/leaflet.featuregroup.subgroup.js'
import 'dateformat/lib/dateformat.js'

import Vuetify from 'vuetify';
//Languages files from /i18n/index.js
import i18n from './i18n/i18n';
//Routes file from /router/index.js
import router from './router';
//Store / Vuex files from /store/store.js
import store from './store/store';
import Vuelidate from 'vuelidate';
import LyTab from 'ly-tab';
import VueProgress from 'vue-progress';

import App from './views/App'

Vue.use(Vuetify, {
	theme: {
		primary: '#446476',
		secondary: '#00b187',
        accent: "#43bd7a",
        error: "#f44336",
        warning: "#ffeb3b",
        info: "#2196f3",
        success: "#4caf50",
	}
});
Vue.use(Vuelidate);
Vue.use(LyTab);
Vue.use(VueProgress);


window.Event = new class {
	constructor() {
		this.vue = new Vue();
	}
	fire(event, data = null) {
		this.vue.$emit(event, data);
	}
	listen(event, callback) {
		this.vue.$on(event, callback);
	}
}

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    store,
    i18n,
});

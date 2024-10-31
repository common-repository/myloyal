import Vue from 'vue';
window.Vue = require('vue');
// import Multiselect from '@vueform/multiselect';
import VueCompositionAPI from '@vue/composition-api'
import Multiselect from '@vueform/multiselect/dist/multiselect.vue2.js';
import '@vueform/multiselect/themes/default.css';
Vue.use(VueCompositionAPI)
Vue.component('multiselect', Multiselect)

var $ = jQuery;
new Vue({
	el: '#myloyal-app',
	data: myloyal_vuedata.data,
	methods: myloyal_vuedata.methods,
	created: myloyal_vuedata.created,
	mounted: myloyal_vuedata.mounted
});
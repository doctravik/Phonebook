
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.eventDispatcher = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('phones', require('./components/Phone/Phones.vue'));
Vue.component('contacts', require('./components/Contact/Contacts.vue'));
Vue.component('filter-button', require('./components/Contact/FilterButton.vue'));
Vue.component('search-contact', require('./components/Contact/SearchContact.vue'));

const app = new Vue({
    el: '#app'
});

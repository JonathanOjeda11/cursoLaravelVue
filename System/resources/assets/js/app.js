
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('Category', require('./components/Category.vue'));
Vue.component('Articlee', require('./components/Article.vue'));
Vue.component('Client', require('./components/Client.vue'));
Vue.component('Supplier', require('./components/Supplier.vue'));
Vue.component('Rol', require('./components/Rol.vue'));
Vue.component('User', require('./components/User.vue'));
Vue.component('Income', require('./components/Income.vue'));
const app = new Vue({
    el: '#app',
    data:{
        menu : 0
    }

});

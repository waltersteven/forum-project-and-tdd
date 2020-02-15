/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.prototype.authorize = function (handler) {
    //Additional admin privileges
    let user = window.App.user;

    return user ? handler(user) : false;
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret, faHeart } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faUserSecret, faHeart)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.component('flash', require('./components/Flash.vue').default);

Vue.component('paginator', require('./components/Paginator.vue').default);

Vue.component('thread-view', require('./pages/Thread.vue').default);

// Vue.component('reply', require('./components/Reply.vue').default);
// Vue.component('favorite', require('./components/Favorite.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.events = new Vue();

window.flash = function (message) {
    window.events.$emit('flash', message);
}

const app = new Vue({
    el: '#app',
});

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Vue
import Vue from 'vue'

// Vuetify
// import Vuetify from 'vuetify/lib'
// import colors from 'vuetify/es5/util/colors'

import Vuetify from 'vuetify'
import colors from 'vuetify/es5/util/colors'


// Vue-Router
import router from './router/index.js'

Vue.use(Vuetify, {
  theme: {
    primary: colors.amber.base,
    secondary: colors.blue.base,
    accent: colors.indigo.base,
  }
});


import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'




// Main app
const app = new Vue({
    el: '#app',
    router,
});







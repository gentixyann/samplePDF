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
    light: {
        primary: colors.purple,
        secondary: colors.grey.darken1,
        accent: colors.shades.black,
        error: colors.red.accent3,
      },
      dark: {
        primary: colors.purple,
        secondary: colors.grey.darken1,
        accent: colors.shades.black,
        error: colors.red.accent3,
      },
  }
});

window.Vue = require('vue');



import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'




// Main app
const app = new Vue({
    el: '#app',
    router,
});







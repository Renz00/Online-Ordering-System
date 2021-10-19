require('./bootstrap');
import Vue from 'vue'
import Vuetify from '../plugins/vuetify.js'
import store from './store' //Vuex store files 
import App from './components/App.vue'
import Chartkick from 'vue-chartkick'
import Chart from 'chart.js'
import Echo from 'laravel-echo';
import '@mdi/font/css/materialdesignicons.css'
import './app.scss' // Custom bootstrap colors

window.Vue = Vue;

var CryptoJS = require("crypto-js");

var token = ''
if (sessionStorage.getItem("user-session") != null) {
    //deleting the product id from user-session
    //   Decrypt
    var userdata = JSON.parse(
        CryptoJS.AES.decrypt(
            sessionStorage.getItem("user-session"),
            "vJaDBQadMaw108cNVXPl"
        ).toString(CryptoJS.enc.Utf8)
    );
    token = userdata.token
}
//Laravel websockets setup
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_VUE_APP_WEBSOCKETS_KEY,
    wsHost: process.env.MIX_VUE_APP_WEBSOCKETS_SERVER,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    auth:{
        headers:{
            Authorization: `Bearer ${token}`
        }
    }
});

Vue.use(Chartkick.use(Chart))

Vue.use(Vuetify, {
    iconfont: 'mdi'
})

const app = new Vue({
    vuetify: Vuetify,
    store,
    el: '#app',
    components: {
        App
    }
});

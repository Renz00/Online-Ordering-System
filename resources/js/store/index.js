import Vue from 'vue'
import Vuex from 'vuex'
import 'es6-promise/auto'

import Products from './modules/Products.js'
import Login from './modules/Login.js'
import Register from './modules/Register.js'
import Users from './modules/Users.js'
import Orders from './modules/Orders.js'
import Reviews from './modules/Reviews.js'
import Dashboard from './modules/Dashboard.js'

Vue.use(Vuex)
export default new Vuex.Store({
    modules: {
        Products,
        Login,
        Register,
        Users,
        Orders,
        Reviews,
        Dashboard
    }
})
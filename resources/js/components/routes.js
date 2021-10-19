import Dashboard from './Dashboard.vue'
import Login from './Login.vue'
import Register from './Register.vue'
import AddUser from './AddUser.vue'
import Users from './Users.vue'
import Products from './Products.vue'
import AddProduct from './AddProduct.vue'
import ShowProduct from './ShowProduct.vue'
import EditProduct from './EditProduct.vue'
import ShowUser from './ShowUser.vue'
import EditUser from './EditUser.vue'
import Orders from './Orders.vue'
import AddOrder from './AddOrder.vue'
import ShowOrder from './ShowOrder.vue'
import EditOrder from './EditOrder.vue'
import Reviews from './Reviews.vue'
import ShowReview from './ShowReview.vue'
import ReportOrders from './ReportOrders.vue'
import ReportProducts from './ReportProducts.vue'
import notfound from './notfound.vue'

var CryptoJS = require("crypto-js");
var role = "";

if (sessionStorage.getItem("user-session") != null) {
    const userdata = JSON.parse(
        CryptoJS.AES.decrypt(
            sessionStorage.getItem("user-session"),
            "vJaDBQadMaw108cNVXPl"
        ).toString(CryptoJS.enc.Utf8)
    );
    role = userdata.user.role;
}

export default {
    mode: 'history',
    base: '/',
    routes: [
        { 
            path: '/dashboard/register', 
            name: 'register', 
            component: Register,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0); //this will position page scroll to the very top
                if (sessionStorage.getItem("user-session") != null) {
                    history.back()
                }
                else {
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/login', 
            name: 'login', 
            component: Login,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") != null) {
                    history.back()
                }
                else {
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/home', 
            name: 'dashboard', 
            component: Dashboard,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
                else if (role === 'Cashier' || role === 'Courier'){
                    next('/dashboard/orders');
                }
            } 
        },
        { 
            path: '/dashboard/products', 
            name: 'products', 
            component: Products,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/add-product', 
            name: 'addproduct', 
            component: AddProduct,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/show-product', 
            name: 'showproduct', 
            component: ShowProduct,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/edit-product', 
            name: 'editproduct', 
            component: EditProduct,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/users', 
            name: 'users', 
            component: Users,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/add-user', 
            name: 'adduser', 
            component: AddUser,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/show-user', 
            name: 'showuser', 
            component: ShowUser,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/edit-user', 
            name: 'edituser', 
            component: EditUser,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/orders', 
            name: 'orders', 
            component: Orders,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/add-order', 
            name: 'addorder', 
            component: AddOrder,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/show-order', 
            name: 'showorder', 
            component: ShowOrder,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier' || role === 'Courier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/edit-order', 
            name: 'editorder', 
            component: EditOrder,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator' || role === 'Cashier'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/reviews', 
            name: 'reviews', 
            component: Reviews,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/show-review', 
            name: 'showreview', 
            component: ShowReview,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/report-orders', 
            name: 'reportorders', 
            component: ReportOrders,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard/report-products', 
            name: 'reportproducts', 
            component: ReportProducts,
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next()
                }
            } 
        },
        { 
            path: '/dashboard',
            beforeEnter: (to, from, next) => { //this adds navigation rules to routers
                window.scrollTo(0,0);
                if (sessionStorage.getItem("user-session") === null) {
                    next('/dashboard/login');
                }
                else if (role === 'Administrator'){
                    next('/dashboard/home')
                }
            } 
        },
        { path: '/404', name: 'notfound', component: notfound },
        { path: '*', redirect: '/404' }
    ]
}
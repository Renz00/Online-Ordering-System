import axios from "axios";
import moment from 'moment'
var CryptoJS = require("crypto-js");
var userdata = "";
var token = "";

if (sessionStorage.getItem("user-session") != null) {
    userdata = JSON.parse(
        CryptoJS.AES.decrypt(
            sessionStorage.getItem("user-session"),
            "vJaDBQadMaw108cNVXPl"
        ).toString(CryptoJS.enc.Utf8)
    );
    token = userdata.token;
}

const state = {
    report_orders: [],
    report_products: [],
    recent_orders: [],
    recent_reviews: [],
    years: [],
    months: [],
    notiforders:[],
    notifreviews:[],
    notifcount:0,
    total_orders: '',
    total_sales: '',
    total_users: '',
    report_response: ''
};
const getters = {
    getStateReportOrders: state => state.report_orders,
    getStateYears: state => state.years,
    getStateMonths: state => state.months,
    getStateReportProducts: state => state.report_products,
    getStateRecentOrders: state => state.recent_orders,
    getStateRecentReviews: state => state.recent_reviews,
    getStateTotalOrders: state => state.total_orders,
    getStateTotalSales: state => state.total_sales,
    getStateTotalUsers: state => state.total_users,
    getStateNotifOrders: state => state.notiforders,
    getStateNotifReviews: state => state.notifreviews,
    getStateNotifCount: state => state.notifcount,
    getReportResponse: state => state.report_response,
};
const actions = {
    async getReports(){
        this.commit("clearReports")
        this.commit("clearReportResponse")
        const response = await axios.get('/api/home', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setReports', response.data)
            this.commit('setReportResponse', 'loaded')
            
        }
    },
    async showOrderReports({}, formData){
        this.commit("clearReports")
        this.commit("clearReportResponse")
        var start = new Date(formData.date, 0, 2) //get the first day of the formData.date year
        var end = new Date(parseInt(formData.date)+1, 0, 0) //get the last day of the formData.date year

        const response = await axios.post('/api/reports/orders',{
            start: start,
            end:end
        },
        {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setOrderReports', response.data)
            this.commit('setReportResponse', 'loaded')
            
        }
    },
    async showProductReports({}, formData){
        this.commit("clearReports")
        this.commit("clearReportResponse")
        var start = new Date(formData.year, formData.month, 1)
        var end = new Date(formData.year, parseInt(formData.month)+1, 0)
        const response = await axios.post('/api/reports/products',{
            start: start,
            end:end
        },
        {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setProductReports', response.data)
            this.commit('setReportResponse', 'loaded')
            
        }
    },
    exportOrderReports({}, formData){
        var start = new Date(formData.date, 0, 2) //get the first day of the formData.date year
        var end = new Date(parseInt(formData.date)+1, 0, 0) //get the last day of the formData.date year
        console.log('exporting...')
        axios({
            url: '/api/reports/orders/export',
            method: 'POST',
            data: {
                start: start,
                end: end
            },
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            },
            responseType: 'blob',
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', formData.date+'.xlsx');
                document.body.appendChild(link);
                link.click();
            })
            .catch(function (error) {
                console.log(error)
            });
        
       
    },
    async getNotifications(){
        this.commit('clearNotifications')
        const response = await axios.get('/api/notifications/all',
        {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setNotifications', response.data)
            
        }
    },
    async updateNotification({}, notifData){
        this.commit('clearNotifications')
        const response = await axios.post('/api/notifications/viewed',{
            id: notifData.id,
            type: notifData.type
        },
        {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data.response != null){
            this.commit('setNotifications', response.data)
            
        }
    },
};
const mutations = {
    setReports(state, data){
       state.report_orders = data.orders
       state.report_products = data.products

        data.recentorders.map(function(value) {
            state.recent_orders.push(value);
        });

        data.recentreviews.map(function(value) {
            state.recent_reviews.push(value);
        });

        state.total_orders = data.totalorders
        state.total_sales = data.totalsales
        state.total_users = data.totalusers
    },
    setOrderReports(state, data){
        state.report_orders = data.groupedorders
        state.years = data.years
    },
    setProductReports(state, data){
        state.report_products = data.products
        state.years = Object.keys(data.dates)
        state.months = data.dates
    },
    setNotifications(state, data){
        data.response.recentorders.map(function(value) {
            state.notiforders.push(value);
        });
        data.response.recentreviews.map(function(value) {
            state.notifreviews.push(value);
        });
        state.notifcount = data.response.count
    },
    setReportResponse(state, data){
        state.report_response = data
    },
    clearReportResponse(state){
        state.report_response = ''
    },
    clearNotifications(state){
        state.notifcount = 0
        state.notiforders = []
        state.notifreviews = []
    },
    clearReports(state){
        state.report_orders = []
        state.report_products = []
        state.recent_orders = []
        state.recent_reviews = []
        state.years = []
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

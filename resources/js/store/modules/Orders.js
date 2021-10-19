import axios from "axios";
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
    orders: [],
    order_address:[],
    names:[],
    order_user:[],
    orderproducts:[],
    order_group: '',
    order_response:''
};
const getters = {
    getStateOrders: state => state.orders,
    getStateOrderAddress: state => state.order_address,
    getStateOrderProducts: state => state.orderproducts,
    getStateOrderUser: state => state.order_user,
    getStateNames: state => state.names,
    getOrderResponse: state => state.order_response
};
const actions = {
    async getOrders(){
        this.commit("clearOrders")
        // this.commit('setID')
        const response = await axios.get('/api/orders/all', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setOrders', {orders: response.data})
            
        }
    },
    async getAddInfo(){
        this.commit("clearNames")
        this.commit("clearOrderProducts")
        // this.commit('setID')
        const response = await axios.get('/api/orders/addinfo', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setAddInfo', response.data)
        }
    },
    async addOrder({}, formData){
        console.log('adding order...')
        this.commit("clearOrderResponse")
        // this.commit('setID')
        const response = await axios.post('/api/orders/create', {
            user: formData.customer,
            products: formData.products,
            phone: formData.phone,
            address: formData.address,
            notes: formData.notes
        },{
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setOrderResponse', response.data)
        }
    },
    async showOrder(){
        this.commit("clearOrders")
        this.commit("clearOrderAddress")
        this.commit("clearOrderResponse")
        this.commit('setOG')
        const response = await axios.get('/api/orders/show/'+state.order_group,
        {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setShowOrders', response.data)
            this.commit('setOrderResponse', 'loaded')
        }
    },
    async editOrder({}, formData){
        this.commit("clearOrderResponse")
        this.commit('setOG')
        const response = await axios.put('/api/orders/update/'+state.order_group, 
        {
            user: formData.customer,
            products: formData.products,
            phone: formData.phone,
            address: formData.address,
            notes: formData.notes,
            status: formData.status
        },
        {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setOrderResponse', response.data)
        }
    },
    async destroyOrder(){
        this.commit("clearOrderResponse")
        this.commit('setOG')
        const response = await axios.delete('/api/orders/destroy/'+state.order_group, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data === 1){
            this.commit('setOrderResponse', 'deleted')
            console.log('order deleted')
        }
    },
    genInvoice({}, {order_group, filename}){
        axios({
            url: '/api/orders/print/'+order_group,
            method: 'GET',
            responseType: 'blob',
              headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', filename+'.pdf');
                document.body.appendChild(link);
                link.click();
            })
            .catch(function (error) {
                console.log(error)
            });
    }
};
const mutations = {
    setOG(state){
        if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            var userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );

            if ('order_group' in userdata){
                state.order_group = userdata.order_group
            }
            else {
                window.location.replace('/dashboard/orders')
            }
        }
    },
    setOrders(state, data){
        data.orders.map(function(value) {
            state.orders.push(value);
        });
        
    },
    setAddInfo(state, data){

        data.users.map(function(value) {
            state.names.push(value);
        });

        data.products.map(function(value) {
            state.orderproducts.push(value);
        });
        
    },
    setShowOrders(state, data){
        data.orders.map(function(value) {
            state.orders.push(value);
        });

        data.order_address.map(function(value) {
            state.order_address.push(value);
        });

        data.user.map(function(value) {
            state.order_user.push(value);
        });


    },
    setOrderResponse(state, data){
        state.order_response = data
    },
    clearOrders(state){
        state.orders = []
    },
    clearOrderUser(state){
        state.orderuser = []
    },
    clearOrderAddress(state){
        state.order_address = []
    },
    clearNames(state){
        state.names = []
    },
    clearOrderProducts(state){
        state.orderproducts = []
    },
    clearOrderResponse(state){
        state.order_response = ''
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

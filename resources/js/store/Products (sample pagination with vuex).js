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
    products: [],
    product_response: '',
    pagination: {
        total:'',
        links:[],
        current:''
    },
};
const getters = {
    getStateProducts: state => state.products, //Creating a getter to be used in other components to retrieve login_error state
    getProductResponse: state => state.product_response,
    getPagination: state => state.pagination,
};
const actions = {
    getProducts() {
        this.commit("clearProducts")
        axios
            .get("/api/products/all", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                // this.commit("setProducts", { products: response.data });
                this.commit('setPagination', response.data)
            })
            .catch(error => {
                //catching any errors
                console.log(error);
            });
    },
    getProductPage({}, page) {
        this.commit("clearProducts")
        axios
            .get("/api/products/all?page="+page, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                // this.commit("setProducts", { products: response.data });
                this.commit('setPagination', response.data)
            })
            .catch(error => {
                //catching any errors
                console.log(error);
            });
    },
    showProduct() {
        this.commit("clearProducts")
        var product_id=''
        if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            var userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );
                
            product_id = userdata.product_id
        }

        axios
            .get("/api/products/show/"+product_id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.data != 'error'){
                    this.commit("setProducts", { products: response.data });
                }
            })
            .catch(error => {
                //catching any errors
                console.log(error);
            });

    },
    addProduct({}, formData){
        console.log('adding product...')
        this.commit("clearProdResponse");
        axios
            .post("/api/products/store", {
                name: formData.name,
                type: formData.type,
                description: formData.description,
                price: formData.price,
                discount: formData.discount,
                available: formData.available
            },{
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.data === 'stored'){
                    this.commit('setProductResponse', response.data)
                    console.log("product has been added!")
                }   
            })
            .catch (error => { //catching any errors
                console.log(error);
            });
    },
    updateProduct({}, formData){
        console.log('updating product...')
        this.commit("clearProdResponse");
        var product_id=''
        if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            var userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );
                
            product_id = userdata.product_id
        }
        axios
            .put("/api/products/update/"+product_id, {
                name: formData.name,
                type: formData.type,
                description: formData.description,
                price: formData.price,
                discount: formData.discount,
                available: formData.available
            },{
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.data === 'updated'){
                    this.commit('setProductResponse', response.data)
                    console.log("product has been Updated!")
                }   
            })
            .catch (error => { //catching any errors
                console.log(error);
            });
    }
};
const mutations = {
    setProducts(state, data) {
        data.products.map(function(value, key) {
            //mapping the object data into products array in state
            state.products.push(value);
        });
    },
    setProductResponse(state, data) {
        state.product_response = data
    },
    setPagination(state, data) {
        data.data.map(function(value, key) {
            //mapping the object data into products array in state
            state.products.push(value);
        });

        state.pagination.current = data.current_page
        state.pagination.links = data.links
        state.pagination.total = data.links.length-1
    },
    setID(state, data){
        state.id = data
    },
    clearProdResponse(state) {
        state.product_response =''
    },
    clearProducts(state) {
        state.products = []
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

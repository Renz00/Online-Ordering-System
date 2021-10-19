
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
    product_response: ''
};
const getters = {
    getStateProducts: state => state.products, //Creating a getter to be used in other components to retrieve login_error state
    getProductResponse: state => state.product_response
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
                this.commit("setProducts", { products: response.data });
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

            if ('product_id' in userdata){
                product_id = userdata.product_id
            }
            else {
                window.location.replace('/dashboard/products')
            }
                
            
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

            if ("product_id" in userdata) {
                product_id = userdata.product_id
            }
            else {
                console.warn('Product ID does not exist in user session')
                window.location.replace('/dashboard/home')
            }

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
    },
    deleteProduct() {
        console.log('Deleting product...')
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

            if ("product_id" in userdata) {
                product_id = userdata.product_id
            }
            else {
                console.warn('Product ID does not exist in user session')
                window.location.replace('/dashboard/home')
            }
                
        }

        axios
            .delete("/api/products/destroy/"+product_id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.data === 'deleted'){
                    this.commit('setProductResponse', response.data)
                    console.log('Product has been deleted!')
                }
            })
            .catch(error => {
                //catching any errors
                console.log(error);
            });

    },
};
const mutations = {
    setProducts(state, data) {
        data.products.map(function(value) {
            //mapping the object data into products array in state
            state.products.push(value);
        });
    },
    setProductResponse(state, data) {
        state.product_response = data
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

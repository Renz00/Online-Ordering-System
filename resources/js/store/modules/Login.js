import axios from "axios";
var CryptoJS = require("crypto-js");
const state = {
    formData: {},
    login_error:'',
    diaload: true
};
const getters = {
    getLoginError: state => state.login_error, //Creating a getter to be used in other components to retrieve login_error state
    getDiaload: state => state.diaload
};
const actions = {

    loginUser({}, formData) {
        axios
            .post("/api/login", {
                username: formData.username,
                password: formData.password
            })
            .then(response => {
                if (response.data != 'Error'){
                    let encrypted = CryptoJS.AES.encrypt(JSON.stringify(response.data), 'vJaDBQadMaw108cNVXPl').toString();
                    sessionStorage.setItem(
                        'user-session', encrypted
                    )
                    window.location.replace('/dashboard/home')
                }
                else {
                    this.commit('changeLoginError', response.data) //Calling mutations function and passing the response data
                }
            })
            .catch (error => { //catching any errors
                console.log(error);
            });
    }
};
const mutations = {
    changeLoginError(state, val){
        state.login_error = val //Passing the value to the login_error state
    },
    setDiaload(state, val){
        state.diaload = val //Passing the value to the login_error state
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

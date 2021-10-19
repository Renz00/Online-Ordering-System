import axios from "axios";
var CryptoJS = require("crypto-js");

// // Encrypt
// var ciphertext = CryptoJS.AES.encrypt(JSON.stringify(data), 'secret key 123').toString();

// // Decrypt
// var bytes  = CryptoJS.AES.decrypt(ciphertext, 'secret key 123');
// var decryptedData = JSON.parse(bytes.toString(CryptoJS.enc.Utf8));


const state = {
    formData: {},
    add_response: ''
};
const getters = {
    getAddResponse: state => state.add_response
};
const actions = {

    registerUser({}, formData) {
        console.log('registering...')
        this.commit('clearAddResponse')
        axios
            .post("/api/register", {
                username: formData.username,
                firstname: formData.firstname,
                lastname: formData.lastname,
                phone: formData.phone,
                description: formData.address,
                email: formData.email,
                password: formData.password,
                role: formData.role,
            })
            .then(response => {
              
                if (formData.route != 'adduser' && response.data != 'denied'){
                    let encrypted = CryptoJS.AES.encrypt(JSON.stringify(response.data), 'vJaDBQadMaw108cNVXPl').toString();
                    sessionStorage.setItem(
                        'user-session', encrypted
                    )
                    window.location.replace('/dashboard/home')
                }
                else {
                    this.commit('setAddResponse')
                }
                
            })
            .catch (error => { //catching any errors
                console.log(error);
            });
    }
};
const mutations = {
    setAddResponse(state){
        state.add_response = 'added'
    },
    clearAddResponse(state){
        state.add_response = ''
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

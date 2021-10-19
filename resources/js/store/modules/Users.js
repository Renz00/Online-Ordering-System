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
    users: [],
    address: [],
    user_id: '',
    user_response:''
};
const getters = {
    getStateUsers: state => state.users,
    getStateAddress: state => state.address,
    getUserResponse: state => state.user_response
};
const actions = {
    getUsers(){
        this.commit("clearUsers")
        axios
            .get("/api/users/all", {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                this.commit("setUsers", {users: response.data});
            })
            .catch(error => {
                //catching any errors
                console.log(error);
            });
    },
    showUser(){
        this.commit("clearUsers")
        this.commit("clearAddress")
        this.commit('setID')
        axios
            .get("/api/users/show/"+state.user_id, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                this.commit("setUsers", response.data);
            })
            .catch(error => {
                //catching any errors
                console.log(error);
            });
    },
    updateUser({}, {password, address, data, type}){

        console.log(password.currentpassword)
        this.commit('clearUserResponse')
        this.commit('setID')
        console.log(type)
        var url = ""

        if (type === 'others'){
            url = '/api/users/update/'
        }
        else if (type === 'password') {
            url = '/api/users/change/'
        }
        axios
            .put(url+state.user_id, {
                username: data[0].username,
                first_name: data[0].first_name,
                last_name: data[0].last_name,
                phone: data[0].phone,
                email: data[0].email,
                current_password: password.currentpassword,
                password: password.newpassword,
                description: address.description,
                notes: address.notes,
                role: data[0].role,
            },
            {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`
                }
            })
            .then(response => {
                if (response.data === 'updated'){
                    this.commit('setUserResponse', response.data)
                }
                
            })
            .catch (error => { //catching any errors
                console.log(error);
            });

    },
    async destroyUser(){
        this.commit('clearUserResponse')
        this.commit('setID')
        const response = await axios.delete('/api/users/destroy/'+state.user_id, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })

        if (response.data === 1){
            this.commit('setUserResponse', 'deleted')
        }
    }
};
const mutations = {
    setID(state){
        if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            var userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );

            if ('user_id' in userdata){
                state.user_id = userdata.user_id
            }
            else {
                window.location.replace('/dashboard/users')
            }
        }
    },
    setUsers(state, data){
        data.users.map(function(value) {
            state.users.push(value);
        });

        if (data.address != null){
            data.address.map(function(value) {
                state.address.push(value);
            });
        }
        
    },
    setUserResponse(state, data){
        state.user_response = data
    },
    clearUsers(state){
        state.users = []
    },
    clearAddress(state){
        state.address = []
    },
    clearUserResponse(state){
        state.user_response = ''
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

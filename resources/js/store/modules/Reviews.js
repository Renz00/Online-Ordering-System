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
    reviews: [],
    review_id: '',
    review_response:''
};
const getters = {
    getStateReviews: state => state.reviews,
    getReviewResponse: state => state.review_response
};
const actions = {
    async getReviews(){
        this.commit("clearReviews")
        const response = await axios.get('/api/reviews/all', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setReviews', {reviews: response.data})
            
        }
    },
    async showReview(){
        this.commit("clearReviews")
        this.commit('setReviewID')
        const response = await axios.get('/api/reviews/show/'+state.review_id, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data != null){
            this.commit('setReviews', {reviews: response.data})
            this.commit('setReviewResponse', 'loaded')
            
        }
    },
    async destroyReview({},{id}){
        this.commit("clearReviewResponse")
        const response = await axios.delete('/api/reviews/destroy/'+id, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            }
        })
        if (response.data === 'deleted'){
            this.commit('setReviewResponse', response.data)
            
        }
    },
};
const mutations = {
    setReviewID(state){
        if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            var userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );

            if ('review_id' in userdata){
                state.review_id = userdata.review_id
            }
            else {
                window.location.replace('/dashboard/reviews')
            }
        }
    },
    setReviews(state, data){
        data.reviews.map(function(value) {
            state.reviews.push(value);
        });
    },
    setReviewResponse(state, data){
        state.review_response = data
    },
    clearReviews(state){
        state.reviews = []
    },
    clearAddress(state){
        state.address = []
    },
    clearReviewResponse(state){
        state.review_response = ''
    }
};

export default {
    namespace: true,
    state,
    getters,
    actions,
    mutations
};

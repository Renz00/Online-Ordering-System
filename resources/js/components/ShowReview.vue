<template>
    <div>
            <v-container>
                <v-row>
                    <v-col></v-col>
                    <v-col>
                        <v-card class="mt-5" width="700" loading v-if="loading">
                            <v-card-title>
                                Loading...
                            </v-card-title>
                        </v-card>
                        <span v-if="empty">No record found...</span>
                        <v-card
                            class="mt-5"
                            width="700"
                            v-if="!loading && !empty"
                        >
                            <v-card-title>
                                <v-tooltip bottom>
                                    <template
                                        v-slot:activator="{
                                            on,
                                            attrs
                                        }"
                                    >
                                        <v-btn
                                            style="background-color:white;"
                                            color="white"
                                            elevation="2"
                                            @click="
                                                $router.push({ name: 'reviews' })
                                            "
                                            v-bind="attrs"
                                            v-on="on"
                                            large
                                            icon
                                            ><v-icon color="blue darken-2"
                                                >mdi-keyboard-backspace</v-icon
                                            ></v-btn
                                        >
                                    </template>
                                    <span>Go Back</span>
                                </v-tooltip>
                                &nbsp; Review ID: {{ this.review[0].id }}
                            </v-card-title>
                            <v-container>
                                <v-card-text class="black--text text-center">
                                    <span style="color: gray;">Review by: </span
                                    ><br />
                                    <h5>
                                        {{
                                            this.review[0].first_name +
                                                " " +
                                                this.review[0].last_name
                                        }}
                                    </h5>
                                    <v-row>
                                        <v-col>
                                            <span style="color: gray;"
                                                >Date Created:</span
                                            >
                                            <br />
                                            {{ this.review[0].created_at }}
                                        </v-col>
                                        <v-col>
                                            <span style="color: gray;"
                                                >Rating:
                                            </span>
                                            <br />
                                            <span
                                                v-for="n in this.review[0]
                                                    .rating"
                                                :key="n"
                                            >
                                                <v-icon color="yellow">
                                                    mdi-star
                                                </v-icon>
                                            </span>
                                        </v-col>
                                    </v-row>
                                    <v-divider></v-divider>
                                    <p>
                                        <span style="color: gray;"
                                            >Product:
                                        </span>
                                        <br />

                                        {{ this.review[0].name }}
                                    </p>
                                    <p>
                                        <span style="color: gray;"
                                            >Content:
                                        </span>
                                        <br />

                                        {{ this.review[0].content }}
                                    </p>
                                </v-card-text>
                                <v-card-actions>
                                    <v-row>
                                        <v-col> </v-col>
                                        <v-col
                                            ><v-btn
                                                @click="
                                                    deleteReview(
                                                        review[0].id
                                                    )
                                                "
                                                color="red"
                                                outlined
                                                block
                                                link
                                                ><v-icon>mdi-delete</v-icon>
                                                Delete</v-btn
                                            ></v-col
                                        >
                                    </v-row>
                                </v-card-actions>
                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col></v-col>
                </v-row>
            </v-container>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";
var CryptoJS = require("crypto-js");
export default {
    data: () => ({
        loading: true,
        empty: true,
        review: []
    }),
    methods: {
        deleteReview(id) {
            console.log("deleting review...");
            this.destroyReview({id});
        },
        populateCard() {
            this.showReview();

            if (this.getStateReviews != null) {
                this.review = this.getStateReviews;
                this.empty = false;
            }
        },

        ...mapActions({
            showReview: "showReview",
            destroyReview: "destroyReview"
        }),
        ...mapMutations({
            clearReviewResponse: "clearReviewResponse"
        })
    },
    watch: {
        getReviewResponse: function() {
            // listeing to changes in a vuex state
            if (this.getReviewResponse === "deleted") {
                if (sessionStorage.getItem("user-session") != null) {
                    //deleting the product id from user-session
                    //   Decrypt
                    var userdata = JSON.parse(
                        CryptoJS.AES.decrypt(
                            sessionStorage.getItem("user-session"),
                            "vJaDBQadMaw108cNVXPl"
                        ).toString(CryptoJS.enc.Utf8)
                    );
                    if ("review_id" in userdata) {
                        //if product id already exists, delete it
                        delete userdata.review_id;
                    }
                    let encrypted = CryptoJS.AES.encrypt(
                        JSON.stringify(userdata),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString();
                    sessionStorage.setItem("user-session", encrypted);
                }
                this.$router.push("/dashboard/reviews");
            } else if (this.getReviewResponse === "loaded") {
                this.loading = false;
            }
        }
    },
    computed: {
        ...mapGetters({
            getStateReviews: "getStateReviews",
            getReviewResponse: "getReviewResponse"
        })
    },
    mounted() {
        this.populateCard();
    },
    beforeRouteLeave(to, from, next) {
        if (this.getReviewResponse != "deleted") {
            this.clearReviewResponse();
        }

        next();
    }
};
</script>

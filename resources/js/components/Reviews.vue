<template>
    <div data-app>
        <v-container>
               <v-alert
                v-model="alert"
                class="mx-4 my-2"
                v-if="this.getReviewResponse() === 'deleted'"
                border="left"
                close-text="Close Alert"
                type="success"
                color="orange darken-2"
                dark
                dismissible
            >
                Success! Review has been deleted.
            </v-alert>
            <h3 class="mt-2">
                <v-icon color="red">mdi-star-face</v-icon>
                Reviews</h3>
            <v-card class="mt-2">
                <span v-if="empty">No reviews found...</span>
                <v-data-table
                    class="my-5"
                    loading
                    loading-text="Loading... Please wait"
                    v-if="loading"
                ></v-data-table>
                <!-- checking if the component is not loading and the data is not empty -->
                <div v-if="!loading && !empty">
                    <v-card-title>
                        <v-row>
                            <v-col>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-title>

                    <v-data-table
                        :headers="headers"
                        :items="rows"
                        :search="search"
                    >
                    
                        <template v-slot:[`item.first_name`]="{ item }">
                            <span>
                                {{ item.first_name+' '+item.last_name }}
                            </span>
                        </template>

                        <template v-slot:[`item.created_at`]="{ item }">
                            <span>{{
                                new Date(item.created_at).toLocaleString(
                                    "en-US"
                                )
                            }}</span>
                        </template>
                        <template v-slot:[`item.actions`]="{ item }">
                            <v-btn
                                @click="show(item.id)"
                                class="mr-2"
                                color="primary"
                            >
                                <v-icon>
                                    mdi-eye
                                </v-icon>
                                &nbsp; View
                            </v-btn>
                        </template>
                    </v-data-table>
                </div>
            </v-card>
        </v-container>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
var CryptoJS = require("crypto-js");

export default {
    name: "Dashboard",
    data: () => ({
        loading: true,
        search: "",
        empty: true,
        alert: true,
        headers: [
            { text: "ID", align: "start", value: "id" },
            { text: "Review By", value: "first_name" },
            { text: "Rating", value: "rating" },
            { text: "Product", value: "name" },
            { text: "Date Added", value: "created_at" },
            { text: "Actions", value: "actions", sortable: false }
        ],
        rows: []
    }),
    methods: {
        show(id){
              if (sessionStorage.getItem("user-session") != null) {
                //   Decrypt
                var userdata = JSON.parse(
                    CryptoJS.AES.decrypt(
                        sessionStorage.getItem("user-session"),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString(CryptoJS.enc.Utf8)
                );
                if ("review_id" in userdata) {
                    delete userdata.review_id;
                }
                Object.assign(userdata, { review_id: id });
                let encrypted = CryptoJS.AES.encrypt(
                    JSON.stringify(userdata),
                    "vJaDBQadMaw108cNVXPl"
                ).toString();
                sessionStorage.setItem("user-session", encrypted);
            }
            this.$router.push('/dashboard/show-review')

        },
        populateTable() {
            this.getReviews();
            if (this.getStateReviews() != null || this.getStateReviews() != "") {
                this.rows = this.getStateReviews();
                this.empty = false;
            }
        },
        ...mapActions({
            getReviews: "getReviews"
        }),
        ...mapGetters({
            getStateReviews: "getStateReviews",
            getReviewResponse: "getReviewResponse"
        }),
        ...mapMutations({
            clearReviewResponse: "clearReviewResponse",
        })
    },
    watch: {
        rows: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.rows != null && this.rows != "" && this.empty === false) {
                this.loading = false;
            }
        }
    },
    mounted() {
        this.populateTable();
    },
    beforeRouteLeave(to, from, next) {
        this.clearReviewResponse();
        next();
    }
};
</script>

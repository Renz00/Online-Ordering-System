<template>
    <div data-app>
        <v-container>
            <v-alert
                v-model="store_alert"
                class="mx-4 my-2"
                v-if="this.getProductResponse() === 'stored'"
                border="left"
                close-text="Close Alert"
                type="success"
                color="green darken-2"
                dark
                dismissible
            >
                Success! Product has been added.
            </v-alert>

            <v-alert
                v-model="delete_alert"
                class="mx-4 my-2"
                v-if="this.getProductResponse() === 'deleted'"
                border="left"
                close-text="Close Alert"
                type="success"
                color="orange darken-2"
                dark
                dismissible
            >
                Success! Product has been deleted.
            </v-alert>
              <h3 class="mt-2">
                <v-icon color="red">mdi-food</v-icon>
                Products</h3>
            <v-card class="mt-2">
                <span v-if="empty">No products found...</span>
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
                                <v-btn
                                    color="#db423d"
                                    class="white--text"
                                    elevation="2"
                                    to="/dashboard/add-product"
                                    link
                                >
                                    <v-icon>mdi-plus-circle-outline</v-icon>Add
                                    Products</v-btn
                                >
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
                        <template v-slot:[`item.discount`]="{ item }">
                            <span v-if="item.discount > 0">{{
                                item.discount + "%"
                            }}</span>
                            <span v-if="item.discount === 0">NONE</span>
                        </template>

                        <template v-slot:[`item.price`]="{ item }">
                            <span v-if="item.discount > 0">{{
                                item.discount_price
                            }}</span>
                            <span v-if="item.discount === 0">{{
                                item.price
                            }}</span>
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
    data() {
        return {
            search: "",
            store_alert: true,
            delete_alert:true,
            loading: true,
            empty: true,
            headers: [
                { text: "ID", align: "start", value: "id" },
                { text: "Name", value: "name" },
                { text: "Discount", value: "discount" },
                { text: "Price", value: "price" },
                { text: "Date Added", value: "created_at" },
                { text: "Actions", value: "actions", sortable: false }
            ],
            rows: []
        };
    },
    methods: {
        show(id) {
            if (sessionStorage.getItem("user-session") != null) {
                //storing the product id to user-session
                //   Decrypt
                var userdata = JSON.parse(
                    CryptoJS.AES.decrypt(
                        sessionStorage.getItem("user-session"),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString(CryptoJS.enc.Utf8)
                );
                if ("product_id" in userdata) {
                    //if product id already exists, delete it before adding new value
                    delete userdata.product_id;
                }
                Object.assign(userdata, { product_id: id });
                let encrypted = CryptoJS.AES.encrypt(
                    JSON.stringify(userdata),
                    "vJaDBQadMaw108cNVXPl"
                ).toString();
                sessionStorage.setItem("user-session", encrypted);
            }
            this.$router.push("/dashboard/show-product");
        },
        populateTable() {
            this.getProducts();
            if (this.getStateProducts() != null || this.getStateProducts() != ''){
                this.rows = this.getStateProducts();
                this.empty = false
            }
            
        },
        ...mapActions({
            getProducts: "getProducts",
            showProduct: "showProduct"
        }),
        ...mapGetters({
            getStateProducts: "getStateProducts",
            getProductResponse: "getProductResponse"
        }),
        ...mapMutations({
            clearProdResponse: "clearProdResponse"
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
        this.clearProdResponse();
        next();
    }
};
</script>

<style scoped>
.white {
    color: white;
}
.dirty-white {
    background-color: #fcf9f2;
}
</style>

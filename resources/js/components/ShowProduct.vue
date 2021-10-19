<template>
    <div>
            <v-container>
                 <v-alert
                v-model="update_alert"
                class="mx-4 my-2"
                v-if="this.getProductResponse() === 'updated'"
                border="left"
                close-text="Close Alert"
                type="success"
                color="green darken-2"
                dark
                dismissible
            >
                Success! Product has been updated.
            </v-alert>
                <v-row>
                    <v-col></v-col>
                    <v-col>
                         <v-card class="mx-5 mt-10" width="700" loading v-if="loading">
                            <v-card-title>
                                Loading...
                            </v-card-title>
                        </v-card>
                        <span v-if="empty">No record found...</span>
                        <v-card class="mx-5 mt-10" width="700" v-if="!loading && !empty">
                            <div style="position:relative;">
                                <!-- Positioning button on top of img using position relative and absolute with z-index -->
                                <div
                                    style="position:absolute; z-index: 1;"
                                    class="mx-2 my-2"
                                >
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
                                                @click="$router.push({name: 'products'})"
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
                                </div>

                                <v-img
                                    src="https://picsum.photos/510/300?random"
                                    lazy-src="https://picsum.photos/510/300?random"
                                    max-width="800"
                                    max-height="400"
                                >
                                    <template v-slot:placeholder>
                                        <v-row
                                            class="fill-height ma-0"
                                            align="center"
                                            justify="center"
                                        >
                                            <v-progress-circular
                                                indeterminate
                                                color="grey lighten-5"
                                            ></v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                            </div>

                            <v-container>
                                <v-card>
                                    <br />
                                    <div class="text-center">
                                        <h4>{{ this.product[0].name }}</h4>
                                        <p class="gray">
                                            <em>{{ this.product[0].type }}</em>
                                        </p>
                                        <v-row>
                                            <v-col>
                                                <p>
                                                    <b>Price:</b> ₱
                                                    {{
                                                        this.product[0]
                                                            .discount === 0
                                                            ? this.product[0]
                                                                  .price
                                                            : this.product[0]
                                                                  .discount_price
                                                    }}
                                                </p></v-col
                                            >
                                            <v-col
                                                ><p
                                                    v-if="
                                                        this.product[0]
                                                            .discount === 0
                                                    "
                                                >
                                                    <b>Discount:</b>
                                                    <span class="gray"
                                                        >None</span
                                                    >
                                                </p>
                                                <p
                                                    v-if="
                                                        this.product[0]
                                                            .discount > 0
                                                    "
                                                >
                                                    Discount:
                                                    <span class="gray">{{
                                                        this.product[0]
                                                            .discount + "%"
                                                    }}</span>
                                                </p>
                                            </v-col>
                                        </v-row>
                                    </div>
                                    <v-divider class="mx-12"></v-divider>
                                    <v-card-text class="black--text">
                                        <div class="text-center">
                                            {{ this.product[0].description }}
                                            <br />
                                            <span
                                                class="blue--text"
                                                v-if="
                                                    this.product[0]
                                                        .available === 1
                                                "
                                                >This product is
                                                available.</span
                                            >
                                            <span
                                                class="red--text"
                                                v-if="
                                                    this.product[0]
                                                        .available === 0
                                                "
                                                >This product is not
                                                available.</span
                                            >
                                        </div>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-row>
                                            <v-col
                                                ><v-btn
                                                    @click="
                                                        editProduct(
                                                            product[0].id
                                                        )
                                                    "
                                                    color="success"
                                                    outlined
                                                    block
                                                    link
                                                    ><v-icon
                                                        >mdi-circle-edit-outline</v-icon
                                                    >
                                                    Edit</v-btn
                                                ></v-col
                                            >
                                            <v-col
                                                ><v-btn
                                                    @click="
                                                        deleteProduct(
                                                            product[0].id
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
                                </v-card>
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
    name: "ShowProduct",
    data: () => ({
        product: [],
        loading: true,
        alert: true,
        empty: true
    }),
    methods: {
        populateProduct() {
            this.showProduct();
            if (this.getStateProducts() != null || this.getStateProducts() != ''){
                this.product = this.getStateProducts();
                this.empty = false
            }
        },
        editProduct(id){
            this.$router.push('/dashboard/edit-product')
        },
        deleteProduct(id){
            this.deleteProduct()
        },
        ...mapGetters({
            getStateProducts: "getStateProducts",
            getProductResponse: "getProductResponse"
        }),
          ...mapActions({
            showProduct: "showProduct",
            deleteProduct: "deleteProduct"
        }),
        ...mapMutations({
            clearProdResponse: "clearProdResponse"
        })
    },
    watch: {
        product: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.product != null && this.product != "") {
                this.loading = false;
            }
        },
        "$store.state.Products.product_response": function() { //listeing to changes in a vuex state
        
            if (this.$store.state.Products.product_response === "deleted") {
                 if (sessionStorage.getItem("user-session") != null) {
                //deleting the product id from user-session
                //   Decrypt
                var userdata = JSON.parse(
                    CryptoJS.AES.decrypt(
                        sessionStorage.getItem("user-session"),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString(CryptoJS.enc.Utf8)
                );
                if ("product_id" in userdata) {
                    //if product id already exists, delete it
                    delete userdata.product_id;
                }
                let encrypted = CryptoJS.AES.encrypt(
                    JSON.stringify(userdata),
                    "vJaDBQadMaw108cNVXPl"
                ).toString();
                sessionStorage.setItem("user-session", encrypted);
                }
                this.$router.push("/dashboard/home");
            }
        }
    },
    mounted() {
        this.populateProduct();
    },
    beforeRouteLeave(to, from, next) {
        if (this.$store.state.Products.product_response != 'deleted'){
            this.clearProdResponse(); //clearing product response state before going to next route
        }
        next();
    }
};
</script>

<style scoped>
.gray {
    color: gray;
}
</style>

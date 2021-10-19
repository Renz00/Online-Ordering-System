<template>
    <div data-app>
            <v-card class="mx-5 mt-10" loading v-if="loading">
                <v-card-title>
                    Loading...
                </v-card-title>
            </v-card>
            <v-card class="mx-5 mt-10" v-if="!loading">
                <p v-for="row in rows" :key="row.id">
                    {{ row.name }}
                </p>
                <v-card-actions>
                    <v-pagination
                        v-model="pagination.current"
                        :length="pagination.total - 1"
                        @input="next"
                    ></v-pagination>
                </v-card-actions>
            </v-card>
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
            alert: true,
            loading: true,
            empty: true,
            rows: [],
            pagination: {}
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
            this.rows = [];
            this.rows = this.getStateProducts();
            this.pagination = this.getPagination();
            this.empty = false;
        },
        next(page) {
            this.loading = true
            this.getProductPage(page);
            this.populateTable();
        },
        ...mapActions({
            getProducts: "getProducts",
            showProduct: "showProduct",
            getProductPage: "getProductPage"
        }),
        ...mapGetters({
            getStateProducts: "getStateProducts",
            getProductResponse: "getProductResponse",
            getPagination: "getPagination"
        }),
        ...mapMutations({
            clearProdResponse: "clearProdResponse"
        })
    },
    watch: {
        alert: function() {
            if (this.alert === false && this.getProductResponse() != null) {
                this.clearProdResponse();
            }
        },
        rows: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.rows != null && this.rows != "" && this.empty === false) {
                this.loading = false;
            }
        }
    },
    mounted() {
        this.getProducts();
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

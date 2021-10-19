<template>
    <div class="mx-5 mt-10">
            <v-container>
                <v-row>
                    <v-col></v-col>
                    <v-col>
                        <v-card width="700" loading v-if="loading">
                        </v-card>
                        <v-card width="700" v-if="!loading">
                            <v-form
                                ref="form"
                                @submit.prevent="validate"
                                v-model="valid"
                                lazy-validation
                            >
                                <v-card-title class="font-weight-bold">
                                    <v-row>
                                        <v-col>
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs
                                                    }"
                                                >
                                                    <v-btn
                                                        color="blue"
                                                        elevation="2"
                                                        @click="$router.go(-1)"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        icon
                                                        large
                                                    >
                                                        <v-icon>
                                                            mdi-keyboard-backspace
                                                        </v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Go Back</span>
                                            </v-tooltip>
                                            &nbsp; Edit Order {{ this.getStateOrderAddress[0].order_group_id }}</v-col
                                        >
                                    </v-row>
                                </v-card-title>
                                <v-card-text>
                                    <v-select
                                        v-model="formData.status"
                                        :items="statusitems"
                                        :rules="[v => !!v || 'Status is required']"
                                        label="Satus"
                                        required
                                    ></v-select>
                                    <div v-if="this.role != 'Courier'">
                                    <v-autocomplete
                                        v-model="formData.customer"
                                        :items="names"
                                        :filter="filter"
                                        color="white"
                                        :item-text="fullName"
                                        label="Customer"
                                        @change="setAddress()"
                                        return-object
                                    ></v-autocomplete>

                                    <v-row class="d-flex">
                                        <v-col>
                                            <v-autocomplete
                                                v-model="selected_product"
                                                :items="allproducts"
                                                :filter="pfilter"
                                                color="white"
                                                item-text="name"
                                                label="Product"
                                                return-object
                                            ></v-autocomplete>
                                        </v-col>
                                        <v-col cols="4">
                                            <v-text-field
                                                v-model="quantity"
                                                :error-messages="quantity_error"
                                                label="Quantity"
                                                type="number"
                                                min="1"
                                                max="10"
                                                required
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="1">
                                            <v-btn class="mt-3" large icon>
                                                <v-icon
                                                    color="blue"
                                                    @click="addItem"
                                                    >mdi-plus-circle-outline</v-icon
                                                >
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                    <v-list elevation="1" dense>
                                        <v-list-item
                                            v-for="(product,
                                            index) in formData.products"
                                            :key="index"
                                        >
                                            <v-row
                                                align="center"
                                                justify="center"
                                            >
                                                <v-col>
                                                    <v-list-item-content>
                                                        <v-row>
                                                            <v-col>
                                                                {{
                                                                    product.name
                                                                }}
                                                                <span
                                                                    v-if="
                                                                        product.discount >
                                                                            0
                                                                    "
                                                                >
                                                                    <small
                                                                        >{{
                                                                            product.discount
                                                                        }}%
                                                                        Off</small
                                                                    >
                                                                </span>
                                                            </v-col>
                                                            <v-col
                                                                cols="4"
                                                                v-if="
                                                                    product.discount >
                                                                        0
                                                                "
                                                                >₱
                                                                {{
                                                                    product.discount_price
                                                                }}</v-col
                                                            >
                                                            <v-col
                                                                cols="4"
                                                                v-if="
                                                                    product.discount ===
                                                                        0
                                                                "
                                                                >₱
                                                                {{
                                                                    parseFloat(product.price).toFixed(
                                                                        2
                                                                    )
                                                                }}</v-col
                                                            >
                                                            <v-col cols="2"
                                                                >{{
                                                                    product.amount
                                                                }}
                                                                Item(s)</v-col
                                                            >
                                                        </v-row>
                                                    </v-list-item-content>
                                                </v-col>
                                                <v-col cols="2">
                                                    <v-btn
                                                        @click="
                                                            removeItem(index)
                                                        "
                                                        icon
                                                        ><v-icon color="red"
                                                            >mdi-delete</v-icon
                                                        ></v-btn
                                                    >
                                                </v-col>
                                            </v-row>
                                        </v-list-item>
                                    </v-list>
                                    <br />
                                    <v-row
                                        v-if="this.formData.products.length > 0"
                                    >
                                        <v-col>
                                            <h6>
                                                Grand Total: ₱
                                                {{ this.gtotal.toFixed(2) }}
                                            </h6>
                                        </v-col>
                                        <v-col>
                                            <h6>
                                                Total Items:
                                                {{ this.qtotal }}
                                            </h6>
                                        </v-col>
                                    </v-row>
                                    <v-text-field
                                        v-model="formData.phone"
                                        :rules="phoneRules"
                                        label="Phone"
                                        name="phone"
                                        required
                                    ></v-text-field>
                                    <v-textarea
                                        v-model="formData.address"
                                        :rules="addressRules"
                                        :counter="200"
                                        rows="2"
                                        row-height="20"
                                        label="Address"
                                        clear-icon="mdi-close-circle"
                                        clearable
                                        required
                                    ></v-textarea>
                                    <v-textarea
                                        v-model="formData.notes"
                                        :counter="200"
                                        rows="2"
                                        row-height="20"
                                        :error-messages="note_error"
                                        label="Other Notes"
                                        clear-icon="mdi-close-circle"
                                        clearable
                                    ></v-textarea>
                                </div>
                                </v-card-text>
                                <v-alert
                                    dense
                                    outlined
                                    class="mx-2"
                                    type="error"
                                    v-if="nocustomer"
                                    ><v-icon large color="white">
                                        mdi-warning-circle
                                    </v-icon>
                                    Customer field is required
                                </v-alert>
                                <v-alert
                                    dense
                                    outlined
                                    class="mx-2"
                                    type="error"
                                    v-if="noproducts"
                                    ><v-icon large color="white">
                                        mdi-warning-circle
                                    </v-icon>
                                    Add at least ONE product to the order
                                </v-alert>
                                <v-card-actions>
                                    <v-btn
                                        :disabled="!valid"
                                        color="success"
                                        class="mb-4"
                                        v-if="loading"
                                        loading
                                        block
                                    >
                                    </v-btn>
                                    <v-btn
                                        :disabled="!valid"
                                        type="submit"
                                        color="success"
                                        class="mb-4"
                                        v-if="!loading"
                                        block
                                    >
                                        Submit
                                        <v-icon right>
                                            mdi-checkbox-marked-outline
                                        </v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-col>
                    <v-col></v-col>
                </v-row>
            </v-container>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
var CryptoJS = require("crypto-js");

export default {
    name: "AddProduct",
    data: () => ({
        role:'',
        quantity: 1,
        gtotal: 0,
        qtotal: 0,
        statusitems: ['Processing', 'Outgoing', 'Delivered', 'Cancelled'],
        names: [],
        allproducts: [],
        selected_product: {},
        valid: true,
        loading: true,
        noproducts: false,
        nocustomer: false,
        formData: {
            customer: {},
            products: [],
            phone: "",
            address: "",
            notes: "",
            status:''
        },
        phoneRules: [
            v => !!v || "Phone is required",
            v =>
                (v && v.length <= 12) ||
                "Phone must be less than 12 characters",
            v => Number.isInteger(Number(v)) || "The value must be a number.",
            v => v > 0 || "The value must be greater than zero"
        ],
        addressRules: [
            v => !!v || "Address is required",
            v =>
                (v && v.length <= 200) ||
                "Address must be less than 200 characters"
        ]
    }),
    methods: {
        validate() {
            if (this.formData.products.length == 0) {
                //checking if array is not empty
                this.noproducts = true;
            } else if (Object.entries(this.formData.customer).length === 0) {
                // checking if object is not empty
                this.nocustomer = true;
            } else {
                this.loading = true;
                this.editOrder(this.formData);
            }
        },
        ...mapActions({
            editOrder: "editOrder",
            getAddInfo: "getAddInfo",
            showOrder:'showOrder'
        }),
        addItem() {
            if (
                Object.keys(this.selected_product).length != 0 &&
                this.quantity != ""
            ) {
                Object.assign(this.selected_product, {
                    amount: this.quantity
                });

                if (this.selected_product.discount > 0) {
                    this.selected_product.price =
                        this.selected_product.discount_price * this.quantity;
                } else {
                    this.selected_product.price =
                        this.selected_product.price * this.quantity;
                }

                this.gtotal += this.selected_product.price;
                this.qtotal += parseInt(this.quantity);

                this.formData.products.push(this.selected_product);
                this.selected_product = "";
                this.quantity = "";
                this.valid = true;
                this.noproducts = false;
            }
        },
        removeItem(index) {
            this.gtotal = Math.abs(
                this.gtotal - this.formData.products[index].price
            );
            this.qtotal = Math.abs(
                this.qtotal - parseInt(this.formData.products[index].quantity)
            );
            this.formData.products.splice(index, 1);

            if (this.formData.products.length == 0) {
                this.valid = false;
            }
        },
        filter(item, queryText, itemText) {
            const textOne =
                item.first_name.toLowerCase() +
                " " +
                item.last_name.toLowerCase();
            const id = item.id.toString();
            const searchText = queryText.toLowerCase();

            return (
                textOne.indexOf(searchText) > -1 || id.indexOf(searchText) > -1
            );
        },
        pfilter(item, queryText, itemText) {
            const textOne = item.name.toLowerCase();
            const textTwo = item.type.toLowerCase();
            const id = item.id.toString();
            const searchText = queryText.toLowerCase();

            return (
                textOne.indexOf(searchText) > -1 ||
                id.indexOf(searchText) > -1 ||
                textTwo.indexOf(searchText) > -1
            );
        },
        fullName(item) {
            return `${item.first_name} ${item.last_name}`;
        },
        populateForm() {
            this.getAddInfo();
            this.showOrder();
            if (this.getStateOrders != null || this.getStateOrders != "") {
                this.names = this.getStateNames;
                this.allproducts = this.getStateOrderProducts;
                this.formData.products = this.getStateOrders  
            }
        },
        setAddress() {
            if (Object.entries(this.formData.customer).length != 0) {
                this.formData.phone = this.formData.customer.phone;
                this.formData.address = this.formData.customer.description;
                this.formData.notes = this.formData.customer.notes;
            }
        }
    },
    computed: {
        note_error: {
            //creating a computed getter for custom input validation
            // getter
            get: function() {
                if (this.formData.notes) {
                    return this.formData.notes.length > 200
                        ? "Notes must be less than 200 characters"
                        : "";
                }
            }
        },
        quantity_error: {
            //creating a computed getter for custom input validation
            // getter
            get: function() {
                if (this.quantity) {
                    if (this.quantity > 10) {
                        return "Quantity must not be more than 10";
                    } else if (this.quantity == 0) {
                        return "Quantity must not be zero";
                    } else {
                        return "";
                    }
                }
            }
        },
          ...mapGetters({
            getStateNames: "getStateNames",
            getStateOrderProducts: "getStateOrderProducts",
            getStateOrders: "getStateOrders",
            getStateOrderAddress: "getStateOrderAddress",
            getOrderResponse:'getOrderResponse',
            getStateOrderUser:'getStateOrderUser'
        }),
    },
    watch: {
        getOrderResponse: function() {
            //listeing to changes in a vuex state
            if (this.getOrderResponse === "updated") {
                this.$router.push("/dashboard/show-order");
            }
            else if (this.getOrderResponse === "loaded"){
                this.formData.customer = this.getStateOrderUser[0]
                this.formData.phone = this.getStateOrderAddress[0].phone;
                this.formData.address = this.getStateOrderAddress[0].description;
                this.formData.notes = this.getStateOrderAddress[0].notes;
                this.formData.status = this.formData.products[0].status;
                this.loading = false

            }
        }
    },
    mounted() {
        this.populateForm();
        if (sessionStorage.getItem("user-session") != null) {
            const userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );
            this.role = userdata.user.role;
        }
    }
};
</script>

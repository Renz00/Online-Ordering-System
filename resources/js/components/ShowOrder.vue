<template>
    <div>
            <v-container>
                <v-alert
                    v-model="update_alert"
                    class="mx-4 my-2"
                    v-if="this.getOrderResponse() === 'updated'"
                    border="left"
                    close-text="Close Alert"
                    type="success"
                    color="green darken-2"
                    dark
                    dismissible
                >
                    Success! Order has been updated.
                </v-alert>
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
                                                    @click="$router.push({name: 'orders'})"
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
                                        &nbsp;
                                Order Ref: {{ this.address[0].order_group_id }}
                            </v-card-title>
                            <v-container>
                                
                                <v-row>
                                    <v-simple-table fixed-header>
                                        <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">
                                                        Name
                                                    </th>
                                                    <th class="text-left">
                                                        Quantity
                                                    </th>
                                                    <th class="text-left">
                                                        Price
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(item, index) in orders"
                                                    :key="index"
                                                >
                                                    <td>
                                                        {{ item.name }}
                                                        <span style="color: gray;" v-if="item.discount != 0">
                                                            <em>{{ item.discount }}% Off</em>
                                                            </span>
                                                    </td>
                                                    <td>{{ item.amount }}</td>
                                                    <td v-if="item.discount === 0">₱ {{ item.price }}</td>
                                                    <td v-if="item.discount > 0">₱ {{ item.discount_price }}</td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                    <v-simple-table>
                                        <template>
                                            <tbody>
                                                <tr style="color:gray;">
                                                    <td>{{ this.orders.length }} Item(s)</td>
                                                    <td> </td><td> </td><td> </td>
                                                    <td>Total: ₱ {{ this.compTotal}}</td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                </v-row>
                                <v-divider></v-divider>
                                <v-card-text class="black--text text-center">
                                    <h5>
                                        - Address Details -
                                    </h5>
                                    <span style="color: gray;">Recipient:</span>
                                    <br />
                                    {{ this.address[0].recipient }}
                                    <v-row>
                                        <v-col>
                                            <span style="color: gray;"
                                                >Address:</span
                                            >
                                            <br />
                                            {{ this.address[0].description }}
                                        </v-col>
                                        <v-col>
                                            <span style="color: gray;"
                                                >Other Notes:</span
                                            >
                                            <br />
                                            {{ this.address[0].notes }}
                                        </v-col>
                                    </v-row>
                                <v-divider></v-divider>
                                    <p>Status: <span style="color:coral;">{{this.orders[0].status}}</span></p>
                                </v-card-text>
                                <v-card-actions>
                                    <v-row>
                                        <v-col
                                            ><v-btn
                                                @click="editOrder(orders[0].order_group_id)"
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
                                        <v-col v-if="orders[0].status != 'Cancelled'">
                                            <v-btn
                                                @click="generateInvoice(orders[0].order_group_id, address[0].created_at)"
                                                color="blue"
                                                outlined
                                                block
                                                link
                                                ><v-icon
                                                    >mdi-receipt</v-icon
                                                >
                                                Print Invoice</v-btn
                                            >
                                        </v-col>
                                        <v-col
                                            ><v-btn
                                                @click="deleteOrder(orders[0].order_group_id)"
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
import dateFormat from "dateformat";
var CryptoJS = require("crypto-js");

export default {
    data: () => ({
        loading: true,
        empty: true,
        update_alert: true,
        orders: [],
        address: []
    }),
    methods: {
        editOrder(id) {
            this.$router.push({ name: "editorder" });
        },
        deleteOrder(id) {
            console.log('deleting order...')
            this.destroyOrder(id);
        },
        generateInvoice(order_group, date){
           
            var filename = order_group+'-'+ dateFormat(date, "dd-mm-yy")
            this.genInvoice({order_group, filename})
        },
        populateCard() {
            this.showOrder();

            if (
                this.getStateOrders() != null ||
                this.getStateOrders() != "" ||
                this.getStateOrderAddress() != null ||
                this.getStateOrderAddress() != ""
            ) {
                this.orders = this.getStateOrders();
                this.address = this.getStateOrderAddress();
                this.empty = false;
            }
        },
        ...mapGetters({
            getStateOrders: "getStateOrders",
            getOrderResponse: "getOrderResponse",
            getStateOrderAddress: "getStateOrderAddress"
        }),
        ...mapActions({
            showOrder: "showOrder",
            destroyOrder: "destroyOrder",
            genInvoice: 'genInvoice'
        }),
        ...mapMutations({
            clearOrderResponse: "clearOrderResponse"
        })
    },
    watch: {
        "$store.state.Orders.order_response": function() {
            //listeing to changes in a vuex state
            if (this.$store.state.Orders.order_response === "deleted") {
                if (sessionStorage.getItem("user-session") != null) {
                    //deleting the product id from user-session
                    //   Decrypt
                    var userdata = JSON.parse(
                        CryptoJS.AES.decrypt(
                            sessionStorage.getItem("user-session"),
                            "vJaDBQadMaw108cNVXPl"
                        ).toString(CryptoJS.enc.Utf8)
                    );
                    if ("order_group" in userdata) {
                        //if product id already exists, delete it
                        delete userdata.order_group;
                    }
                    let encrypted = CryptoJS.AES.encrypt(
                        JSON.stringify(userdata),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString();
                    sessionStorage.setItem("user-session", encrypted);
                }
                this.$router.push("/dashboard/orders");
            }
            else if (this.$store.state.Orders.order_response === "loaded"){
                this.loading = false;
            }
        }
    },
    computed: {
        compTotal(){
        let total = 0;
        this.orders.forEach((item, i) => {
            if (item.discount > 0){
                total += parseFloat(item.discount_price);
            }
            else {
                total += parseFloat(item.price);
            }
            
        });
        return total.toFixed(2);
        }
    },
    mounted() {
        this.populateCard();
    },
    beforeRouteLeave(to, from, next) {
        if (this.$store.state.Orders.order_response != "deleted") {
            this.clearOrderResponse();
        }

        next();
    }
};
</script>

<template>
    <div>
            <v-container>
                 <v-dialog
                v-model="dialog"
                hide-overlay
                persistent
                width="300"
                v-if="this.getReportResponse != 'loaded'"
                >
                <v-card
                    color="cyan"
                    dark
                >
                    <v-card-text>
                    Retrieving data...
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                    </v-card-text>
                </v-card>
                </v-dialog>
                <v-row>
                    <v-col>
                        <v-card class="mt-4 mx-auto" max-width="400" shaped>
                            <v-container>
                                   <v-icon color="red">
                                    mdi-account-box-multiple
                                </v-icon>
                                Registered Users

                                <h1 class="text-center">{{ this.total_users }}</h1>
                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card class="mt-4 mx-auto" max-width="400" shaped>
                            <v-container>
                                   <v-icon color="red">
                                    mdi-cash-register
                                </v-icon>
                                Total Sales

                                <h1 class="text-center">{{ this.total_sales }}</h1>
                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card class="mt-4 mx-auto" max-width="400" shaped>
                            <v-container>
                                <v-icon color="red">
                                    mdi-food
                                </v-icon>
                                Total Orders

                                <h1 class="text-center">{{ this.total_orders }}</h1>
                            </v-container>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col>
                         <v-card v-if="!loading">
                            <div class="text-h6 text-center py-5">
                                <b>Monthly Sales for 2021</b>
                            </div>
                            <v-sheet>
                              <column-chart :data="chartData" height="400px" xtitle="Period" ytitle="Sales"></column-chart>
                            </v-sheet>
                            <v-divider></v-divider>
                        </v-card>
                    </v-col>
                    <v-col>
                         <v-card v-if="!loading">
                            <div class="text-h6 text-center py-5">
                                <b>Best Products</b>
                            </div>
                            <v-sheet>
                              <pie-chart :data="chartData2" height="400px" ></pie-chart>
                            </v-sheet>
                            <v-divider></v-divider>
                        </v-card>
                    </v-col>
                </v-row>
                
                 
            </v-container>
            <v-container fluid>
                <v-row>
                    <v-col>
                       <v-card class="rounded-xl">
                            <v-list three-line>
                                <v-container>
                                <h6 style="color:gray;">Recent Orders</h6>
                                <template v-for="(item, index) in recent_orders">
                        
                                    <v-list-item :key="index" @click="showOrder(item.id)" link>
                                        <v-list-item-avatar>
                                          <v-img :src="'../storage/profile_images/'+item.image"></v-img>
                                        </v-list-item-avatar>

                                        <v-list-item-content>
                                            <v-list-item-title>
                                                 {{item.first_name+' '+item.last_name+' | '}}
                                                <span class="red--text font-weight-bold" v-if="item.status === 'Processing'">{{item.status}}</span>
                                                <span class="blue--text font-weight-bold" v-if="item.status === 'Outgoing'">{{item.status}}</span>
                                                <span class="green--text font-weight-bold" v-if="item.status === 'Delivered'">{{item.status}}</span>
                                                 
                                            </v-list-item-title>
                                            <v-list-item-subtitle>
                                                {{item.itemcount}} Item(s)
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </template>
                                </v-container>
                            </v-list>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card class="rounded-xl">
                            <v-list three-line>
                                <v-container>
                                <h6 style="color:gray;">Recent Reviews</h6>
                                <template v-for="(item, index) in recent_reviews">
                                    <v-list-item :key="index" @click="showReview(item.id)" link>
                                        <v-list-item-avatar>
                                            <v-img :src="'../storage/profile_images/'+item.image"></v-img>
                                        </v-list-item-avatar>

                                        <v-list-item-content>
                                            <v-list-item-title>
                                                {{item.first_name+' '+item.last_name+' | '}}
                                                 <span
                                                v-for="n in item.rating"
                                                :key="n"
                                                >
                                                <v-icon color="yellow">
                                                    mdi-star
                                                </v-icon>
                                            </span>
                                            </v-list-item-title>
                                            <v-list-item-subtitle>
                                                Review for: {{item.name}}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </template>
                                </v-container>
                            </v-list>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
var CryptoJS = require("crypto-js");
export default {
    data: () => ({
        report_orders: {},
        report_products: {},
        recent_orders: [],
        recent_reviews: [],
        total_orders: '',
        total_sales: '',
        total_users: '',
        dialog:true,
        empty: true,
        loading: true
    }),
    methods: {
        showOrder(id){
             if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            var userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );
            if ("order_group" in userdata) {
                delete userdata.order_group;
            }
            Object.assign(userdata, { order_group: id });
            let encrypted = CryptoJS.AES.encrypt(
                JSON.stringify(userdata),
                "vJaDBQadMaw108cNVXPl"
            ).toString();
            sessionStorage.setItem("user-session", encrypted);
            }
            this.$router.push('/dashboard/show-order')

        },
        showReview(id){
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
        ...mapActions({
            getReports: "getReports"
        }),
        ...mapMutations({
            clearReportResponse:'clearReportResponse',
            setReports: 'setReports',
            clearReports: 'clearReports'
        }),
        refreshDashboard(){
              this.report_orders = this.getStateReportOrders
                this.report_products = this.getStateReportProducts
                this.recent_orders = this.getStateRecentOrders
                this.recent_reviews = this.getStateRecentReviews
                this.total_orders = this.getStateTotalOrders
                this.total_sales = this.getStateTotalSales 
                this.total_users = this.getStateTotalUsers 
        },
        clearVariables(){
            this.report_orders= {}
            this.report_products= {}
            this.recent_orders= []
            this.recent_reviews= []
            this.total_orders= ''
            this.total_sales= ''
            this.total_users= ''
        }
    },
    computed:{
        chartData: {
            get: function() {
                
                if (this.report_orders != null && this.report_orders != ''){
                    var data = {}
                   for (const key in this.report_orders) {
                        var total = 0

                        for (const key2 in this.report_orders[key]){
                            total += parseFloat(this.report_orders[key][key2].total)
                        }
                        data[key] = total.toFixed(2);
                    }
                    return data
                }
            }
        },
        chartData2: {
            get: function() {
                
                if (this.report_products != null && this.report_products != ''){
                    var data = {}
                   for (const key in this.report_products) {
                        var count = 0

                        for (const key2 in this.report_products[key]){
                            count += 1
                        }
                        data[key] = count;
                    }
                    return data
                }
            }
        },
        ...mapGetters({
            getStateReportOrders: 'getStateReportOrders',
            getStateReportProducts: 'getStateReportProducts',
            getStateRecentOrders: 'getStateRecentOrders',
            getStateRecentReviews: 'getStateRecentReviews',
            getStateTotalOrders: 'getStateTotalOrders',
            getStateTotalSales: 'getStateTotalSales',
            getStateTotalUsers: 'getStateTotalUsers',
            getReportResponse: "getReportResponse"
        }),
    },
     watch: {
        getReportResponse: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.getReportResponse === 'loaded') {
               this.refreshDashboard()
                this.empty = false;
                this.loading = false;
            }
        }
    },
    mounted(){
        this.getReports();
        window.Echo.channel('DASHBOARD.YzuwyaG5MaT77AGtYrjq')
        .listen('.dashboard-event', (e) => {
            this.clearReports(); //clears the vuex state variables
            this.clearVariables(); //clears the local variables
            console.log(e)
            this.setReports(e.response)
            this.refreshDashboard()
        })
    },
    beforeRouteLeave(to, from, next) {
        this.clearReportResponse()
        next();
    }
};
</script>

<style scoped>
.v-sheet.v-card.v-sheet--shaped {
      background-color: #ffffff !important;
    border-left: 5px solid brown !important
} 
</style>

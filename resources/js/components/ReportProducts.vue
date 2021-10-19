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
                    <v-card color="cyan" dark>
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
                    <v-col
                        >
                        <h3 class="mt-4">
                        <v-icon color="red">mdi-chart-tree</v-icon>
                        
                            Best Products in {{ this.date }}
                        </h3>
                    </v-col>
                    <v-col cols="4">
                        <v-row>
                            <v-col>
                                <v-select
                                    v-model="formData.month"
                                    :items="compMonths"
                                    label="Report Month"
                                    item-text="month"
                                    @change="refresh"
                                    single-line
                                    v-if="this.formData.month != ''"
                                ></v-select>
                            </v-col>
                            <v-col>
                                <v-select
                                    v-model="formData.year"
                                    :items="items"
                                    label="Report Year"
                                    item-text="year"
                                    @change="refresh"
                                    single-line
                                    v-if="this.formData.year != ''"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row>
                    <v-card v-if="!loading">
                        <v-sheet>
                            <column-chart
                                :data="chartData"
                                height="400px"
                                xtitle="Period"
                                ytitle="Sales"
                            ></column-chart>
                        </v-sheet>
                    </v-card>
                </v-row>
                <v-row class="my-5">
                    <v-card v-if="!loading">
                        <v-sheet>
                            <pie-chart
                                :data="chartData"
                                height="400px"
                            ></pie-chart>
                        </v-sheet>
                    </v-card>
                </v-row>
            </v-container>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
var CryptoJS = require("crypto-js");

export default {
    name: "ReportOrders",
    data: () => ({
        report_products: {},
        months: [],
        items: [],
        fullm:  ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                        'August', 'September', 'October', 'November', 'December'],
        formData: {
            month: "",
            year: ""
        },
        dialog: true,
        empty: true,
        loading: true
    }),
    methods: {
        refresh() {
            var marray = this.fullm
            this.formData.month = marray.indexOf(this.formData.month) //getting the number format of the months
            if (this.formData.month <= 0){
                this.formData.month = new Date().getMonth();
            }
            this.clearReportResponse();
            this.showProductReports(this.formData);
            this.formData.month = marray[this.formData.month]
        },
        ...mapActions({
            getReports: "getReports",
            showProductReports: "showProductReports"
        }),
        ...mapMutations({
            clearReportResponse: "clearReportResponse",
            setReportResponse: "setReportResponse"
        }),
    },
    computed: {
        chartData: {
            get: function() {
                if (this.report_products != null && this.report_products != "") {
                    var data = {};
                    for (const key in this.report_products) {
                        var objkey = ''
                        var objcount = 0
                        for (const key2 in this.report_products[key]) {
                            objcount = this.report_products[key].count
                            objkey = this.report_products[key].name
                            data[objkey] = objcount
                        }
                    }
                  return data
                }
            }
        },
        compMonths: {
            get: function() {
                if (this.months != null && this.months != "" && this.formData.year != null && this.formData.year != "") {
                    var monthsarray = []
                   var marray = this.fullm
                    this.months[this.formData.year].map(function(value) {
                        monthsarray.push(marray[value.month-1])
                    });

                    let unique = [... new Set(monthsarray)]
                    return unique
                  
                }
            }
        },
        date:{
            get: function(){
                 var marray = this.fullm
                 if (Number.isInteger(this.formData.month)){
                     return marray[this.formData.month]+' '+this.formData.year
                 }
                 else {
                     var month = marray.indexOf(this.formData.month)
                     return marray[month]+' '+this.formData.year
                 }
            }
        },
        ...mapGetters({
            getStateReportProducts: "getStateReportProducts",
            getReportResponse: "getReportResponse",
            getStateYears: "getStateYears",
            getStateMonths: "getStateMonths"
        })
    },
    watch: {
        getReportResponse: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.getReportResponse === "loaded") {
                
                this.report_products = this.getStateReportProducts;
                this.items = this.getStateYears;
                this.months = this.getStateMonths;
                this.empty = false;
                this.loading = false;
            }
        }
    },
    mounted() {
        this.formData.year = new Date().getFullYear();
        this.formData.month = new Date().getMonth();
        this.showProductReports(this.formData);
    },
    beforeRouteLeave(to, from, next) {
        this.clearReportResponse();
        next();
    }
};
</script>

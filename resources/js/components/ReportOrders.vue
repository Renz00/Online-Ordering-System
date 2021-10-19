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
                        <h3 class="mt-4">
                        <v-icon color="red">mdi-chart-tree</v-icon>
                        Monthly Sales for {{ this.formData.date }}</h3></v-col>
                    <v-col cols="2">
                        <v-select
                        v-model="formData.date"
                        :items="items"
                        label="Report Period"
                        item-text="date"
                        @change="refresh"
                        single-line
                        v-if="this.formData.date != ''"
                        ></v-select>
                    </v-col>
                    <v-col cols="2" class="mt-4">
                        <v-btn color="primary" @click="exportReport">
                            <v-icon>
                                mdi-printer
                            </v-icon>
                            Print
                        </v-btn>
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
        report_orders: {},
        items: [],
        formData:{
            date: ''
        },
        dialog: true,
        empty: true,
        loading: true
    }),
    methods: {
        refresh(){
            this.clearReportResponse()
            this.showOrderReports(this.formData)
        },
        exportReport(){
            this.exportOrderReports(this.formData)
        },
        ...mapActions({
            showOrderReports: "showOrderReports",
            exportOrderReports: "exportOrderReports",
        }),
        ...mapMutations({
            clearReportResponse: "clearReportResponse",
            setReportResponse: 'setReportResponse'
        })
    },
    computed: {
        chartData: {
            get: function() {
                if (this.report_orders != null && this.report_orders != "") {
                    var data = {};
                    for (const key in this.report_orders) {
                        var total = 0;

                        for (const key2 in this.report_orders[key]) {
                            total += parseFloat(
                                this.report_orders[key][key2].total
                            );
                        }
                        data[key] = total.toFixed(2);
                    }
                    return data;
                }
            }
        },
        ...mapGetters({
            getStateReportOrders: "getStateReportOrders",
            getReportResponse: "getReportResponse",
            getStateYears: "getStateYears"
        })
    },
    watch: {
        getReportResponse: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.getReportResponse === "loaded") {
                this.report_orders = this.getStateReportOrders;
                this.items = this.getStateYears
                this.empty = false;
                this.loading = false;
            }
        }
    },
    mounted() {
        this.formData.date = new Date().getFullYear();
        this.showOrderReports(this.formData);
    },
    beforeRouteLeave(to, from, next) {
        this.clearReportResponse();
        next();
    }
};
</script>

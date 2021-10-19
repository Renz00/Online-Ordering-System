This is the main/parent app component
<template>
    <v-app class="defaultFont" id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            v-if="
                this.$route.fullPath != '/404' &&
                    this.$route.fullPath != '/dashboard/login' &&
                    this.$route.fullPath != '/dashboard/register'
            "
            elevation="2"
            app
        >
            <v-list>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title class="text-h6 font-weight-bold">
                            <v-icon color="red" large>
                                mdi-store
                            </v-icon>
                            OOS Dashboard
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <br />
                <v-list-item
                    to="/dashboard/home"
                    active-class="active_class"
                    link
                    v-if="this.role === 'Administrator'"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item>

                <v-list-item
                    to="/dashboard/products"
                    active-class="active_class"
                    link
                    v-if="
                        this.role === 'Administrator' || this.role === 'Cashier'
                    "
                >
                    <v-list-item-icon>
                        <v-icon>mdi-food</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>Products</v-list-item-title>
                </v-list-item>

                <v-list-item
                    to="/dashboard/reviews"
                    active-class="active_class"
                    link
                    v-if="this.role === 'Administrator'"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-star-face</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>Reviews</v-list-item-title>
                </v-list-item>

                <v-list-item
                    to="/dashboard/orders"
                    active-class="active_class"
                    link
                    v-if="
                        this.role === 'Administrator' ||
                            this.role === 'Cashier' ||
                            this.role === 'Courier'
                    "
                >
                    <v-list-item-icon>
                        <v-icon>mdi-food-fork-drink</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>Orders</v-list-item-title>
                </v-list-item>

                <v-list-item
                    to="/dashboard/users"
                    active-class="active_class"
                    link
                    v-if="this.role === 'Administrator'"
                >
                    <v-list-item-icon>
                        <v-icon>mdi-account-box-multiple</v-icon>
                    </v-list-item-icon>

                    <v-list-item-title>Users</v-list-item-title>
                </v-list-item>

                <v-list-group
                    :value="false"
                    color="#0b4141"
                    prepend-icon="mdi-view-dashboard-variant"
                >
                    <template v-slot:activator>
                        <v-list-item-title>Reports</v-list-item-title>
                    </template>

                    <v-list-item
                        to="/dashboard/report-orders"
                        active-class="active_class"
                        link
                        v-if="this.role === 'Administrator'"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-view-dashboard-variant-outline</v-icon>
                        </v-list-item-icon>

                        <v-list-item-title>Monthly Orders</v-list-item-title>
                    </v-list-item>
                    <v-list-item
                        :to="{ name: 'reportproducts' }"
                        active-class="active_class"
                        link
                        v-if="this.role === 'Administrator'"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-view-dashboard-variant-outline</v-icon>
                        </v-list-item-icon>

                        <v-list-item-title>Best Products</v-list-item-title>
                    </v-list-item>
                </v-list-group>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            v-if="
                this.$route.fullPath != '/404' &&
                    this.$route.fullPath != '/dashboard/login' &&
                    this.$route.fullPath != '/dashboard/register'
            "
            color="#0b4141"
            app
        >
            <v-app-bar-nav-icon
                color="white"
                @click="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-spacer></v-spacer>
            <div>
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                        <v-badge
                            :content="notifcount"
                            :value="notifcount"
                            bordered
                            color="red"
                            bottom
                            overlap
                        >
                            <v-btn
                                color="white"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                icon
                            >
                                <v-icon>
                                    mdi-bell
                                </v-icon>
                            </v-btn>
                        </v-badge>
                    </template>
                    <v-list
                        v-if="
                            this.notifications != null &&
                                this.notifications != ''
                        "
                    >
                        <v-list-item
                            v-for="(item, index) in this.notifications"
                            :key="index"
                            @click="showNotif(item.id, item.type)"
                            link
                        >
                            <div v-if="item.type == 'review'">
                                <v-row>
                                    <v-col cols="2">
                                        <v-list-item-avatar>
                                            <v-img
                                                :src="
                                                    '../storage/profile_images/' +
                                                        item.image
                                                "
                                            ></v-img>
                                        </v-list-item-avatar>
                                    </v-col>
                                    <v-col>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                {{
                                                    item.first_name +
                                                        " " +
                                                        item.last_name +
                                                        " | "
                                                }}
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
                                                Review for: {{ item.name }}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-col>
                                </v-row>
                            </div>
                            <div v-if="item.type == 'order'">
                                <v-row>
                                    <v-col cols="2">
                                        <v-list-item-avatar>
                                            <v-img
                                                :src="
                                                    '../storage/profile_images/' +
                                                        item.image
                                                "
                                            ></v-img>
                                        </v-list-item-avatar>
                                    </v-col>
                                    <v-col>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                {{
                                                    item.first_name +
                                                        " " +
                                                        item.last_name +
                                                        " | "
                                                }}
                                                <span
                                                    class="red--text font-weight-bold"
                                                    v-if="
                                                        item.status ===
                                                            'Processing'
                                                    "
                                                    >{{ item.status }}</span
                                                >
                                                <span
                                                    class="blue--text font-weight-bold"
                                                    v-if="
                                                        item.status ===
                                                            'Outgoing'
                                                    "
                                                    >{{ item.status }}</span
                                                >
                                                <span
                                                    class="green--text font-weight-bold"
                                                    v-if="
                                                        item.status ===
                                                            'Delivered'
                                                    "
                                                    >{{ item.status }}</span
                                                >
                                            </v-list-item-title>
                                            <v-list-item-subtitle>
                                                {{ item.itemcount }} Item(s)
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-list-item>
                    </v-list>
                    <v-list v-else>
                        <v-list-item>
                            No Notifications.
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>
            &nbsp;
            <span class="white--text">
                {{
                    userdata != ""
                        ? "Hello! " +
                          userdata.user.first_name +
                          " " +
                          userdata.user.last_name
                        : ""
                }}
                <v-btn color="white" icon loading v-if="loading"> </v-btn>
                <v-tooltip bottom v-if="!loading">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" @click="logout" icon>
                            <v-icon color="white">
                                mdi-logout
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Logout</span>
                </v-tooltip>
            </span>
        </v-app-bar>
        <v-main>
            <router-view></router-view>
        </v-main>
        <v-dialog
            v-model="diaload"
            hide-overlay
            persistent
            width="300"
            v-if="
                this.$route.fullPath != '/dashboard/login' &&
                    this.$route.fullPath != '/dashboard/register'
            "
        >
            <v-card color="success" dark>
                <v-card-text>
                    Loggin out, please wait...
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script>
import Vue from "vue";
import VueRouter from "vue-router";
import Routes from "./routes.js";
import { mapGetters, mapActions, mapMutations } from "vuex";
var CryptoJS = require("crypto-js");
Vue.use(VueRouter);

export default {
    name: "App",
    data: () => ({
        drawer: null,
        selectedItem: 0,
        notifcount: 0,
        notifications: [],
        notifData: {
            id:'',
            type:''
        },
        userdata: "",
        token: "",
        diaload: false,
        loading: false,
        role: "",
        user_id: ""
    }),
    router: new VueRouter(Routes),
    methods: {
        logout() {
            console.log("logging out...");
            this.diaload = true;
            this.loading = true;
            axios
                .post(
                    "/api/logout",
                    {},
                    {
                        headers: {
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${this.token}`
                        }
                    }
                )
                .then(response => {
                    if (response.data.response === "success") {
                        sessionStorage.removeItem("user-session");
                        this.$router.replace({ name: "login" });
                    }
                })
                .catch(error => {
                    //catching any errors
                    console.log(error);
                });
        },
        ...mapActions({
            getNotifications: "getNotifications",
            updateNotification: 'updateNotification'
        }),
        ...mapMutations({
            clearNotifications: "clearNotifications",
            setNotifications: "setNotifications"
        }),
        showNotif(id, type){
            this.notifData.type = type
            this.notifData.id = id
            this.updateNotification(this.notifData); //updating the is_viewed column to true
            if (type == 'order'){
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
                if (this.$route.fullPath != '/dashboard/show-order'){
                     this.$router.push('/dashboard/show-order')
                }
            }
            else if (type == 'review'){
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
                if (this.$route.fullPath != '/dashboard/show-review'){
                     this.$router.push('/dashboard/show-review')
                }
               
            }
        },
        clearNotifVariables() {
            this.notifcount = 0;
            this.notifications = [];
        }
    },
    computed: {
        ...mapGetters({
            getStateNotifOrders: "getStateNotifOrders",
            getStateNotifReviews: "getStateNotifReviews",
            getStateNotifCount: "getStateNotifCount"
        })
    },
    watch: {
        getStateNotifCount: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.getStateNotifCount > 0) {
                this.notifcount = this.getStateNotifCount;
                var self = this;
                this.getStateNotifOrders.map(function(value) {
                    self.notifications.push(value);
                });
                this.getStateNotifReviews.map(function(value) {
                    self.notifications.push(value);
                });
                console.log(this.notifications);
            }
        }
    },
    mounted() {
        this.getNotifications();
        if (sessionStorage.getItem("user-session") != null) {
            //   Decrypt
            this.userdata = JSON.parse(
                CryptoJS.AES.decrypt(
                    sessionStorage.getItem("user-session"),
                    "vJaDBQadMaw108cNVXPl"
                ).toString(CryptoJS.enc.Utf8)
            );
            this.token = this.userdata.token;
            this.role = this.userdata.user.role;
            this.user_id = this.userdata.user.id;

            if (this.role === "Administrator") {
                window.Echo.channel("NOTIFICATION.EMPb2LGWfXTtNOjTEef3").listen(
                    ".notification-event",
                    e => {
                        if (
                            e.response.recentorders != null ||
                            (e.response.recentreviews != null &&
                                e.response.count != null)
                        ) {
                            this.clearNotifications();
                            this.clearNotifVariables();
                            this.setNotifications(e);
                        }
                    }
                );
            }
            this.diaload = false;
        }
    },
    created() {
        this.diaload = true;
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Georama:wght@600&family=Noto+Sans&display=swap');
.white {
    color: white;
}
.dirty-white {
    background-color: #fcf9f2;
}
.active_class{
    background: #0b4141;
    color: white;
}
.defaultFont {
font-family: 'Georama', sans-serif;
}
</style>


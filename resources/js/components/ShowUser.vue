<template>
    <div>
            <v-container>
                <v-alert
                    v-model="update_alert"
                    class="mx-4 my-2"
                    v-if="this.getUserResponse === 'updated'"
                    border="left"
                    close-text="Close Alert"
                    type="success"
                    color="green darken-2"
                    dark
                    dismissible
                >
                    Success! Product has been updated.
                </v-alert>

                <v-alert
                    v-model="delete_error"
                    class="mx-4 my-2"
                    v-if="delete_error"
                    border="left"
                    close-text="Close Alert"
                    type="success"
                    color="pruple darken-2"
                    dark
                    dismissible
                >
                    Warning! You cannot delete your own account.
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
                                                @click="
                                                    $router.push({
                                                        name: 'users'
                                                    })
                                                "
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
                                    :src="'../storage/profile_images/'+this.user[0].image"
                                    :lazy-src="'../storage/profile_images/'+this.user[0].image"
                                    class="center"
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
                                        <h4>
                                            {{
                                                this.user[0].first_name +
                                                    " " +
                                                    this.user[0].last_name
                                            }}
                                        </h4>
                                        <p class="gray">
                                            <em>{{ this.user[0].role }}</em>
                                        </p>
                                        <v-row>
                                            <v-col>
                                                <p>
                                                    <span style="color: gray;">Email:</span>
                                                    <br>
                                                    {{ this.user[0].email }}
                                                </p>
                                            </v-col>
                                            <v-col>
                                                <p>
                                                    <span style="color: gray;">Phone:</span>
                                                    <br>
                                                    {{ this.user[0].phone }}
                                                </p>
                                            </v-col>
                                        </v-row>
                                    </div>
                                    <div v-if="this.address != null && this.address != ''">
                                   <v-divider class="mx-12"></v-divider>
                                    <v-card-text class="black--text text-center">
                                        <h5>
                                            - Address Details -
                                        </h5>
                                        <v-row>
                                            <v-col>
                                                <span style="color: gray;">Description:</span>
                                                <br>
                                                {{ this.address[0].description }}
                                            </v-col>
                                            <v-col>
                                                <span style="color: gray;">Other Notes: </span>
                                                <br>
                                                {{ this.address[0].notes }}
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                    </div>
                                    <v-card-actions>
                                        <v-row>
                                            <v-col
                                                ><v-btn
                                                    @click="
                                                        editUser(user[0].id)
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
                                                        deleteUser(user[0].id)
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
    data: () => ({
        loading: true,
        empty: true,
        update_alert: true,
        delete_error: false,
        user: {},
        address:{}
    }),
    methods: {
        editUser(id) {
            this.$router.push({ name: "edituser" });
        },
        deleteUser(id) {
            if (sessionStorage.getItem("user-session") != null) {
                //deleting the product id from user-session
                //   Decrypt
                var userdata = JSON.parse(
                    CryptoJS.AES.decrypt(
                        sessionStorage.getItem("user-session"),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString(CryptoJS.enc.Utf8)
                );

                if (userdata.user.id != id) {
                    this.destroyUser(id);
                } else {
                    this.delete_error = true;
                    window.scrollTo(0, 0);
                }
            }
        },
        populateCard() {
            this.user = {};
            this.showUser();

            if (this.getStateUsers != null || this.getStateUsers != "" || this.getStateAddress != null || this.getStateAddress != '') {
                this.user = this.getStateUsers;
                this.address = this.getStateAddress;
                this.empty = false;
            }

            
        },
        ...mapActions({
            showUser: "showUser",
            destroyUser: "destroyUser"
        }),
        ...mapMutations({
            clearUserResponse: "clearUserResponse"
        })
    },
    watch: {
        user: function() {
            if (this.user != null && this.user != "" && this.empty != true) {
                this.loading = false;
            }
        },
        getUserResponse: function() {
            //listeing to changes in a vuex state
            if (this.getUserResponse === "deleted") {
                if (sessionStorage.getItem("user-session") != null) {
                    //deleting the product id from user-session
                    //   Decrypt
                    var userdata = JSON.parse(
                        CryptoJS.AES.decrypt(
                            sessionStorage.getItem("user-session"),
                            "vJaDBQadMaw108cNVXPl"
                        ).toString(CryptoJS.enc.Utf8)
                    );
                    if ("user_id" in userdata) {
                        //if product id already exists, delete it
                        delete userdata.user_id;
                    }
                    let encrypted = CryptoJS.AES.encrypt(
                        JSON.stringify(userdata),
                        "vJaDBQadMaw108cNVXPl"
                    ).toString();
                    sessionStorage.setItem("user-session", encrypted);
                }
                this.$router.push("/dashboard/users");
            }
        }
    },
    computed:{
        //Always place vuex mapgetters and mapstates inside a computed hook
        ...mapGetters({
            getStateUsers: "getStateUsers",
            getUserResponse: "getUserResponse",
            getStateAddress: "getStateAddress",  
        }),
    },
    mounted() {
        this.populateCard();
    },
    beforeRouteLeave(to, from, next) {
        if (this.getUserResponse != "deleted") {
            this.clearUserResponse();
        }

        next();
    }
};
</script>

<style scoped>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  object-fit: fill; 
  width: 100%;
  height: 400px;
}
</style>
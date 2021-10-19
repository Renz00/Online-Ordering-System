<template>
    <div>
        <v-container class="mt-5">
            <v-card class="mx-auto py-2" width="700" loading v-if="loading">
                <v-card-title>
                    Loading...
                </v-card-title>
            </v-card>
            <v-card class="mx-auto py-2" width="700" v-if="!loading">
                <v-form
                    ref="form"
                    @submit.prevent="validate('others')"
                    v-model="valid"
                    lazy-validation
                >
                    <v-card-text>
                        <v-row>
                            <v-col>
                                <h4>
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
                                    Edit
                                    {{
                                        this.formData[0].first_name +
                                            " " +
                                            this.formData[0].last_name
                                    }}
                                </h4>
                            </v-col>
                            <v-col class="text-right">
                                <v-dialog
                                    transition="dialog-top-transition"
                                    persistent
                                    width="400"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="#db423d"
                                            class="white--text"
                                            v-bind="attrs"
                                            v-on="on"
                                        >
                                            <v-icon>
                                                mdi-security
                                            </v-icon>
                                            Change Password
                                        </v-btn>
                                    </template>
                                    <template v-slot:default="dialog">
                                        <v-card>
                                            <v-toolbar color="#db423d" dark
                                                ><v-icon>
                                                mdi-security
                                            </v-icon> Change Password</v-toolbar
                                            >
                                            <v-card-text>
                                                <v-container>
                                                    <v-text-field
                                                        v-model="
                                                            changePassword.currentpassword
                                                        "
                                                        :rules="passwordRules"
                                                        type="password"
                                                        label="Current Password"
                                                        name="password"
                                                        required
                                                    ></v-text-field>

                                                    <v-text-field
                                                        v-model="
                                                            changePassword.newpassword
                                                        "
                                                        :rules="passwordRules"
                                                        type="password"
                                                        label="New Password"
                                                        name="password"
                                                        required
                                                    ></v-text-field>

                                                    <v-text-field
                                                        v-model="
                                                            changePassword.cpassword
                                                        "
                                                        :rules="[
                                                            v =>
                                                                !!v ||
                                                                'Confirm Password is required',
                                                            changePassword.newpassword ===
                                                                changePassword.cpassword ||
                                                                'Passwords must match'
                                                        ]"
                                                        type="password"
                                                        label="Confirm New Password"
                                                        name="cpassword"
                                                        required
                                                    ></v-text-field>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions class="justify-end">
                                                <v-btn
                                                    text
                                                    @click="
                                                        dialog.value = false
                                                    "
                                                    >Cancel</v-btn
                                                >
                                                <v-btn
                                                    text
                                                    @click="
                                                        validate('password')
                                                    "
                                                    >Save</v-btn
                                                >
                                            </v-card-actions>
                                        </v-card>
                                    </template>
                                </v-dialog>
                            </v-col>
                        </v-row>

                        <v-select
                            v-model="formData[0].role"
                            :items="items"
                            :rules="[v => !!v || 'Role is required']"
                            label="Role"
                            required
                        ></v-select>

                        <v-text-field
                            v-model="formData[0].username"
                            :rules="[
                                v => !!v || 'Username is required',
                                v =>
                                    (v && v.length <= 30) ||
                                    'Username must be less than 30 characters'
                            ]"
                            label="User Name"
                            name="username"
                            v-if="this.formData[0].username != null"
                            required
                        ></v-text-field>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="formData[0].first_name"
                                    :rules="fnameRules"
                                    label="First Name"
                                    name="firstname"
                                    required
                                ></v-text-field
                            ></v-col>
                            <v-col
                                ><v-text-field
                                    v-model="formData[0].last_name"
                                    :rules="lnameRules"
                                    label="Last Name"
                                    name="lastname"
                                    required
                                ></v-text-field
                            ></v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="formData[0].email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    name="email"
                                    required
                                ></v-text-field
                            ></v-col>
                            <v-col>
                                <v-text-field
                                    v-model="formData[0].phone"
                                    :rules="phoneRules"
                                    prefix="+63"
                                    label="Phone"
                                    name="phone"
                                    required
                                ></v-text-field
                            ></v-col>
                        </v-row>
                        <v-row> 
                            <v-col>
                                 <v-textarea
                                        v-model="formAddress.description"
                                        :counter="200"
                                        :error-messages="address_error"
                                        rows="2"
                                        row-height="20"
                                        label="Address"
                                        clear-icon="mdi-close-circle"
                                        clearable
                                    ></v-textarea>
                            </v-col>
                            <v-col>
                                  <v-textarea
                                        v-model="formAddress.notes"
                                        :error-messages="notes_error"
                                        :counter="200"
                                        rows="2"
                                        row-height="20"
                                        label="Other Notes"
                                        clear-icon="mdi-close-circle"
                                        clearable
                                    ></v-textarea>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-alert
                        dense
                        outlined
                        class="mx-2"
                        type="error"
                        v-if="this.username_error === 'error'"
                        ><v-icon large color="white">
                            mdi-warning-circle
                        </v-icon>
                        Username already exists!
                    </v-alert>
                    <v-card-actions>
                        <v-container>
                            <v-row>
                                <v-btn color="primary" loading v-if="loading">
                                </v-btn>
                                <v-btn
                                :disabled="!valid"
                                    color="primary"
                                    type="submit"
                                    v-if="!loading"
                                >
                                    <v-icon>
                                        mdi-arrow-right-box
                                    </v-icon>
                                    Submit
                                </v-btn>
                            </v-row>
                        </v-container>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-container>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";
var CryptoJS = require("crypto-js");

export default {
    name: "EditUser",
    data: () => ({
        formData: {},
        address:{},
        formAddress:{
            description:'',
            notes:''
        },
        changePassword: {
            currentpassword: "",
            cpassword: "",
            newpassword: ""
        },
        items: ["Administrator", "Cashier", "Courier", 'Customer'],
        valid: true,
        loading: true,
        username_error: "",
        fnameRules: [
            v => !!v || "First Name is required",
            v =>
                (v && v.length <= 100) ||
                "First Name must be less than 100 characters"
        ],
        lnameRules: [
            v => !!v || "Last Name is required",
            v =>
                (v && v.length <= 100) ||
                "Last Name must be less than 100 characters"
        ],
        phoneRules: [
            v => !!v || "Phone is required",
            v =>
                (v && v.length <= 12) ||
                "Phone must be less than 12 characters",
            v => Number.isInteger(Number(v)) || "The value must be a number.",
            v => v > 0 || "The value must be greater than zero"
        ],
        emailRules: [
            v => !!v || "E-mail is required",
            v => /.+@.+\..+/.test(v) || "E-mail must be valid"
        ],
        passwordRules: [
            v => !!v || "Password is required",
            v =>
                (v && v.length >= 8) || "Password must be at least 8 characters"
        ]
    }),
    methods: {
        validate(type) {
            if (this.$refs.form.validate() === true) {
                  axios.post("/api/unique", {
                        username: this.formData[0].username,
                        type: 'update',
                        id: this.formData[0].id
                    })
                    .then(response => {
                        if (response.data === "unique") {
                            this.loading = true;
                            var data = this.formData;
                            var address = this.formAddress;
                            var password = this.changePassword;
                            this.updateUser({ password, address, data, type });
                        } else {
                            this.username_error = "error";
                        }
                    })
                    .catch(error => {
                        //catching any errors
                        console.log(error);
                    });
            
            }
        },
        populateForm() {
            this.showUser();
            this.formData = this.getStateUsers();
            this.address = this.getStateAddress();
        
        },
        ...mapGetters({
            getStateUsers: "getStateUsers",
            getUserResponse: "getUserResponse",
            getStateAddress: "getStateAddress"
        }),
        ...mapActions({
            showUser: "showUser",
            updateUser: "updateUser"
        }),
        ...mapMutations({})
    },
    computed: {
            address_error: { //creating a computed getter for custom input validation
            // getter
            get: function () {
                    if (this.formAddress.description){
                        return this.formAddress.description.length > 200 ? 'Address must be less than 200 characters' : ''
                    }
                }
            },
            notes_error: { //creating a computed getter for custom input validation
            // getter
            get: function () {
                    if (this.formAddress.notes){
                        return this.formAddress.notes.length > 200 ? 'Notes must be less than 200 characters' : ''
                    }
                }
            }
    },
    watch: {
        formData: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.formData != null && this.formData != "") {
                this.loading = false;
                if (typeof this.address !== 'undefined' && this.address.length > 0){
                    this.formAddress.description = this.address[0].description
                    this.formAddress.notes = this.address[0].notes
                }
            }
        },
        "$store.state.Users.user_response": function() {
            //listening to changes in a vuex state
            if (this.$store.state.Users.user_response === "updated") {
                this.$router.push("/dashboard/show-user");
            }
        }
    },
    mounted() {
        this.populateForm();
    }
};
</script>

<template>
        <v-container>
            <v-card class="mx-auto py-2" width="700">
                <v-form
                    ref="form"
                    @submit.prevent="validate"
                    v-model="valid"
                    lazy-validation
                >
                    <v-card-text>
                        <div v-if="this.$route.name === 'adduser'">
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
                                            @click="
                                                $router.push('/dashboard/users')
                                            "
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
                                Create a User
                            </h4>
                        </div>
                        <div v-if="this.$route.name != 'adduser'">
                            <h4>    
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
                                            $router.push({ name: 'login' })
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
                            
                                Create an Account
                            </h4>
                        </div>

                        <v-select
                            v-model="formData.role"
                            :items="formData.items"
                            :rules="[v => !!v || 'Role is required']"
                            label="Role"
                            required
                        ></v-select>

                        <v-text-field
                            v-model="formData.username"
                            :rules="[
                                v => !!v || 'Username is required',
                                v =>
                                    (v && v.length <= 30) ||
                                    'Username must be less than 30 characters'
                            ]"
                            label="User Name"
                            name="username"
                            required
                        ></v-text-field>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="formData.firstname"
                                    :rules="fnameRules"
                                    label="First Name"
                                    name="firstname"
                                    required
                                ></v-text-field
                            ></v-col>
                            <v-col
                                ><v-text-field
                                    v-model="formData.lastname"
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
                                    v-model="formData.email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    name="email"
                                    required
                                ></v-text-field
                            ></v-col>
                            <v-col>
                                <v-text-field
                                    v-model="formData.phone"
                                    :rules="phoneRules"
                                    label="Phone"
                                    name="phone"
                                    required
                                ></v-text-field
                            ></v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-textarea
                                    v-model="formData.address"
                                    :counter="200"
                                    :error-messages="address_error"
                                    hint="Optional"
                                    rows="2"
                                    row-height="20"
                                    label="Address"
                                    clear-icon="mdi-close-circle"
                                    clearable
                                ></v-textarea>
                            </v-col>
                            <v-col>
                                <v-textarea
                                    v-model="formData.notes"
                                    :error-messages="notes_error"
                                    :counter="200"
                                    hint="Optional"
                                    rows="2"
                                    row-height="20"
                                    label="Other Notes"
                                    clear-icon="mdi-close-circle"
                                    clearable
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="formData.password"
                                    :rules="passwordRules"
                                    type="password"
                                    label="Password"
                                    name="password"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    v-model="formData.cpassword"
                                    :rules="[
                                        v =>
                                            !!v ||
                                            'Confirm Password is required',
                                        this.formData.password ===
                                            this.formData.cpassword ||
                                            'Password must match'
                                    ]"
                                    type="password"
                                    label="Confirm Password"
                                    name="cpassword"
                                    required
                                ></v-text-field
                            ></v-col>
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
                        <span class="text-center">
                            Username already exists!
                        </span>
                    </v-alert>
                    <v-card-actions>
                        <v-container>
                            <v-row>
                                <v-btn
                                    color="primary"
                                    loading
                                    v-if="
                                        loading &&
                                            this.username_error != 'error'
                                    "
                                >
                                </v-btn>
                                <v-btn
                                    color="primary"
                                    :disabled="!valid"
                                    type="submit"
                                    v-if="
                                        !loading ||
                                            this.username_error === 'error'
                                    "
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
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";

export default {
    name: "Register",
    data: () => ({
        valid: true,
        loading: false,
        formData: {
            username: "",
            firstname: "",
            lastname: "",
            phone: "",
            address: "",
            notes: "",
            email: "",
            password: "",
            cpassword: "",
            role: null,
            route: "",
            items: ["Administrator", "Cashier", "Courier", "Customer"]
        },
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
        addressRules: [
            v => v.length <= 200 || "Address must be less than 200 characters"
        ],
        noteRules: [
            v => v.length <= 200 || "Notes must be less than 200 characters"
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
        validate() {
            if (this.$refs.form.validate() === true) {
                axios
                    .post("/api/unique", {
                        username: this.formData.username,
                        type: "register"
                    })
                    .then(response => {
                        if (response.data === "unique") {
                            this.loading = true;
                            this.formData.route = this.$route.name;
                            this.register(this.formData);
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
        //Calling an action from the Login module and mapping it to a login function
        ...mapActions({
            register: "registerUser"
        })
    },
    computed: {
        address_error: {
            //creating a computed getter for custom input validation
            // getter
            get: function() {
                if (this.formData.address) {
                    return this.formData.address.length > 200
                        ? "Address must be less than 200 characters"
                        : "";
                }
            }
        },
        notes_error: {
            //creating a computed getter for custom input validation
            // getter
            get: function() {
                if (this.formData.notes) {
                    return this.formData.notes.length > 200
                        ? "Notes must be less than 200 characters"
                        : "";
                }
            }
        }
    },
    watch: {
        "$store.state.Register.add_response": function() {
            if (this.$store.state.Register.add_response === "added") {
                this.$router.push("/dashboard/users");
            }
        }
    },
    mounted() {
        this.username_error = "";
    }
};
</script>

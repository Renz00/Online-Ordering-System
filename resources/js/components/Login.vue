<template>
        <div class="container-fluid mt-5">
            <v-card class="mx-auto py-2" width="500">
                <v-form
                    ref="form"
                    @submit.prevent="login(formData)"
                    v-model="valid"
                    lazy-validation
                >
                    <v-card-text>
                        <h4>Sign in</h4>
                        <v-text-field
                            v-model="formData.username"
                            :rules="unameRules"
                            label="User Name"
                            name="username"
                            required
                        ></v-text-field>

                        <v-text-field
                            v-model="formData.password"
                            :rules="passwordRules"
                            type="password"
                            label="Password"
                            name="password"
                            required
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-btn
                                        color="primary"
                                        loading
                                        v-if="loading && login_error != 'Error'"
                                    >
                                    </v-btn>
                                    <v-btn
                                        color="primary"
                                        type="submit"
                                        v-if="!loading || login_error == 'Error'"
                                    >
                                        <v-icon>
                                            mdi-arrow-right-box
                                        </v-icon>
                                        Submit
                                    </v-btn>
                                </v-col>
                                <v-col class="text-right">
                                    <v-btn
                                        color="success"
                                        :to="{name:'register'}"
                                        :disabled = "loading"
                                    >
                                        <v-icon>
                                            mdi-account-circle
                                        </v-icon>
                                        Register
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                 <v-alert
                                        dense
                                        outlined
                                        type="error"
                                        v-if="this.login_error === 'Error'"
                                        ><v-icon large color="white">
                                            mdi-warning-circle
                                        </v-icon>
                                        Username or Password does not exist
                                    </v-alert>
                            </v-row>
                        </v-container>
                    </v-card-actions>
                </v-form>
            </v-card>
        </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
    name: "Login",
    data: () => ({
        valid: true,
        loading: false,
        formData: {
            username: "",
            password: ""
        },
        loginError: "",
        unameRules: [
            v => !!v || "Username is required",
            v =>
                (v && v.length <= 30) ||
                "Username must be less than 30 characters"
        ],
        passwordRules: [
            v => !!v || "Password is required",
            v =>
                (v && v.length >= 8) || "Password must be at least 8 characters"
        ]
    }),
    methods: {
        login(data) {
            if (this.$refs.form.validate() === true) {
                this.loading = true;
                this.loginUser(data);
            }
        },
        //Calling an action from the Login module and mapping it to a login function
        ...mapActions(["loginUser"]),
         ...mapMutations({
            setDiaload: "setDiaload"
        })

    },
    computed: {
        ...mapGetters({
            login_error: "getLoginError",
            getDiaload: 'getDiaload'
        })
    },
    mounted() {
        this.setDiaload(false)
    }
};
</script>

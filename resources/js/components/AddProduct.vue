<template>
    <div class="mx-5 mt-10">
            <v-container>
                <v-row>
                    <v-col></v-col>
                    <v-col>
                        <v-card width="700">
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
                                                        @click="$router.push({name: 'products'})"
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
                                            &nbsp; Add a Product</v-col
                                        >
                                    </v-row>
                                </v-card-title>
                                <v-card-text>
                                    <v-select
                                        v-model="formData.type"
                                        :items="items"
                                        :rules="[
                                            v => !!v || 'Role is required'
                                        ]"
                                        label="Product Type"
                                        required
                                    ></v-select>
                                    <v-text-field
                                        v-model="formData.name"
                                        :rules="nameRules"
                                        label="Product Name"
                                        required
                                    ></v-text-field>
                                    <v-textarea
                                        v-model="formData.description"
                                        :rules="descRules"
                                        :counter="200"
                                        rows="2"
                                        row-height="20"
                                        name="description"
                                        label="Description"
                                        clear-icon="mdi-close-circle"
                                        clearable
                                        required
                                    ></v-textarea>
                                    <v-row>
                                        <v-col>
                                            <v-text-field
                                                v-model="formData.price"
                                                :rules="priceRules"
                                                label="Price"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col>
                                            <v-text-field
                                                v-model="formData.discount"
                                                append-icon="mdi-percent"
                                                :rules="discountRules"
                                                label="Discount"
                                                required
                                            ></v-text-field
                                        ></v-col>
                                    </v-row>

                                    <v-checkbox
                                        v-model="formData.available"
                                        label="Available"
                                    ></v-checkbox>
                                </v-card-text>
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
import { mapActions } from "vuex";

export default {
    name: "AddProduct",
    data: () => ({
        valid: true,
        loading: false,
        formData: {
            name: "",
            type: "Appetizer",
            description: "",
            price: "",
            discount: "0",
            available: true
        },
        items: ["Appetizer", "Sandwich", "Combo", "Pasta", "Drinks"],
        nameRules: [
            v => !!v || "Name is required",
            v =>
                (v && v.length <= 100) ||
                "Name must be less than 100 characters"
        ],
        descRules: [
            v => !!v || "Description is required",
            v =>
                (v && v.length <= 200) ||
                "Description must be less than 200 characters"
        ],
        priceRules: [
            v => !!v || "Price is required",
            v => !isNaN(v) || "The value must be a number"
        ],
        discountRules: [
            v => !!v || "Discount is required",
            v => !isNaN(v) || "The value must be a number"
        ]
    }),

    methods: {
        validate() {
            if (this.$refs.form.validate() === true) {
                this.loading = true
                this.addProduct(this.formData);
            }
        },
        ...mapActions({
            addProduct: "addProduct"
        })
    },
    watch: {
        "$store.state.Products.product_response": function() { //listeing to changes in a vuex state
            if (this.$store.state.Products.product_response === "stored") {
                this.$router.push("/dashboard/products");
            }
        }
    }
};
</script>

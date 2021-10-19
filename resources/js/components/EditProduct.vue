<template>
    <div class="mx-5 mt-10">
            <v-container>
                <v-row>
                    <v-col></v-col>
                    <v-col>
                         <v-card width="700" loading v-if="loading">
                            <v-card-title>
                                Loading...
                            </v-card-title>
                        </v-card>
                        <v-card width="700" v-if="!loading">
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
                                            &nbsp; Edit {{ this.formData[0].name }}</v-col
                                        >
                                       
                                    </v-row>
                                </v-card-title>
                                <v-card-text>
                                    <v-select
                                        v-model="formData[0].type"
                                        :items="items"
                                        :rules="[
                                            v => !!v || 'Role is required'
                                        ]"
                                        label="Product Type"
                                        required
                                    ></v-select>
                                    <v-text-field
                                        v-model="formData[0].name"
                                        :rules="nameRules"
                                        label="Product Name"
                                        required
                                    ></v-text-field>
                                    <v-textarea
                                        v-model="formData[0].description"
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
                                                v-model="formData[0].price"
                                                :rules="priceRules"
                                                label="Price"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col>
                                            
                                            <v-text-field
                                                v-model="formData[0].discount"
                                                append-icon="mdi-percent"
                                                :rules="discountRules"
                                                label="Discount"
                                                required
                                            ></v-text-field
                                        ></v-col>
                                    </v-row>

                                    <v-checkbox
                                        v-model="formData[0].available"
                                        label="Available"
                                    ></v-checkbox>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn
                                        :disabled="!valid"
                                        type="submit"
                                        color="success"
                                        class="mb-4"
                                        block
                                    >
                                        Save
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
import { mapGetters, mapActions } from "vuex";

export default {
    name: "AddProduct",
    data: () => ({
        loading: true,
        valid: true,
        formData: [],
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
                (v && v.length <= 100) ||
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
                this.updateProduct(this.formData[0]);
            }
        },
         populateProduct() {
            this.showProduct();
            this.formData = this.getStateProducts();
        },
        ...mapGetters({
            getStateProducts: "getStateProducts"
        }),
        ...mapActions({
            showProduct: "showProduct",
            updateProduct: "updateProduct"
        })
    },
    watch: {
        formData: function() {
            //this will show the component when the product object recieves data. This will prevent any reference errors.
            if (this.formData != null && this.formData != "") {
                this.loading = false;

                if (this.formData[0].discount === 0){
                    // This field produced an error in validation because the text field was given an integer instead of a string. 
                    // I have no clue why it gets a validation error for that. To prevent this, I will convert the discount value
                    //using this if statement
                    this.formData[0].discount = '0'
                }
            }
        },
        "$store.state.Products.product_response": function() { //listening to changes in a vuex state
            if (this.$store.state.Products.product_response === "updated") {
                this.$router.push("/dashboard/show-product");
            }
        }
    },
    mounted(){
        this.populateProduct();
    }
};
</script>

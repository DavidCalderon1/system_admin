<template>
    <div id="store">
        <form v-on:submit.prevent="sendRequest">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Código*</label>
                    <input type="number" min="1" minlength="6" maxlength="6" class="form-control form-control-sm"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.code"
                           placeholder="Codigo de 6 digitos...">
                    <small class="form-text text-danger"
                           v-if="validate('code')">{{errors.code[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Referencia*</label>
                    <input type="text" class="form-control form-control-sm"
                           v-bind:class="{'is-invalid': validate('reference') }"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.name"
                           placeholder="Referencia...">
                    <small class="form-text text-danger"
                           v-if="validate('reference')">{{errors.reference[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Categoría*</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('category_id')}"
                            size="sm"
                            v-model="request.category_id">
                        <option v-for="category in categories"
                                v-bind:value="category.id">
                            {{category.name}}
                        </option>
                    </select>
                    <small class="form-text text-danger"
                           v-if="validate('category_id')">{{errors.category_id[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Cargar imagen*</label>

                    <input type="file" class="form-control form-control-sm" v-on:change="handleFileUpload()"
                           accept="image/*">

                    <small class="form-text text-danger"
                           v-if="validate('image')">{{errors.image[0]}}</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label>Costo unitario:</label>
                    <input type="number" min="1" class="form-control form-control-sm"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.base_price"
                           @keypress="calculateTotalCostPurchase"
                           placeholder="$">
                    <small class="form-text text-danger"
                           v-if="validate('base_price')">{{errors.base_price[0]}}</small>
                </div>
                <div class="form-group col-md-2">
                    <label>% iva:</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('vat')}"
                            size="sm"
                            @change="calculateTotalCostPurchase"
                            v-model="request.vat">
                        <option v-for="vatPercentage in vatPercentages"
                                v-bind:value="vatPercentage.value">
                            {{vatPercentage.label}}
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Total compra:</label>
                    <div>{{totalCostPurchase}}</div>
                </div>
                <div class="form-group col-md-2">

                </div>
            </div>
            <hr>
            <button class="btn btn-success btn-sm" v-bind:disabled="isDisabled">Crear</button>
        </form>
        <button class="btn btn-danger ml-1 btn-sm" @click="back">Cancelar</button>
        <button class="btn btn-info btn-sm" @click="clearRequest" v-if="typeof ProductToEdit == 'undefined'">
            Limpiar
        </button>
    </div>
</template>

<script>
    export default {
        name: "FormCreateProductComponent",
        props: {
            routeStore: String,
            routeIndex: String,
            routeAllCategories: String,
            ProductToEdit: String,
        },
        data() {
            return {
                categories: [],
                vatPercentages: [
                    {value: 0, label: '0'},
                    {value: 5, label: '5'},
                    {value: 9, label: '19'},
                ],
                request: {
                    id: 0,
                    category_id: 0,
                    code: '',
                    reference: '',
                    base_price: 0,
                    vat: 0,
                    price: 0,
                    image: '',
                },
                errors: {},
                totalCostPurchase: 0.0,
            }
        },
        mounted() {

            axios.get(this.routeAllCategories).then((response) => {
                this.categories = response.data;
            }).catch((error) => {
                alert('handle server error from here');
            });
        },
        computed: {
            isDisabled() {
                return false;
                return this.request.name === ''
                    || this.request.address === ''
                    || this.request.state_id === 0
                    || this.request.city_id === 0
                    || this.request.phone_number === ''
            },
        },
        methods: {
            calculateTotalCostPurchase() {
                console.log(this.request.vat)
                this.totalCostPurchase = parseFloat(this.request.base_price / this.request.vat);
                console.log(this.totalCostPurchase)
            },
            handleFileUpload() {
                this.request.image = this.$refs.file.files[0];
            },
            setRequest() {
                let warehouseDataToEdit = JSON.parse(this.ProductToEdit)
                this.request.id = warehouseDataToEdit.id
                this.request.name = warehouseDataToEdit.name
                this.request.address = warehouseDataToEdit.address
                this.request.country_code = warehouseDataToEdit.country.code
                this.request.state_id = warehouseDataToEdit.state_id
                this.request.city_id = warehouseDataToEdit.city_id
                this.request.phone_number = warehouseDataToEdit.phone_number
            },

            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
            },
            sendRequest() {
                let routeStore = this.routeStore;
                if (this.request.id > 0) {
                    routeStore = this.getRouteWithId(this.routeStore, this.request.id)
                }

                axios.post(routeStore, this.request)
                    .then((response) => {
                        window.location.href = this.routeIndex;
                    })
                    .catch((error) => {
                        console.log(error.response.data.message)
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else if (typeof error.response.data.message != 'undefined') {
                            alert(error.response.data.message);
                        } else {
                            alert('Internal error')
                        }
                    });
            },
            validate(input) {
                return typeof this.errors[input] != 'undefined';
            },
            clearRequest() {
                this.request = {
                    name: '',
                    address: '',
                    country_code: 'CO',
                    state_id: 0,
                    city_id: 0,
                    phone_number: '',
                }
                this.errors = {}
            },
            back() {
                window.location.href = this.routeIndex
            },
        },
    }
</script>
<style>
    #store label {
        font-size: 12px;
    }

    #store .btn-success {
        float: left;
    }
</style>

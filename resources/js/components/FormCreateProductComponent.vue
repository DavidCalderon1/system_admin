<template>
    <div id="store">
        <form v-on:submit.prevent="sendRequest">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Cargar imagen*</label>
                    <div id="preview">
                        <img v-if="url" :src="url"/>
                    </div>
                    <input type="file" class="form-control form-control-sm"
                           v-on:change="handleFileUpload()"
                           ref="file"
                           v-bind:class="{'is-invalid': validate('image')}"
                           accept="image/*">
                    <small class="form-text text-danger"
                           v-if="validate('image')">{{errors.image[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Código*</label>
                    <input type="number" min="1" minlength="6" maxlength="6" class="form-control form-control-sm"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.code"
                           v-bind:class="{'is-invalid': validate('code')}"
                           placeholder="Codigo de 6 digitos...">
                    <small class="form-text text-danger"
                           v-if="validate('code')">{{errors.code[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Referencia*</label>
                    <input type="text" class="form-control form-control-sm"
                           v-bind:class="{'is-invalid': validate('reference') }"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.reference"
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
                    <small class="form-text text-danger"
                           v-if="categories.length === 0">Debes crear categorias.</small>
                </div>
            </div>
            <hr>
            <h5>Información Precio de compra:</h5>
            <div class="form-row">

                <div class="form-group col-md-2">
                    <label>Costo unitario:</label>
                    <input type="number" min="0" class="form-control form-control-sm"
                           step="0.01"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.base_price"
                           v-bind:class="{'is-invalid': validate('base_price')}"
                           placeholder="$">
                    <small class="form-text text-danger"
                           v-if="validate('base_price')">{{errors.base_price[0]}}</small>
                </div>
                <div class="form-group col-md-1">
                    <label>% iva:</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('vat')}"
                            size="sm"
                            v-model="request.vat">
                        <option v-for="vatPercentage in vatPercentages"
                                v-bind:value="vatPercentage.value">
                            {{vatPercentage.label}}
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-2 text-center">
                    <label class="totals">Total compra:</label>
                    <div>$ {{calculateTotalCostPurchase}}</div>
                </div>
            </div>
            <hr>
            <h5>Información Precio de venta:</h5>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label>Valor unitario:</label>
                    <input type="number" min="0" class="form-control form-control-sm"
                           step="0.01"
                           v-model="request.price"
                           v-bind:class="{'is-invalid': validate('price')}"
                           placeholder="$">
                    <small class="form-text text-danger"
                           v-if="validate('price')">{{errors.price[0]}}</small>
                </div>
                <div class="form-group col-md-1">
                    <label>% iva:</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('vat')}"
                            size="sm"
                            v-model="request.vat">
                        <option v-for="vatPercentage in vatPercentages"
                                v-bind:value="vatPercentage.value">
                            {{vatPercentage.label}}
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-2 text-center">
                    <label class="totals">Total venta:</label>
                    <div>{{totalPrice}}</div>
                </div>
                <div class="form-group col-md-2 text-center">
                    <label class="totals">Total utilidad:</label>
                    <div>{{calculateTotalUtility}}</div>
                </div>
                <div class="form-group col-md-2 text-center">
                    <label class="totals">% Utilidad:</label>
                    <div>{{percentageUtility}} %</div>
                </div>
            </div>
            <hr>
            <h5>Descripción:</h5>
            <div class="form-row">
                <textarea class="form-control form-control-sm"
                          onkeyup="javascript:this.value=this.value.toUpperCase();"
                          v-bind:class="{'is-invalid': validate('description')}"
                          v-model="request.description"
                ></textarea>
                <small class="form-text text-danger"
                       v-if="validate('description')">
                    {{errors['description'][0]}}
                </small>
            </div>
            <hr>

            <button class="btn btn-success btn-sm" v-bind:disabled="isDisabled">Guardar</button>
        </form>
        <button class="btn btn-danger ml-1 btn-sm" @click="back">Cancelar</button>
        <button class="btn btn-info btn-sm" @click="clearRequest" v-if="typeof productToEdit == 'undefined'">
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
            routeAllWarehouses: String,
            productToEdit: String,
        },
        data() {
            return {
                categories: [],
                warehouses: [],
                vatPercentages: [
                    {value: 0, label: '0'},
                    {value: 5, label: '5'},
                    {value: 19, label: '19'},
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
                    description: '',
                },
                errors: {},
                totalValueSale: 0.0,
                totalCostPurchase: 0.0,
                totalUtility: 0.0,
                url: null,
            }
        },
        mounted() {

            axios.get(this.routeAllCategories).then((response) => {
                this.categories = response.data;
            }).catch((error) => {
                alert('handle server error from here');
            });

            if (this.productToEdit !== undefined) {
                this.setRequest();
            }
        },
        computed: {
            isDisabled() {
                return (this.request.category_id === 0 || typeof this.request.category_id === undefined)
                    || this.request.reference === ''
                    || this.request.base_price === '' || isNaN(this.request.base_price)
                    || this.request.price === '' || isNaN(this.request.price)
                    || this.request.phone_number === ''
            },
            calculateTotalCostPurchase() {
                this.request.base_price = parseFloat(this.request.base_price);
                this.request.vat = parseFloat(this.request.vat);
                this.totalCostPurchase = parseFloat(this.request.base_price + (this.request.base_price * (this.request.vat / 100)));
                return this.formatPrice(this.totalCostPurchase);
            },
            totalPrice() {
                this.request.price = parseFloat(this.request.price);
                this.request.vat = parseFloat(this.request.vat);
                this.totalValueSale = parseFloat(this.request.price + (this.request.price * (this.request.vat / 100)));
                return this.formatPrice(this.totalValueSale);
            },
            calculateTotalUtility() {
                this.request.price = parseFloat(this.request.price);
                this.request.vat = parseFloat(this.request.vat);
                this.totalUtility = parseFloat(this.totalUtility)
                this.totalUtility = this.request.price - this.request.base_price

                return this.formatPrice(this.totalUtility);
            },
            percentageUtility() {
                this.totalValueSale = parseFloat(this.totalValueSale);
                this.totalUtility =  parseFloat(this.totalUtility);

                return this.totalUtility / this.request.price * 100;
            },
        },
        methods: {
            formatPrice(value) {
                let val = (value / 1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            handleFileUpload() {
                this.request.image = this.$refs.file.files[0];
                if (typeof this.request.image === "undefined") {
                    this.url = '';
                    this.request.image = '';
                } else {
                    this.url = URL.createObjectURL(this.request.image);
                }
            },
            setRequest() {
                let productDataToEdit = JSON.parse(this.productToEdit)

                this.request.id = productDataToEdit.id
                this.request.category_id = productDataToEdit.category_id
                this.request.code = productDataToEdit.code
                this.request.reference = productDataToEdit.reference
                this.request.base_price = productDataToEdit.base_price
                this.request.vat = productDataToEdit.vat
                this.request.price = productDataToEdit.price
                this.request.description = productDataToEdit.description
                this.request.image = productDataToEdit.image
                this.url= productDataToEdit.image
            },
            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
            },
            sendRequest() {

                let routeStore = this.routeStore;

                if (this.request.id > 0) {
                    routeStore = this.getRouteWithId(this.routeStore, this.request.id)
                }

                let formData = new FormData();
                formData.append('id', this.request.id);
                formData.append('category_id', this.request.category_id);
                formData.append('code', this.request.code);
                formData.append('reference', this.request.reference);
                formData.append('base_price', this.request.base_price);
                formData.append('vat', this.request.vat);
                formData.append('price', this.request.price);
                formData.append('image', this.request.image);
                formData.append('description', this.request.description);

                console.log(this.request)

                axios.post(routeStore, formData)
                    .then((response) => {
                        window.location.href = this.routeIndex;
                    })
                    .catch((error) => {
                        console.log(error.response.data.message)
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                            console.log(this.errors)
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

                const input = this.$refs.file
                input.type = 'text'
                input.type = 'file'

                this.request = {
                    id: 0,
                    category_id: 0,
                    code: '',
                    reference: '',
                    base_price: 0,
                    vat: 0,
                    price: 0,
                    image: '',
                    description: '',
                }
                this.totalValueSale = 0.0;
                this.totalCostPurchase = 0.0;
                this.totalUtility = 0.0;
                this.errors = {}
                this.url = null
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

    #preview {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #preview img {
        max-width: 100%;
        max-height: 500px;
    }
</style>

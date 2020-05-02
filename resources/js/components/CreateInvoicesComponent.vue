<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <label>Número</label>
                <input type="text" class="form-control form-control-sm" value="Numeración automática"
                       disabled="disabled">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <label>Cliente</label>
                <select-component v-model="clientSelectedId"
                                  v-on:response="setRequestClientData"
                                  v-bind:url="routeFilterClientsByIdentityNumber"
                                  :placeholder="'Seleccione el cliente'">
                </select-component>
            </div>
            <div class="col-md-3">
                <div class="autocomplete">
                    <label>Contacto</label>
                    <select-component v-model="request.client_contact" v-bind:options="optionsClientContact"
                                      :placeholder="'Seleccione el contacto'">
                    </select-component>
                </div>
            </div>
            <div class="col-md-3">
                <div class="autocomplete">
                    <label>Fecha de elaboración</label>
                    <b-input-group class="mb-3">
                        <b-form-input
                            id="example-input"
                            v-model="request.date"
                            size="sm"
                            type="text"
                            placeholder="YYYY-MM-DD"
                            autocomplete="off"
                        ></b-form-input>
                        <b-input-group-append>
                            <b-form-datepicker
                                v-model="request.date"
                                button-only
                                right
                                size="sm"
                                locale="en-US"
                                aria-controls="example-input"
                                @context="onContext"
                            ></b-form-datepicker>
                        </b-input-group-append>
                    </b-input-group>
                </div>
            </div>
            <div class="col-md-3">
                <label>Vendedor</label>
                <input type="text" class="form-control form-control-sm">
            </div>

            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th style="width: 300px">Producto</th>
                    <th>Descripción</th>
                    <th>Bodega</th>
                    <th style="width:78px;">Cantidad</th>
                    <th style="width: 90px;">Valor unit.</th>
                    <th style="width:78px;">% Desc.</th>
                    <th style="width:78px;">Valor total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product,k) in request.products" :key="k">
                    <td>
                        <select-component v-on:response="loadProductData($event, k)"
                                          v-bind:url="routeFilterProducts"
                                          :placeholder="'Seleccione el producto'">
                        </select-component>
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm" v-model="product.description"
                                  rows="1"></textarea>

                    </td>
                    <td>
                        <select-component :options="warehousesData" v-model="warehouseSelected"
                                          @input="loadWarehouseData">
                            <option disabled value="0">Seleccione el Bodega</option>
                        </select-component>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="product.quantity">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="product.price">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="product.discount_percentage">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="product.total">
                    </td>
                    <td>
                        <span>
                            <i class="fas fa-minus-circle" @click="remove(k)"
                               v-show="k || ( !k && request.products.length > 1)"></i>
                            <i class="fas fa-plus-circle" @click="add(k)" v-show="k == request.products.length-1"></i>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CreateInvoicesComponent",

        props: {
            routeFilterClientsByIdentityNumber: {
                type: String,
                required: true
            },
            routeGetClientById: {
                type: String,
                required: true
            },
            routeFilterProducts: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                optionsClientContact: [],
                clientSelectedId: '',

                request: {
                    client_id: 0,
                    client_name: '',
                    client_last_name: '',
                    client_identity_number: '',
                    client_identity_type: '',
                    client_contact: '',
                    date: '',
                    products: [
                        {
                            id: 0,
                            name: '',
                            description: '',
                            warehouse: '',
                            quantity: 0,
                            price: 0,
                            discount_percentage: 0,
                            total: 0
                        }
                    ],
                    payments: []
                },
                sellersData: [
                    {id: 1, text: "vendedor 1"},
                    {id: 2, text: "vendedor 2"},
                    {id: 3, text: "vendedro 3"}
                ],
                productsData: [
                    {id: 1, text: "producto 1"},
                    {id: 2, text: "producto 2"},
                    {id: 3, text: "producto 3"}
                ],
                warehousesData: [
                    {id: 1, text: "Bodega 1"},
                    {id: 2, text: "Bodega 2"},
                    {id: 3, text: "Bodega 3"}
                ],
                arr: ["Afghanistan", "Albania", "Algeria", 'esta', 'es una prueba', 'de autoompletado'],
                tengo_resultados: '',
                formatted: '',

                sellerSelected: '',
                productSelected: '',
                warehouseSelected: '',
                value: ''

            }
        },
        mounted() {
            console.log(this.routeFilterClientsByIdentityNumber)
            console.log(this.routeFilterProducts)
        },
        methods: {
            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
            },
            setRequestClientData(clientSelected) {

                this.optionsClientContact = clientSelected.contacts;

                this.request.client_id = clientSelected.id
                this.request.client_name = clientSelected.name
                this.request.client_last_name = clientSelected.last_name
                this.request.client_identity_number = clientSelected.identity_number
                this.request.client_identity_type = clientSelected.identity_type
            },

            loadProductData(productSelected, k) {
                this.request.products[k].id = productSelected.id;
                this.request.products[k].text = productSelected.text
                this.request.products[k].description = productSelected.description
                this.request.products[k].warehouse = productSelected.warehouse
                this.request.products[k].quantity = productSelected.quantity
                this.request.products[k].price = productSelected.price
                this.request.products[k].discount_percentage = productSelected.discount_percentage
                this.request.products[k].total = productSelected.total
            },
            loadSellerData() {
                console.log(this.sellerSelected)
            },
            loadWarehouseData() {
                console.log(this.warehouseSelected)
            },
            onContext(ctx) {
                // The date formatted in the locale, or the `label-no-date-selected` string
                this.formatted = ctx.selectedFormatted
                // The following will be an empty string until a valid date is entered
                this.selected = ctx.selectedYMD
            },
            add(index) {
                this.request.products.push({
                    id: 0,
                    text: '',
                    description: '',
                    warehouse: '',
                    quantity: '',
                    price: 0,
                    discount_percentage: 0,
                    total: 0
                });
            },
            remove(index) {
                this.request.products.splice(index, 1);
            }
        }
    }
</script>

<style scoped>

</style>

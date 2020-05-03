<template>
    <div id="create-invoices">
        <!--        <div class="row">-->
        <!--            <div class="col-md-3">-->
        <!--                <label>Número</label>-->
        <!--                <input type="text" class="form-control form-control-sm" value="Numeración automática"-->
        <!--                       disabled="disabled">-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <hr>-->
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
        </div>
        <hr>
        <table class="table table-striped table-hover table-bordered table-sm">
            <thead class="thead-light text-center">
            <tr>
                <th style="width:200px">Producto</th>
                <th style="width:250px">Descripción</th>
                <th style="width:200px">Bodega</th>
                <th style="width:78px;">Cantidad</th>
                <th style="width:90px;">Valor unit.</th>
                <th style="width:78px;">% Desc.</th>
                <th style="width:70px;">% Iva</th>
                <th style="width:85px;">Total</th>
                <th style="width:48px;"></th>
            </tr>
            </thead>
            <tbody class="text-center">
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
                    <select-component :options="product.warehouses" v-model="warehouseSelected"
                                      @input="loadWarehouseData">
                        <option disabled value="0">Seleccione el Bodega</option>
                    </select-component>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" min="1" v-model="product.quantity">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" v-model="product.price">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" v-model="product.discount_percentage">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" v-model="product.vat">
                </td>
                <td>
                    <!--                        <input type="text" class="form-control form-control-sm" v-bind:value="calculateTotalRow(product)">-->

                    ${{calculateTotalRow(product)}}
                </td>
                <td>
                    <span>
                        <i class="fas fa-minus-circle" @click="remove(k)"
                           v-show="k || ( !k && request.products.length > 1)"></i>
                        <i class="fas fa-plus-circle" @click="add(k)" v-show="k == request.products.length-1"></i>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Total bruto</td>
                <td colspan="3">$ {{formatPrice(calculateGrossTotal)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Descuentos</td>
                <td colspan="3">$ {{formatPrice(calculateDiscountsTotal)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Subtotal</td>
                <td colspan="3">$ {{formatPrice(calculateSubTotal)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">IVA</td>
                <td colspan="3">$ {{formatPrice(calculateVatTotal)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2"><b>Total neto $</b></td>
                <td colspan="3">$ {{formatPrice(calculateTotal)}}</td>
            </tr>
            </tbody>
        </table>
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
                            quantity: 1,
                            price: 0,
                            discount_percentage: 0,
                            vat: 0,
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
                warehousesData: [
                    {id: 1, text: "Bodega 1"},
                    {id: 2, text: "Bodega 2"},
                    {id: 3, text: "Bodega 3"}
                ],
                formatted: '',

                sellerSelected: '',
                productSelected: '',
                warehouseSelected: '',
                value: '',

                totalGross:0,
                totalDiscounts:0,
                subTotal:0,
                total:0,

            }
        },
        mounted() {
            console.log(this.routeFilterClientsByIdentityNumber)
            console.log(this.routeFilterProducts)
        },
        computed: {
            calculateGrossTotal() {
                return this.getTotalGross();
            },
            calculateDiscountsTotal() {
                return this.getTotalDiscount();
            },
            calculateSubTotal() {
                return this.getTotalGross() - this.getTotalDiscount()
            },
            calculateVatTotal(){
                return this.getTotalVat();
            },
            calculateTotal() {
                let subTotal = this.getTotalGross() - this.getTotalDiscount();
                return subTotal + this.getTotalVat();
            }
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
                console.log(productSelected)
                this.request.products[k].id = productSelected.id;
                this.request.products[k].text = productSelected.text
                this.request.products[k].description = productSelected.description
                this.request.products[k].warehouses = productSelected.warehouses
                this.request.products[k].price = productSelected.price
                this.request.products[k].vat = productSelected.vat
                this.request.products[k].total = 0
            },
            calculateTotalRow(product) {

                product.price = (product.price === '') ? 0 : product.price;
                product.quantity = (product.quantity === '') ? 0 : product.quantity;
                product.vat = (product.vat === '') ? 0 : product.vat;
                product.discount_percentage = (product.discount_percentage === '') ? 0 : product.discount_percentage;

                product.price = parseFloat(product.price)
                product.quantity = parseInt(product.quantity)
                product.vat = parseFloat(product.vat)
                product.discount_percentage = parseFloat(product.discount_percentage)

                let vat = product.price * product.vat / 100;
                let discount = product.price * product.discount_percentage / 100;

                product.total = (product.price - discount + vat) * product.quantity;

                return this.formatPrice(product.total);
            },
            getTotalGross() {
                let totalGross = 0;
                for (let i in this.request.products) {
                    totalGross += parseFloat(this.request.products[i].price) * parseFloat(this.request.products[i].quantity)
                }
                return totalGross;
            },
            getTotalDiscount(){
                let totalDiscount = 0;
                for (let i in this.request.products){
                    let discount = parseFloat(this.request.products[i].price) * parseFloat(this.request.products[i].discount_percentage) / 100
                    totalDiscount += discount * this.request.products[i].quantity;
                }
                return totalDiscount;
            },
            getTotalVat(){
                let totalVat = 0;
                for (let i in this.request.products){
                    let unitVat = parseFloat(this.request.products[i].price) * parseFloat(this.request.products[i].vat) / 100;
                    totalVat += unitVat * parseFloat(this.request.products[i].quantity);
                }
                return totalVat;
            },
            formatPrice(value) {
                let val = (value / 1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
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
                    price: 0,
                    quantity: 1,
                    discount_percentage: 0,
                    vat: 0,
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
    div#create-invoices {
        font-size: 12px;
    }
</style>

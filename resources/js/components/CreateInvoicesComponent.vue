<template>
    <div id="create-invoices">
        <div class="row">
            <div class="col-md-3">
                <label>Cliente</label>

                <select2-ajax @response="setRequestClientData($event)" :url="routeFilterClients" style="width: 100%"/>

                <small class="form-text text-danger"
                       v-if="validate('client_name')">{{errors.client_name[0]}}</small>
            </div>
            <div class="col-md-3">
                <div class="autocomplete">
                    <label>Contacto</label>
                    <Select2 :options="optionsClientContact"
                             v-bind:id="'select_contact'"
                             v-model="request.client_contact"
                             :settings="{dropdownAutoWidth:'true', width: 'resolve',}"
                    />

                    <small class="form-text text-danger"
                           v-if="validate('client_contact')">{{errors.client_contact[0]}}</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="autocomplete">
                    <label>Fecha de elaboración</label>
                    <b-input-group class="mb-3">
                        <b-form-input
                            id="example-input"
                            v-model="request.date"
                            v-bind:class="{'is-invalid': validate('date')}"
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
                                locale="es-CO"
                                aria-controls="example-input"
                            ></b-form-datepicker>
                        </b-input-group-append>
                    </b-input-group>
                    <small class="form-text text-danger"
                           v-if="validate('date')">{{errors.date[0]}}</small>
                </div>
            </div>
            <div class="col-md-3">
                <label>Vendedor</label>
                <input type="text" v-model="request.seller_code" class="form-control form-control-sm"
                       v-bind:class="{'is-invalid': validate('seller_code')}">
                <small class="form-text text-danger"
                       v-if="validate('seller_code')">{{errors.seller_code[0]}}</small>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered table-sm">
                <thead class="thead-light text-center">
                <tr>
                    <th style="width:200px">Producto</th>
                    <th style="width:250px">Descripción</th>
                    <th style="width:200px">Bodega</th>
                    <th style="width:100px;">Cantidad</th>
                    <th style="width:150px;">Valor unit.</th>
                    <th style="width:78px;">% Desc.</th>
                    <th style="width:70px;">% Iva</th>
                    <th style="width:85px;">Total</th>
                    <th style="width:48px;"></th>
                </tr>
                </thead>
                <tbody class="text-center">
                <tr v-for="(product,k) in request.products" :key="k">
                    <td>
                        <div>
                            <select2-ajax @response="loadProductData($event, product)"
                                          :url="routeFilterProducts"
                                          :value="product.id"
                                          style="width: 100%"/>
                        </div>

                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.name')">{{errors['products.'+k+'.name'][0]}}</small>
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm" v-model="product.description"
                                  rows="1"></textarea>
                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.description')">{{errors['products.'+k+'.description'][0]}}</small>
                    </td>
                    <td>
                        <div>
                            <Select2
                                v-bind:options="product.warehouses"
                                v-model="product.warehouse_id"
                                :settings="{dropdownAutoWidth:'true', width: 'resolve',}"
                                v-bind:id='"select_warehouse_"+k'/>
                        </div>


                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.warehouse_id')">{{errors['products.'+k+'.warehouse_id'][0]}}</small>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" min="1" v-model="product.quantity">
                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.quantity')">{{errors['products.'+k+'.quantity'][0]}}</small>
                    </td>
                    <td>
                        <currency-input-component v-model="product.price"></currency-input-component>
                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.price')">{{errors['products.'+k+'.price'][0]}}</small>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="product.discount_percentage">
                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.discount_percentage')">{{errors['products.'+k+'.discount_percentage'][0]}}</small>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" v-model="product.vat">
                        <small class="form-text text-danger"
                               v-if="validate('products.'+k+'.vat')">{{errors['products.'+k+'.vat'][0]}}</small>
                    </td>
                    <td>
                        <!--<input type="text" class="form-control form-control-sm" v-bind:value="calculateTotalRow(product)">-->
                        ${{calculateTotalRow(product)}}
                    </td>
                    <td>
                    <span>
                        <i class="fas fa-minus-circle" @click="removeProductRow(k)"
                           v-show="k || ( !k && request.products.length > 1)"></i>
                        <i class="fas fa-plus-circle" @click="addProductRow(k)"
                           v-show="k == request.products.length-1"></i>
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
        <section v-if="calculateTotal > 0">
            <h6>Forma de pago</h6>
            <table class="text-center">
                <thead>
                <tr v-for="(payment,i) in request.payments" :key="i">
                    <th>
                        <label> &nbsp;</label>

                        <select v-model="request.payments[i].way_to_pay" class="form-control form-control-sm"
                                @change="resetCurrentMethod(payment)">
                            <option value="credit">Crédito</option>
                            <option value="cash">Contado</option>
                        </select>
                        <small class="form-text text-danger"
                               v-if="validate('payments.'+i+'.way_to_pay')">{{errors['payments.'+i+'.way_to_pay'][0]}}</small>
                    </th>
                    <th>
                        <label>Valor</label>
                        <currency-input-component v-model="request.payments[i].amount"></currency-input-component>
                        <small class="form-text text-danger"
                               v-if="validate('payments.'+i+'.amount')">{{errors['payments.'+i+'.amount'][0]}}</small>
                    </th>
                    <th v-if="request.payments[i].way_to_pay === 'cash'">
                        <label>Metodo de pago</label>
                        <select v-model="payment.method" class="form-control form-control-sm">
                            <option v-for="payment_method in payment_methods">{{payment_method.name}}</option>
                        </select>
                        <small class="form-text text-danger"
                               v-if="validate('payments.'+i+'.method')">{{errors['payments.'+i+'.method'][0]}}</small>
                    </th>
                    <th v-if="request.payments[i].way_to_pay === 'credit'">
                        <label>Dias de plazo</label>
                        <select v-model="payment.days_to_pay" class="form-control form-control-sm">
                            <option v-for="days_to_pay_option in days_to_pay_options">{{days_to_pay_option}}</option>
                        </select>
                        <small class="form-text text-danger"
                               v-if="validate('payments.'+i+'.method')">{{errors['payments.'+i+'.method'][0]}}</small>
                    </th>
                    <th>
                        <span>
                            <i class="fas fa-minus-circle" @click="removePaymentMethodRow(i)"
                               v-show="i || ( !i && request.payments.length > 1)"></i>
                            <i class="fas fa-plus-circle" @click="addPaymentMethodRow(i)"
                               v-show=" (i == request.payments.length-1) && canAddPayment"></i>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <th>Total formas de pago: ${{formatPrice(calculateTotalPayments)}}</th>
                </tr>
                </thead>
            </table>
        </section>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <label>Observaciones</label>
                <textarea class="form-control form-control-sm" v-model="request.description"></textarea>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <label>Adjuntar Archivo</label>
                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
            </div>
        </div>
        <hr>
        <button class="btn btn-sm btn-success" v-bind:disabled="!canCreate" @click="sendRequest(0)">Guardar</button>
        <button class="btn btn-sm btn-primary" v-bind:disabled="!canCreate" @click="sendRequest(1)">Guardar y
            descargar
        </button>
        <a v-bind:href="routeIndex" class="btn btn-sm btn-danger">Cancelar</a>
    </div>
</template>

<script>
    import Select2 from 'v-select2-component';
    import Select2Ajax from "./Select2Ajax";

    export default {
        name: "CreateInvoicesComponent",
        components: {Select2Ajax, Select2},
        props: {
            routeStore: {
                type: String,
                required: true
            },
            routeIndex: {
                type: String,
                required: true
            },
            routeFilterClients: {
                type: String,
                required: true
            },
            routeFilterProducts: {
                type: String,
                required: true
            },
            routeSaleView: {
                type: String,
                required: true
            },
            routeSaleDownload: {
                type: String,
                required: true
            },
            paymentMethods: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                optionsClientContact: [],

                request: {
                    client_id: 0,
                    client_name: '',
                    client_last_name: '',
                    client_identity_number: '',
                    client_identity_type: '',
                    client_contact: '',
                    seller_code: '',
                    date: '',
                    description: '',
                    file: '',
                    products: [
                        {
                            id: 0,
                            name: '',
                            description: '',
                            warehouse_id: 0,
                            quantity: 1,
                            price: 0,
                            discount_percentage: 0,
                            vat: 0,
                            total: 0,
                        }
                    ],
                    payments: [
                        {
                            way_to_pay: 'cash',
                            amount: 0,
                            method: '',
                            days_to_pay: '',
                        }
                    ]
                },
                payment_methods: [],
                days_to_pay_options: [15, 30, 90, 120, 360],
                errors: {},
            }
        },
        mounted() {
            this.payment_methods = JSON.parse(this.paymentMethods);
        },
        computed: {
            calculateGrossTotal() {
                return this.getTotalGross();
            },
            calculateDiscountsTotal() {
                return this.getTotalDiscount();
            },
            calculateSubTotal() {
                return this.getSubTotal()
            },
            calculateVatTotal() {
                return this.getTotalVat();
            },
            calculateTotal() {
                return this.getTotal();
            },
            calculateTotalPayments() {
                return this.getTotalPayments();
            },
            canAddPayment() {
                return this.getTotalPayments() < this.getTotal();
            },
            canCreate() {

                for (let i in this.request.products) {
                    if (this.request.products[i].warehouse_id === 0 || this.request.products[i].warehouse_id === '') {
                        return false;
                    }
                }

                for (let i in this.request.payments) {

                    if (typeof this.request.payments[i].method == "undefined") {
                        return false;
                    }

                    if (this.request.payments[i].way_to_pay === 'cash' && this.request.payments[i].method === '') {
                        return false;
                    }

                    if (this.request.payments[i].way_to_pay === 'credit' && typeof this.request.payments[i].days_to_pay == 'undefined') {
                        return false;
                    }
                }

                return this.request.client_id > 0
                    && this.request.client_contact.length > 0
                    && this.request.date.length > 0
                    && this.request.seller_code.length > 0
                    && this.getTotal() > 0
                    && this.getTotalPayments() === this.getTotal();
            }
        },
        methods: {
            resetCurrentMethod(payment) {
                payment.method = '';
                payment.days_to_pay = '';
            },
            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
            },
            setRequestClientData(response) {
                response.data.filter(client => {
                    if (client.id == response.selected) {
                        this.optionsClientContact = client.contacts;
                        this.request.client_id = client.id
                        this.request.client_name = client.name
                        this.request.client_last_name = client.last_name
                        this.request.client_identity_number = client.identity_number
                        this.request.client_identity_type = client.identity_type
                    }
                });
            },
            loadProductData(response, product) {
                response.data.filter(item => {
                    if (item.id == response.selected) {
                        product.id = item.id;
                        product.text = item.text
                        product.name = item.text
                        product.description = item.description
                        product.warehouses = item.warehouses
                        product.price = item.price
                        product.vat = item.vat
                        product.total = 0
                    }
                })
            },
            resetWarehouseId(product) {
                product.warehouse_id = 0;
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

                return Math.round(product.total * 100) / 100;
            },
            getTotalGross() {
                let totalGross = 0;
                for (let i in this.request.products) {
                    totalGross += parseFloat(this.request.products[i].price) * parseFloat(this.request.products[i].quantity)
                }
                return Math.round(totalGross * 100) / 100;
            },
            getTotalDiscount() {
                let totalDiscount = 0;
                for (let i in this.request.products) {
                    let discount = parseFloat(this.request.products[i].price) * parseFloat(this.request.products[i].discount_percentage) / 100
                    totalDiscount += discount * this.request.products[i].quantity;
                }
                return Math.round(totalDiscount * 100) / 100;
            },
            getTotalVat() {
                let totalVat = 0;
                for (let i in this.request.products) {
                    let unitVat = parseFloat(this.request.products[i].price) * parseFloat(this.request.products[i].vat) / 100;
                    totalVat += unitVat * parseFloat(this.request.products[i].quantity);
                }
                return Math.round(totalVat * 100) / 100;
            },
            getSubTotal() {
                let subTotal = this.getTotalGross() - this.getTotalDiscount();
                return Math.round(subTotal * 100) / 100;
            },
            getTotal() {
                let total = this.getSubTotal() + this.getTotalVat();
                return Math.round(total * 100) / 100;
            },
            getTotalPayments() {
                let totalPayments = 0;
                for (let i in this.request.payments) {
                    totalPayments += parseFloat(this.request.payments[i].amount)
                }
                return Math.round(totalPayments * 100) / 100;
            },
            formatPrice(value) {
                let val = (value / 1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            addProductRow(index) {
                this.request.products.push({
                    id: 0,
                    text: '',
                    description: '',
                    warehouse_id: 0,
                    price: 0,
                    quantity: 1,
                    discount_percentage: 0,
                    vat: 0,
                    total: 0
                });
            },
            removeProductRow(index) {
                this.request.products.splice(index, 1);
            },
            addPaymentMethodRow(index) {

                let nextTotal = this.getTotal() - this.getTotalPayments();

                this.request.payments.push({
                    'way_to_pay': 'cash',
                    'amount': nextTotal,
                    'method': '',
                });
            },
            removePaymentMethodRow(index) {
                this.request.payments.splice(index, 1);
            },
            handleFileUpload() {
                this.request.file = this.$refs.file.files[0];
            },
            validate(input) {
                return typeof this.errors[input] != 'undefined';
            },
            sendRequest(download) {

                let formData = new FormData();
                formData.append('client_id', this.request.client_id);
                formData.append('client_name', this.request.client_name);
                formData.append('client_last_name', this.request.client_last_name);
                formData.append('client_identity_number', this.request.client_identity_number);
                formData.append('client_identity_type', this.request.client_identity_type);
                formData.append('client_contact', this.request.client_contact);
                formData.append('seller_code', this.request.seller_code);
                formData.append('date', this.request.date);
                formData.append('description', this.request.description);
                formData.append('file', this.request.file);
                formData.append('products', JSON.stringify(this.request.products));
                formData.append('payments', JSON.stringify(this.request.payments));

                axios.post(this.routeStore, formData, {headers: {"Content-Type": "application/json"}})
                    .then(resp => {
                        this.$alertify.success(resp.data.data.message);
                        let url = this.getRouteWithId(this.routeSaleView, resp.data.data.sale.id)
                        if (download) {
                            let urlDownload = this.getRouteWithId(this.routeSaleDownload, resp.data.data.sale.id);

                            axios({
                                url: urlDownload,
                                method: 'GET',
                                responseType: 'blob',
                            }).then((response) => {
                                let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                                let fileLink = document.createElement('a');
                                fileLink.href = fileURL;
                                fileLink.setAttribute('download', resp.data.data.sale.prefix + '-' + resp.data.data.sale.consecutive + '.pdf');
                                document.body.appendChild(fileLink);
                                fileLink.click();
                                window.location.href = url;
                            });
                        } else {
                            window.location.href = url;
                        }
                    })
                    .catch(error => {
                        console.log(error.response)
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        } else if (typeof error.response.data.errors.message != 'undefined') {
                            this.$alertify.error(error.response.data.errors.message);
                        } else {
                            this.$alertify.error('Internal error');
                        }
                    });
                console.log(this.request, download)
            },
        }
    }
</script>

<style scoped>
    div#create-invoices {
        font-size: 12px;
    }
</style>

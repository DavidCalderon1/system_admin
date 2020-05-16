<template>
    <div id="edit-purchase">
        <div class="row">
            <div class="col-md-3">
                <label>Proveedor</label>

                <select2-ajax @response="setRequestProviderData($event)"
                              :url="routeFilterProviders"
                              :options="[purchaseDataToEdit]"
                              :value="request.provider_id"
                              style="width: 100%"/>

                <small class="form-text text-danger"
                       v-if="validate('provider_name')">{{errors.provider_name[0]}}</small>
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
                <label>Número de factura</label>
                <input type="text" v-model="request.provider_invoice_number" class="form-control form-control-sm"
                       v-bind:class="{'is-invalid': validate('provider_invoice_number')}">
                <small class="form-text text-danger"
                       v-if="validate('provider_invoice_number')">{{errors.provider_invoice_number[0]}}</small>
            </div>
            <div class="col-md-3">
                <label>Impuestos</label><br>
                <input type="radio" id="yes" v-model="request.include_taxes" v-bind:value="'1'">
                <label for="yes">Sí</label>
                <input type="radio" id="no" v-model="request.include_taxes" v-bind:value="'0'" class="ml-3">
                <label for="no">No</label><br>
            </div>
        </div>
        <hr>
        <table class="table table-striped table-hover table-bordered table-sm" >
            <thead class="thead-light text-center">
            <tr>
                <th style="width:200px">Producto</th>
                <th style="width:250px">Descripción</th>
                <th style="width:200px">Bodega</th>
                <th style="width:150px;">Cant.</th>
                <th style="width:70px;">% Iva</th>
                <th style="width:auto;">Ret. en la fuente</th>
                <th style="width:180px;">Total</th>
                <th style="width:48px;"></th>
            </tr>
            </thead>
            <tbody class="text-center">
            <tr v-for="(product,k) in request.products" v-bind:key="k">
                <td>
                        <select2-ajax @response="loadProductData($event, product)"
                                      :options="(product.id > 0) ? [product] : []"
                                      :url="routeFilterProducts"
                                      :value="product.id"
                                      style="width: 100%"
                        />

                    <small class="form-text text-danger"
                           v-if="validate('products.'+k+'.name')">{{errors['products.'+k+'.name'][0]}}</small>
                </td>
                <td>
                    <textarea class="form-control form-control-sm" v-model="product.description" rows="1"></textarea>
                    <small class="form-text text-danger"
                           v-if="validate('products.'+k+'.description')">{{errors['products.'+k+'.description'][0]}}</small>
                </td>
                <td>
                    <div>
                        <Select2
                            v-bind:options="product.warehouses"
                            v-model="product.warehouse_id"
                            :settings="{dropdownAutoWidth:'true', width: 'resolve',}"
                            v-bind:id='"select_warehouse_"+k'
                            style="width: 100%"/>
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
                    <input type="text" class="form-control form-control-sm" v-model="product.vat">
                    <small class="form-text text-danger"
                           v-if="validate('products.'+k+'.vat')">{{errors['products.'+k+'.vat'][0]}}</small>
                </td>
                <td>
                    <select class="form-control form-control-sm" style="width: auto"
                            v-model="product.withholding_tax_percentage">
                        <option v-for="withholding_tax_percentage in withholding_tax_percentages"
                                :value="withholding_tax_percentage.percentage">
                            {{withholding_tax_percentage.name}}
                        </option>
                    </select>
                    <small class="form-text text-danger"
                           v-if="validate('products.'+k+'.withholding_tax_percentage')">{{errors['products.'+k+'.withholding_tax_percentage'][0]}}</small>
                </td>
                <td>
                    <currency-input-component v-model="product.total" style="width: auto"></currency-input-component>
                    <small class="form-text text-danger"
                           v-if="validate('products.'+k+'.total')">{{errors['products.'+k+'.total'][0]}}</small>
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
                <td colspan="2">Subtotal</td>
                <td colspan="3">$ {{formatPrice(calculateSubTotal)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Retención en la fuente</td>
                <td colspan="3">$ {{formatPrice(calculateTaxesTotal)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">IVA</td>
                <td colspan="3">$ {{formatPrice(calculateTotalVat)}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2"><b>Total neto $</b></td>
                <td colspan="3">$ {{formatPrice(calculateTotal)}}</td>
            </tr>
            </tbody>
        </table>

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
        name: "CreatePurchaseComponent",
        components: {Select2Ajax, Select2},
        props: {
            routeUpdate: {
                type: String,
                required: true
            },
            routeIndex: {
                type: String,
                required: true
            },
            routeFilterProviders: {
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
            },
            taxes: {
                type: String,
                required: true
            },
            purchaseData: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                purchaseDataToEdit:{
                    purchase_products:[],
                    purchase_payments:[],
                },
                request: {
                    id: 0,
                    provider_id: 0,
                    provider_name: '',
                    provider_identity_number: '',
                    provider_identity_type: '',
                    provider_address: '',
                    provider_phone_number: '',
                    provider_location: '',
                    provider_invoice_number: '',
                    include_taxes: 0,
                    date: '',
                    description: '',
                    file: '',
                    products: [
                        {
                            id: 0,
                            product_id : 0,
                            name: '',
                            description: '',
                            warehouse_id: 0,
                            quantity: 1,
                            withholding_tax_percentage: 0,
                            vat: 0,
                            total: 0
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
                withholding_tax_percentages: [],
                days_to_pay_options: [15, 30, 90, 120, 360],
                errors: {},
            }
        },
        mounted() {
            this.payment_methods = JSON.parse(this.paymentMethods);
            this.withholding_tax_percentages = JSON.parse(this.taxes);
            this.purchaseDataToEdit = JSON.parse(this.purchaseData);

            this.request.id = this.purchaseDataToEdit.id
            this.request.provider_id = this.purchaseDataToEdit.provider_id
            this.request.provider_name = this.purchaseDataToEdit.provider_name
            this.request.provider_identity_number = this.purchaseDataToEdit.provider_identity_number
            this.request.provider_identity_type = this.purchaseDataToEdit.provider_identity_type
            this.request.provider_address = this.purchaseDataToEdit.provider_address
            this.request.provider_phone_number = this.purchaseDataToEdit.provider_phone_number
            this.request.provider_location = this.purchaseDataToEdit.provider_location
            this.request.provider_invoice_number = this.purchaseDataToEdit.provider_invoice_number
            this.request.include_taxes = this.purchaseDataToEdit.include_taxes
            this.request.date = this.purchaseDataToEdit.date
            this.request.description = this.purchaseDataToEdit.description

            this.request.products = this.purchaseDataToEdit.purchase_products;
            this.request.payments = this.purchaseDataToEdit.purchase_payments;

            $('td i.fa-plus-circle').last().click()
            setTimeout(function () {
                $('td i.fa-minus-circle').last().click()
            },1000);

        },
        computed: {
            calculateGrossTotal() {
                return this.getTotalGross();
            },
            calculateSubTotal() {
                return this.getSubTotal()
            },
            calculateTaxesTotal(){
              return this.getTotalTaxes();
            },
            calculateTotalVat(){
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
                    if (this.request.products[i].withholding_tax_percentage < 0 || this.request.products[i].withholding_tax_percentage==null) {
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

                return this.request.provider_id > 0
                    && this.request.date.length > 0
                    && this.request.provider_invoice_number.length > 0
                    && (this.request.include_taxes == 0 ||  this.request.include_taxes == 1)
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
            setRequestProviderData(response) {
                response.data.filter(provider => {
                    if (provider.id == response.selected) {
                        this.request.provider_id = provider.id
                        this.request.provider_name = provider.name
                        this.request.provider_identity_number = provider.identity_number
                        this.request.provider_identity_type = provider.identity_type
                        this.request.provider_address = provider.address
                        this.request.provider_phone_number = provider.phone_number + ' ' + provider.phone_extension
                        this.request.provider_location = provider.city.name + '-' + provider.country.name
                    }
                });
            },
            loadProductData(response, product) {
                response.data.filter(item => {
                    if (item.id == response.selected) {
                        product.id = item.id;
                        product.product_id = item.id;
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
            getTotalGross() {
                let totalGross = 0;
                for (let i in this.request.products) {
                    let vatValue = this.request.products[i].total * this.request.products[i].vat / 100;
                    totalGross += parseFloat(this.request.products[i].total) - vatValue;
                }
                return Math.round(totalGross * 100) / 100;
            },
            getSubTotal() {
                let subTotal = 0;
                for (let i in this.request.products) {
                    subTotal += parseFloat(this.request.products[i].total);
                }
                subTotal -= this.getTotalVat();
                return Math.round(subTotal * 100) / 100;
            },
            getTotalTaxes(){
                let totalTaxes = 0;
                for (let i in this.request.products) {
                    totalTaxes += this.request.products[i].total * this.request.products[i].withholding_tax_percentage / 100;
                }
                return Math.round(totalTaxes * 100) / 100;
            },
            getTotalVat() {
                let totalVat = 0;
                for (let i in this.request.products) {
                    totalVat += parseFloat(this.request.products[i].total) * parseFloat(this.request.products[i].vat) / 100;
                }
                return Math.round(totalVat * 100) / 100;
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
                    product_id: 0,
                    name: '',
                    description: '',
                    warehouse_id: 0,
                    quantity: 1,
                    withholding_tax_percentage: 0,
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
                formData.append('id', this.request.id);
                formData.append('provider_id', this.request.provider_id);
                formData.append('provider_name', this.request.provider_name);
                formData.append('provider_identity_number', this.request.provider_identity_number);
                formData.append('provider_identity_type', this.request.provider_identity_type);
                formData.append('provider_address', this.request.provider_address);
                formData.append('provider_phone_number', this.request.provider_phone_number);
                formData.append('provider_location', this.request.provider_location);
                formData.append('provider_invoice_number', this.request.provider_invoice_number);
                formData.append('include_taxes', this.request.include_taxes);
                formData.append('date', this.request.date);
                formData.append('description', this.request.description);
                formData.append('file', this.request.file);
                formData.append('products', JSON.stringify(this.request.products));
                formData.append('payments', JSON.stringify(this.request.payments));

                axios.post(this.getRouteWithId(this.routeUpdate, this.request.id), formData, {headers: {"Content-Type": "application/json"}})
                    .then(resp => {
                        this.$alertify.success(resp.data.data.message);
                        let url = this.getRouteWithId(this.routeSaleView, resp.data.data.purchase.id)
                        if (download) {
                            let urlDownload = this.getRouteWithId(this.routeSaleDownload, resp.data.data.purchase.id);

                            axios({
                                url: urlDownload,
                                method: 'GET',
                                responseType: 'blob',
                            }).then((response) => {
                                let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                                let fileLink = document.createElement('a');
                                fileLink.href = fileURL;
                                fileLink.setAttribute('download', resp.data.data.purchase.prefix_consecutive + '.pdf');
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
    div#edit-purchase {
        font-size: 12px;
    }
</style>

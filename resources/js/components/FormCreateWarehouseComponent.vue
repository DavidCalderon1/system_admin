<template>
    <div id="store">
        <form v-on:submit.prevent="sendRequest">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nombre*</label>
                    <input type="text" class="form-control form-control-sm"
                           v-bind:class="{'is-invalid': validate('name') }"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.name"
                           placeholder="Nombre...">
                    <small class="form-text text-danger"
                           v-if="validate('name')">{{errors.name[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Dirección*</label>
                    <input type="text" class="form-control form-control-sm"
                           v-bind:class="{'is-invalid': validate('address')}"
                           onkeyup="javascript:this.value=this.value.toUpperCase();"
                           v-model="request.address"
                           placeholder="Dirección...">
                    <small class="form-text text-danger"
                           v-if="validate('address')">{{errors.address[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Teléfono*</label>
                    <input type="number" min="1" class="form-control form-control-sm"
                           v-bind:class="{'is-invalid': validate('phone_number')}"
                           v-model="request.phone_number"
                           placeholder="Teléfono...">
                    <small class="form-text text-danger"
                           v-if="validate('phone_number')">{{errors.phone_number[0]}}</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>País*</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('country_code')}"
                            v-model="request.country_code"
                            size="sm"
                            v-on:change="resetLocation(), getStatesByCountryCode()">
                        <option v-for="country in countries"
                                v-bind:value="country.code">
                            {{country.name}}
                        </option>
                    </select>
                    <small class="form-text text-danger"
                           v-if="validate('country_code')">{{errors.country_code[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Departamento*</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('state_id')}"
                            size="sm"
                            v-model="request.state_id"
                            v-on:change="getCityByStateId">
                        <option v-for="state in states"
                                v-bind:value="state.id">
                            {{state.name}}
                        </option>
                    </select>
                    <small class="form-text text-danger"
                           v-if="validate('state_id')">{{errors.state_id[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Ciudad*</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('city_id')}"
                            size="sm"
                            v-model="request.city_id">
                        <option v-for="city in cities"
                                v-bind:value="city.id">
                            {{city.name}}
                        </option>
                    </select>
                    <small class="form-text text-danger"
                           v-if="validate('city_id')">{{errors.city_id[0]}}</small>
                </div>
            </div>
            <hr>
            <button class="btn btn-success btn-sm" v-bind:disabled="isDisabled">Crear</button>
        </form>
        <button class="btn btn-danger ml-1 btn-sm" @click="back">Cancelar</button>
        <button class="btn btn-info btn-sm" @click="clearRequest" v-if="typeof warehouseToEdit == 'undefined'">
            Limpiar
        </button>
    </div>
</template>

<script>
    export default {
        name: "FormCreateThirdComponent",
        props: {
            routeStore: String,
            routeIndex: String,
            routeAllCountries: String,
            routeStatesByCountryCode: String,
            routeCitiesByStateId: String,
            countryCodeSelectedDefault: String,
            warehouseToEdit: String,
        },
        data() {
            return {
                countries: [],
                states: [],
                cities: [],
                request: {
                    id: 0,
                    name: '',
                    address: '',
                    country_code: 'CO',
                    state_id: 0,
                    city_id: 0,
                    phone_number: '',
                },
                errors: {},
            }
        },
        mounted() {
            this.request.country_code = this.countryCodeSelectedDefault;

            if (this.warehouseToEdit !== undefined) {
                this.setRequest();
            }

            axios.get(this.routeAllCountries).then((response) => {
                this.countries = response.data;
                this.getStatesByCountryCode()
            }).catch((error) => {
                alert('handle server error from here');
            });
        },
        computed: {
            isDisabled() {
                return this.request.name === ''
                    || this.request.address === ''
                    || this.request.state_id === 0
                    || this.request.city_id === 0
                    || this.request.phone_number === ''
            },
        },
        methods: {
            setRequest() {
                let warehouseDataToEdit = JSON.parse(this.warehouseToEdit)
                this.request.id = warehouseDataToEdit.id
                this.request.name = warehouseDataToEdit.name
                this.request.address = warehouseDataToEdit.address
                this.request.country_code = warehouseDataToEdit.country.code
                this.request.state_id = warehouseDataToEdit.state_id
                this.request.city_id = warehouseDataToEdit.city_id
                this.request.phone_number = warehouseDataToEdit.phone_number
            },
            resetLocation() {
                this.request.state_id = 0;
                this.request.city_id = 0;
            },
            getStatesByCountryCode() {
                this.cities = [];

                let url = this.getRouteWithId(this.routeStatesByCountryCode, this.request.country_code);

                axios.get(url).then((response) => {
                    this.states = response.data;
                    this.getCityByStateId();
                }).catch((error) => {
                    console.log('error country.');
                });
            },
            getCityByStateId() {
                let url = this.getRouteWithId(this.routeCitiesByStateId, this.request.state_id);

                axios.get(url).then((response) => {
                    this.cities = response.data;
                }).catch((error) => {
                    console.log('error state.');
                });
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

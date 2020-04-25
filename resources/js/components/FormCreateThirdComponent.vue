<template>
    <div id="create-third">
        <form v-on:submit.prevent="sendRequest">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label>Tipo de registro*</label>
                    <b-form-select
                        v-model="request.type"
                        :options="options.type"
                        size="sm"
                        v-bind:class="{'is-invalid': validate('type')}"
                    ></b-form-select>
                    <small class="form-text text-danger"
                           v-if="validate('type')">{{errors.type[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Numero de documento*</label>
                    <b-form-input type="text"
                                  class="form-control "
                                  v-bind:class="{'is-invalid': validate('identity_number') }"
                                  size="sm"
                                  v-model="request.identity_number"
                                  placeholder="Documento...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('identity_number')">{{errors.identity_number[0]}}</small>
                </div>
                <div class="form-group col-md-1">
                    <label>Doc*</label>
                    <b-form-select
                        v-model="request.identity_type"
                        :options="options.identity_type"
                        size="sm"
                        v-bind:class="{'is-invalid': validate('identity_type')}"
                    ></b-form-select>
                    <small class="form-text text-danger"
                           v-if="validate('identity_type')">{{errors.identity_type[0]}}</small>
                </div>
                <div class="form-group col-md-2">
                    <label>Tipo de persona*</label>
                    <b-form-select
                        v-model="request.type_person"
                        :options="options.type_person"
                        size="sm"
                        v-bind:class="{'is-invalid': validate('type_person')}"
                    ></b-form-select>
                    <small class="form-text text-danger"
                           v-if="validate('type_person')">{{errors.type_person[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Nombre completo*</label>
                    <b-form-input type="text"
                                  class="form-control "
                                  v-bind:class="{'is-invalid': validate('name') }"
                                  size="sm"
                                  v-model="request.name"
                                  placeholder="Nombre completo...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('name')">{{errors.name[0]}}</small>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Dirección*</label>
                    <b-form-input type="text"
                                  class="form-control"
                                  v-bind:class="{'is-invalid': validate('address')}"
                                  size="sm"
                                  v-model="request.address"
                                  placeholder="Dirección...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('address')">{{errors.address[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Correo*</label>
                    <b-form-input type="email"
                                  class="form-control"
                                  v-bind:class="{'is-invalid': validate('email')}"
                                  v-model="request.email"
                                  size="sm"
                                  placeholder="Correo...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('email')">{{errors.email[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Teléfono*</label>
                    <b-form-input type="tel"
                                  class="form-control"
                                  v-bind:class="{'is-invalid': validate('phone_number')}"
                                  v-model="request.phone_number"
                                  size="sm"
                                  placeholder="Teléfono...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('phone_number')">{{errors.phone_number[0]}}</small>
                </div>
                <div class="form-group col-md-1">
                    <label for="phone_extension">Ext:</label>
                    <b-form-input type="tel"
                                  class="form-control"
                                  v-bind:class="{ 'is-invalid': validate('phone_extension') }"
                                  v-model="request.phone_extension"
                                  size="sm"
                                  placeholder="Ext">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('phone_extension')">{{errors.phone_extension[0]}}</small>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>País*</label>
                    <select class="form-control"
                            v-bind:class="{'is-invalid': validate('country_code')}"
                            v-model="request.country_code"
                            size="sm"
                            @change="getStatesByCountryCode">
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
                            @change="getCityByStateId">
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
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label class="label-sm">Descripción</label>
                    <textarea id="description"
                              class="form-control"
                              v-bind:class="{ 'is-invalid': validate('description') }"
                              size="sm"
                              v-model="request.description"></textarea>
                    <small class="form-text text-danger"
                           v-if="validate('description')">{{errors.description[0]}}</small>
                </div>
            </div>
            <button class="btn btn-success">Crear</button>
        </form>
        <button class="btn btn-danger ml-1">Cancelar</button>
        <button class="btn btn-info" @click="clearRequest">Limpiar</button>

    </div>
</template>

<script>
    export default {
        name: "FormCreateThirdComponent",
        props: {
            routeStoreThird: String,
            routeAllCountries: String,
            routeStatesByCountryCode: String,
            routeCitiesByStateId: String,
            countryCodeSelectedDefault: String,
        },
        components: {},
        data() {
            return {
                countries: [],
                states: [],
                cities: [],
                options: {
                    type: [
                        {value: 'client', text: 'Cliente'},
                        {value: 'provider', text: 'Proveedor'},
                        {value: 'other', text: 'Otros'}
                    ],
                    identity_type: [
                        {value: 'CC', text: 'CC'},
                        {value: 'NIT', text: 'NIT'},
                    ],
                    type_person: [
                        {value: 'natural', text: 'Natural'},
                        {value: 'juridical', text: 'Juridica'},
                    ]
                },
                request: {
                    identity_type: 'CC',
                    identity_number: '',
                    type_person: 'natural',
                    type: 'client',
                    name: '',
                    address: '',
                    country_code: 'CO',
                    state_id: 0,
                    city_id: 0,
                    phone_number: '',
                    phone_extension: '',
                    email: '',
                    description: '',
                },
                errors: {}
            }
        },
        mounted() {
            this.request.country_code = this.countryCodeSelectedDefault;
            axios.get(this.routeAllCountries)
                .then((response) => {
                    this.countries = response.data;
                    this.getStatesByCountryCode()
                })
                .catch((error) => {
                    alert('handle server error from here');
                });
        },
        methods: {
            getStatesByCountryCode() {
                this.request.city_id = 0;
                this.cities = [];

                let url = this.getRouteWithId(this.routeStatesByCountryCode, this.request.country_code);

                axios.get(url)
                    .then((response) => {
                        this.states = response.data;
                    })
                    .catch((error) => {
                        alert('handle server error from here');
                    });
            },
            getCityByStateId() {
                let url = this.getRouteWithId(this.routeCitiesByStateId, this.request.state_id);

                axios.get(url)
                    .then((response) => {
                        this.cities = response.data;
                    })
                    .catch((error) => {
                        alert('handle server error from here');
                    });
            },
            getRouteWithId: function (route, roleId) {
                return route.replace('__ID__', roleId);
            },
            sendRequest() {
                axios.post(this.routeStoreThird, this.request)
                    .then((response) => {
                        this.cities = response.data;
                    })
                    .catch((error) => {

                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                            console.log(this.errors)
                        } else {
                            alert('handle server error from here');
                        }
                    });
            },
            validate(input) {
                return typeof this.errors[input] != 'undefined';
            },
            clearRequest() {
                this.request = {
                    identity_type: 'CC',
                    identity_number: '',
                    type_person: 'natural',
                    type: 'client',
                    name: '',
                    address: '',
                    country_code: 'CO',
                    state_id: 0,
                    city_id: 0,
                    phone_number: '',
                    phone_extension: '',
                    email: '',
                    description: '',
                }
                this.errors = {}
            }
        },
    }
</script>
<style>
    #create-third label {
        font-size: 12px;
    }

    #create-third .btn-success {
        float: left;
    }
</style>

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
                <div class="form-group col-md-4">
                    <label>Numero de documento* (Sin dígito de verificación)</label>
                    <b-form-input type="number"
                                  class="form-control "
                                  v-bind:class="{'is-invalid': validate('identity_number') }"
                                  size="sm"
                                  v-model="request.identity_number"
                                  placeholder="Documento...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('identity_number')">{{errors.identity_number[0]}}</small>
                </div>
            </div>
            <div class="form-row">

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
                <div class="form-group col-md-5">
                    <label>Nombre*</label>
                    <b-form-input type="text"
                                  class="form-control "
                                  v-bind:class="{'is-invalid': validate('name') }"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();"
                                  size="sm"
                                  v-model="request.name"
                                  placeholder="Nombre...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('name')">{{errors.name[0]}}</small>
                </div>
                <div class="form-group col-md-5" v-if="request.type_person == 'natural'">
                    <label>Apellido*</label>
                    <b-form-input type="text"
                                  class="form-control "
                                  v-bind:class="{'is-invalid': validate('last_name') }"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();"
                                  size="sm"
                                  v-model="request.last_name"
                                  placeholder="Apelldio...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('last_name')">{{errors.last_name[0]}}</small>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Dirección*</label>
                    <b-form-input type="text"
                                  class="form-control"
                                  v-bind:class="{'is-invalid': validate('address')}"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();"
                                  size="sm"
                                  v-model="request.address"
                                  placeholder="Dirección...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('address')">{{errors.address[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>Correo*</label>
                    <b-form-input type="text"
                                  class="form-control"
                                  v-bind:class="{'is-invalid': validate('email')}"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();"
                                  v-model="request.email"
                                  size="sm"
                                  placeholder="Correo...">
                    </b-form-input>
                    <small class="form-text text-danger"
                           v-if="validate('email')">{{errors.email[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label>Teléfono*</label>
                    <b-form-input type="number" min="1"
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
                    <label>Ext:</label>
                    <b-form-input type="text"
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
                    <label class="label-sm">Observaciones o notas</label>
                    <textarea id="description"
                              class="form-control"
                              v-bind:class="{ 'is-invalid': validate('description') }"
                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                              size="sm"
                              v-model="request.description"></textarea>
                    <small class="form-text text-danger"
                           v-if="validate('description')">{{errors.description[0]}}</small>
                </div>
            </div>
            <button class="btn btn-success btn-sm" v-bind:disabled="isDisabled">Crear</button>
        </form>
        <button class="btn btn-danger ml-1 btn-sm" @click="back">Cancelar</button>
        <button class="btn btn-info btn-sm" @click="clearRequest">Limpiar</button>

    </div>
</template>

<script>
    export default {
        name: "FormCreateThirdComponent",
        props: {
            routeStoreThird: String,
            routeIndexThird: String,
            routeAllCountries: String,
            routeStatesByCountryCode: String,
            routeCitiesByStateId: String,
            countryCodeSelectedDefault: String,
        },
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
                    last_name: '',
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
        computed: {
            isDisabled() {
                return (this.request.identity_type === '' || typeof this.request.identity_type == 'undefined')
                || (this.request.identity_number === '' || typeof this.request.identity_number == 'undefined')
                || (this.request.type_person === '' || typeof this.request.type_person == 'undefined')
                || (this.request.type === '' || typeof this.request.type == 'undefined')
                || (this.request.name === '' || typeof this.request.name == 'undefined')
                || (this.request.address === '' || typeof this.request.address == 'undefined')
                || (this.request.country_code === '' || typeof this.request.country_code == 'undefined')
                || (this.request.state_id == '0' || typeof this.request.state_id == 'undefined')
                || (this.request.city_id == '0' || typeof this.request.city_id == 'undefined')
                || (this.request.phone_number === '' || typeof this.request.phone_number == 'undefined')
                || (this.request.email === '' || typeof this.request.email == 'undefined')
            }
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
                        console.log('error country.');
                    });
            },
            getCityByStateId() {
                let url = this.getRouteWithId(this.routeCitiesByStateId, this.request.state_id);

                axios.get(url)
                    .then((response) => {
                        this.cities = response.data;
                    })
                    .catch((error) => {
                        console.log('error state.');
                    });
            },
            getRouteWithId: function (route, roleId) {
                return route.replace('__ID__', roleId);
            },
            sendRequest() {
                axios.post(this.routeStoreThird, this.request)
                    .then((response) => {
                        window.location.href = this.routeIndexThird;
                    })
                    .catch((error) => {
                        console.log(error.response.data.message)
                        if (error.response.status == 422) {
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
            },
            back() {
                window.location.href = this.routeIndexThird
            },
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

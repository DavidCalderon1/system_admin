<template>
    <div id="warehouses-component">

        <div class="accordion" id="accordionExample">
            <div class="card" id="ancla">
                <div class="card-header" id="headingOne" v-if="userCanCreate==1">
                    <h5 class="mb-0" >
                        <button class="btn btn-link"
                                type="button"
                                data-toggle="collapse"
                                data-target="#collapseOne"
                                aria-expanded="true"
                                aria-controls="collapseOne"
                        >
                            {{getActionText}}
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <form v-on:submit.prevent>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Nombre*</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-bind:class="{'is-invalid': validate('name') }"
                                           v-model="request.name"
                                           placeholder="Nombre...">
                                    <small class="form-text text-danger"
                                           v-if="validate('name')">{{errors.name[0]}}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Dirección*</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-bind:class="{'is-invalid': validate('address')}"
                                           v-model="request.address"
                                           placeholder="Dirección...">
                                    <small class="form-text text-danger"
                                           v-if="validate('address')">{{errors.address[0]}}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Teléfono*</label>
                                    <input type="number" min="1" class="form-control form-control-sm"
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
                                            id="select-country"
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
                                            id="select-state"
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
                                            id="select-city"
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
                            <button class="btn btn-success btn-sm" id="create" v-bind:disabled="!canCreate"  @click="getAction" >Crear</button>
                            <button class="btn btn-info btn-sm" @click="clean" v-if="action == 'create'">
                                Limpiar
                            </button>
                            <button class="btn btn-danger btn-sm" @click="cancelStore">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <data-table
            :columns="columns"
            :url="listRoute"
            :translate="{ nextButton: 'Siguiente', previousButton: 'Atrás', placeholderSearch: 'Buscar...'}"
            ref="dataTable">
            <div slot="filters" slot-scope="{ tableData, perPage }">
                <div class="row mb-2">
                    <div class="col-md-6" id="content-filter">
                        <select class="form-control" v-model="tableData.length" id="select-length-data-table">
                            <option :key="page" v-for="page in perPage">{{ page }}</option>
                        </select>
                        <input
                            name="name"
                            class="form-control"
                            v-model="tableData.search"
                            placeholder="Buscar"
                            id="search"
                        >
                    </div>
                </div>
            </div>
        </data-table>
    </div>
</template>

<script>
    import ButtonDataTable from "./ButtonDataTable";

    export default {
        name: "WarehousesComponent",
        props: {
            listRoute: String,
            createRoute: String,
            updateRoute: String,
            deleteRoute: String,

            allCountriesRoute: String,
            statesByCountryCodeRoute: String,
            citiesByStateIdRoute: String,
            countryCodeSelectedDefault: String,

            userCanCreate: String,
            userCanUpdate: String,
            userCanDelete: String,
        },
        data() {
            return {
                columns: [
                    {
                        label: 'Nombre',
                        name: 'name',
                        orderable: true,
                    },
                    {
                        label: 'Dirección',
                        name: 'address',
                        orderable: true,
                    },
                    {
                        label: 'País',
                        name: 'country.name',
                        orderable: false,
                    },
                    {
                        label: 'Departamento',
                        name: 'state.name',
                        orderable: false,
                    },
                    {
                        label: 'Ciudad',
                        name: 'city.name',
                        orderable: false,
                    },
                    {
                        label: '',
                        name: '',
                        orderable: false,
                        classes: {
                            view: {
                                userCanView: 0
                            },
                            edit: {
                                userCanEdit: this.userCanUpdate,
                                ico: {
                                    'fa fa-pencil': true
                                },
                                'btn': true,
                                'btn-primary': true,
                                'btn-sm': true,
                            },
                            cancel: {
                                userCanCancel: this.userCanDelete,
                                ico: {
                                    'fa fa-trash': true
                                },
                                'btn': true,
                                'btn-danger': true,
                                'btn-sm': true,
                            }

                        },
                        event: "click",
                        handler: this.view,
                        component: ButtonDataTable,
                    },
                ],
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
                action: 'create'
            }
        },
        mounted() {
            this.request.country_code = this.countryCodeSelectedDefault;

            axios.get(this.allCountriesRoute).then((response) => {
                this.countries = response.data;
                this.getStatesByCountryCode()
            }).catch((error) => {
                console.log('handle server error from here');
            });
        },
        computed: {
            canCreate() {
                return this.request.name !== ''
                    && this.request.address !== ''
                    && this.request.country_code !== ''
                    && this.request.state_id > 0
                    && this.request.city_id > 0
            },
            getAction() {
                return this.action === 'update' ? this.update : this.create;
            },
            getActionText() {
                return this.action === 'update' ? 'Editar' : 'Crear';
            }
        },
        methods: {
            view(data, action) {
                switch (action) {
                    case 'edit':
                        this.edit(data);
                        break;
                    case 'cancel':
                        this.delete(data.id);
                        break;
                }
            },
            create() {
                this.errors = {};
                axios.post(this.createRoute, this.request)
                    .then(resp => {
                        this.clean();
                        $('#collapseOne').collapse('hide')
                        this.$alertify.success(resp.data.message);
                        this.$refs.dataTable.getData()
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }

                        this.$alertify.error(error.response.data.message);
                        this.$refs.dataTable.getData()

                    })
            },
            edit(data) {
                this.action = 'update';
                $('#create').text('Actualizar')
                $('#collapseOne').collapse('show')
                this.request.id = data.id
                this.request.name = data.name
                this.request.address = data.address
                this.request.country_code = data.country.code

                let select = document.getElementById('select-country')
                select.value = data.country.code
                select.dispatchEvent(new Event('change'))

                this.request.state_id = data.state.id
                this.request.city_id = data.city.id
                this.request.phone_number = data.phone_number

                let codigo = "#ancla";
                $("html,body").animate({scrollTop: $(codigo).offset().top});

            },
            update() {
                this.errors = {};
                axios.post(this.updateRoute, this.request)
                    .then(resp => {
                        this.clean();
                        $('#collapseOne').collapse('hide')
                        this.$alertify.success(resp.data.message);
                        this.$refs.dataTable.getData()
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                        this.$alertify.error(error.response.data.message);
                        this.$refs.dataTable.getData()
                    })
            },
            delete(id) {
                this.$alertify.confirm(
                    'Estas seguro que deseas eliminar la Categoría?',
                    () => {
                        let url = this.getRouteWithId(this.deleteRoute, id);
                        axios.delete(url)
                            .then(resp => {
                                this.$alertify.success(resp.data.message);
                                this.$refs.dataTable.getData()
                            })
                            .catch(error => {
                                this.$alertify.error(error.response.data.message);
                                this.$refs.dataTable.getData()

                            })
                    },
                );
            },
            clean() {
                this.action = 'create';
                $('#create').text('Crear')
                this.request.id = 0;
                this.request.name = '';
                this.request.address = '';
                this.request.country_code = 'CO';
                this.request.state_id = '';
                this.request.city_id = '';
                this.request.phone_number = '';
            },
            cancelStore(){
                this.clean();
                $('#collapseOne').collapse('hide')
            },
            validate(input) {
                return typeof this.errors[input] != 'undefined';
            },
            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
            },

            getStatesByCountryCode() {
                this.cities = [];

                let url = this.getRouteWithId(this.statesByCountryCodeRoute, this.request.country_code);

                axios.get(url).then((response) => {
                    this.states = response.data;
                    this.getCityByStateId();
                }).catch((error) => {
                    console.log('error country.');
                });
            },
            getCityByStateId() {
                let url = this.getRouteWithId(this.citiesByStateIdRoute, this.request.state_id);

                axios.get(url).then((response) => {
                    this.cities = response.data;
                }).catch((error) => {
                    console.log('error state.');
                });
            },
            resetLocation() {
                this.request.state_id = 0;
                this.request.city_id = 0;
            },
        },
    }
</script>

<style>
    td.laravel-vue-datatable-td {
        border: 2px solid #dee2e6 !important;
        font-size: 12px;
        text-align: center;
        padding: 5px;
    }

    th.laravel-vue-datatable-th {
        text-align: center;
        border: 1px solid #dee2e6 !important;
    }

    #select-length-data-table {
        width: 75px;
        margin-right: 10px;
    }

    div#content-filter {
        display: flex;
    }

</style>

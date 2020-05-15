<template>
    <div>
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
                    <div class="col-md-6" v-if="userCanCreate == 1">
                        <a v-bind:href="createRoute" class="btn btn-success btn-sm pull-right">
                            <i class="fa fa-plus">Nuevo</i>
                        </a>
                    </div>
                </div>
            </div>
        </data-table>
        <div class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             id="thirdInfoModal"
             aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles de {{thirdViewData.type}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>{{thirdViewData.type}}</td>
                                </tr>
                                <tr>
                                    <th>{{thirdViewData.identity_type}}:</th>
                                    <td>{{thirdViewData.identity_number}}</td>
                                </tr>
                                <tr>
                                    <th>Persona:</th>
                                    <td>{{thirdViewData.type_person}}</td>
                                </tr>
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{thirdViewData.name}}</td>
                                </tr>
                                <tr v-if="thirdViewData.last_name !==''">
                                    <th>Apellido:</th>
                                    <td>{{thirdViewData.last_name}}</td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td>{{thirdViewData.phone_number}}</td>
                                </tr>
                                <tr>
                                    <th>Correo:</th>
                                    <td>{{thirdViewData.email}}</td>
                                </tr>
                                <tr>
                                    <th>Dirección:</th>
                                    <td>{{thirdViewData.address}}</td>
                                </tr>
                                <tr>
                                    <th>País:</th>
                                    <td>{{thirdViewData.country}}</td>
                                </tr>
                                <tr>
                                    <th>Departamento:</th>
                                    <td>{{thirdViewData.state}}</td>
                                </tr>
                                <tr>
                                    <th>Ciudad:</th>
                                    <td>{{thirdViewData.city}}</td>
                                </tr>
                                <tr v-if="thirdViewData.description !==''">
                                    <td colspan="2">{{thirdViewData.description}}</td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonDataTable from "./ButtonDataTable";

    export default {
        name: 'thirds-component',
        props: {
            listRoute: {
                type: String,
                required: true
            },
            createRoute: {
                type: String,
                required: true
            },
            editRoute: {
                type: String,
                required: true
            },
            viewRoute: {
                type: String,
                required: true
            },
            destroyRoute: {
                type: String,
                required: true
            },
            userCanCreate: {
                type: String,
                required: true
            },
            userCanView: {
                type: String,
                required: true
            },
            userCanEdit: {
                type: String,
                required: true
            },
            userCanDelete: {
                type: String,
                required: true
            },
        },
        data() {
            return {
                columns: [
                    {
                        label: 'Tipo',
                        name: 'type_trans',
                        orderable: true,
                    },
                    {
                        label: 'Nombre',
                        name: 'name',
                        orderable: true,
                    },
                    {
                        label: 'Apellido',
                        name: 'last_name',
                        orderable: true,
                    },
                    {
                        label: 'Tipo doc.',
                        name: 'identity_type',
                        orderable: true,
                    },
                    {
                        label: 'Numero doc',
                        name: 'identity_number',
                        orderable: true,
                    },
                    {
                        label: 'Correo',
                        name: 'email',
                        orderable: true,
                    },
                    {
                        label: '',
                        name: '',
                        orderable: false,
                        classes: {
                            view: {
                                userCanView: this.userCanView,
                                ico: {
                                    'fa fa-eye': true
                                },
                                'btn': true,
                                'btn-success': true,
                                'btn-sm': true,
                            },
                            edit: {
                                userCanEdit: this.userCanEdit,
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
                thirdViewData: {
                    type: '',
                    identity_type: '',
                    identity_number: '',
                    type_person: '',
                    name: '',
                    last_name: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    country: '',
                    state: '',
                    city: '',
                    description: ''
                },
            }
        },
        methods: {
            view(data, action) {
                switch (action) {
                    case 'view':
                        this.showInfoThird(data.id)
                        break;
                    case 'edit':
                        window.location.href = this.getRouteWithId(this.editRoute, data.id);
                        break;
                    case 'cancel':
                        this.cancel(data.id);
                        break;
                }
            },
            showInfoThird(id) {
                axios.get(this.getRouteWithId(this.viewRoute, id))
                    .then(resp => {

                        let ext = (resp.data.data.phone_extension !== '' && typeof resp.data.data.phone_extension !== 'undefined')
                            ? ' Ext: ' + resp.data.data.phone_extension
                            : '';

                        this.thirdViewData.type = resp.data.data.type_trans
                        this.thirdViewData.identity_type = resp.data.data.identity_type
                        this.thirdViewData.identity_number = resp.data.data.identity_number
                        this.thirdViewData.type_person = resp.data.data.type_person_trans
                        this.thirdViewData.name = resp.data.data.name
                        this.thirdViewData.last_name = resp.data.data.last_name
                        this.thirdViewData.phone_number = resp.data.data.phone_number + ext
                        this.thirdViewData.email = resp.data.data.email
                        this.thirdViewData.address = resp.data.data.address
                        this.thirdViewData.country = resp.data.data.country.name
                        this.thirdViewData.state = resp.data.data.state.name
                        this.thirdViewData.city = resp.data.data.city.name
                        this.thirdViewData.description = resp.data.data.description

                        $('#thirdInfoModal').modal('show')
                    })
                    .catch(error => {
                        this.$alertify.error('Ha ocurrido un error obteniendo el producto');
                    })
            },
            cancel(id) {
                this.$alertify.confirm(
                    'Estas seguro que deseas eliminar el tercero?',
                    () => {
                        let url = this.getRouteWithId(this.destroyRoute, id);
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
            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
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

    #search {
        width: 50%;
    }

    .modal-body table {
        max-width: 100%;
    }
</style>

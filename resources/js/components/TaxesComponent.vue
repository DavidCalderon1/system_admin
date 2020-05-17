<template>
    <div id="taxes-component">

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
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control form-control-sm" id="name" placeholder="Nombre"
                                               v-bind:class="{'is-invalid': validate('name')}"
                                               v-model="request.name">
                                        <small class="form-text text-danger"
                                               v-if="validate('name')">{{errors.name[0]}}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="type">Tipo</label>
                                        <select id="type" class="form-control form-control-sm" v-model="request.type"
                                                v-bind:class="{'is-invalid': validate('type')}">
                                            <option selected></option>
                                            <option v-for="taxType in taxesType">{{taxType}}</option>
                                        </select>
                                        <small class="form-text text-danger"
                                               v-if="validate('type')">{{errors.type[0]}}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="percentage">% Tarifa</label>
                                        <input type="number" min="0" class="form-control form-control-sm" id="percentage"
                                               placeholder="%"
                                               v-bind:class="{'is-invalid': validate('percentage')}"
                                               v-model="request.percentage">
                                        <small class="form-text text-danger"
                                               v-if="validate('percentage')">{{errors.percentage[0]}}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-success" id="create" v-bind:disabled="!canCreate"
                                                @click="getAction">
                                            Crear
                                        </button>
                                        <button class="btn btn-sm btn-info" id="clear" @click="clean" v-if="action == 'create'">
                                            Limpiar
                                        </button>
                                        <button class="btn btn-danger btn-sm" id="cancel" @click="cancelStore">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
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
        name: "TaxesComponent",
        props: {
            listRoute: String,
            createRoute: String,
            updateRoute: String,
            deleteRoute: String,
            userCanCreate: String,
            userCanEdit: String,
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
                        label: 'Tipo',
                        name: 'type',
                        orderable: true,
                    },
                    {
                        label: 'Tarifa',
                        name: 'percentage',
                        orderable: true,
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
                taxesType: ['IVA', 'Retefuente', 'ReteICA', 'ReteIVA', 'Impoconsumo'],
                request: {
                    id: 0,
                    name: '',
                    type: '',
                    percentage: '',
                },
                errors: {},
                action: 'create'
            }
        },
        computed: {
            canCreate() {
                return this.request.name !== ''
                    && this.request.type !== ''
                    && this.request.percentage !== ''
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

                this.request.id = data.id
                this.request.name = data.name
                this.request.type = data.type
                this.request.percentage = data.percentage
                $('#collapseOne').collapse('show')

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
                    'Estas seguro que deseas eliminar el impuesto?',
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
                this.request.type = '';
                this.request.percentage = '';
                this.errors={};
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

    #taxes-component button#create {
        margin-top: 30px;
    }

    #taxes-component button#clear {
        margin-top: 30px;
    }

    #taxes-component button#cancel {
        margin-top: 30px;
    }
</style>

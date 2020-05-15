<template>
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
            }
        },
        methods: {
            view(data, action) {
                switch (action) {
                    case 'view':
                        window.location.href = this.getRouteWithId(this.viewRoute, data.id);
                        break;
                    case 'edit':
                        window.location.href = this.getRouteWithId(this.editRoute, data.id);
                        break;
                    case 'cancel':
                        this.cancel(data.id);
                        break;
                }
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
</style>

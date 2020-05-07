<template>
    <div class="container" id="grid">
        <form v-on:submit.prevent>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control form-control-sm" v-model="filters.name"
                               placeholder="Nombre">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Dirección</label>
                    <input type="text" class="form-control form-control-sm" v-model="filters.address"
                           placeholder="Dirrección">
                </div>
                <div class="col-md-3">
                    <label>Teléfono</label>
                    <input type="text" class="form-control form-control-sm" v-model="filters.phone_number"
                           placeholder="Teléfono">
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-primary btn-sm mt-2" @click="getData">
                        <i class="fa fa-search">Buscar</i>
                    </button>
                    <a v-bind:href="createRoute" class="btn btn-success btn-sm pull-right mt-2"
                       v-if="userCanCreate == 1">
                        <i class="fa fa-plus">Nuevo</i>
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">

            <table class="table table-bordered table-sm" ref="table">
                <thead class="thead-light">
                <tr>
                    <th v-for="field in fields">{{field.label}}</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(body) in data">
                    <td> {{body.name}}</td>
                    <td> {{body.address}}</td>
                    <td> {{body.phone_number}}</td>
                    <td>
                        <a v-if="userCanUpdate == 1" v-bind:href="getRouteWithId(editRoute, body.id)"
                           class="btn btn-warning btn-sm">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a v-if="userCanDelete == 1" href="javascript:;"
                           v-on:click="destroy(body.id)"
                           class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination-component :pagination="pagination" @paginate="getData()" :offset="1">
            </pagination-component>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'warehouses-component',
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
            destroyRoute: {
                type: String,
                required: true
            },
            userCanCreate: {
                type: String,
                required: true
            },
            userCanUpdate: {
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
                fields: [
                    {key: 'name', label: 'Nombre'},
                    {key: 'address', label: 'Dirección'},
                    {key: 'phone_number', label: 'Teléfono'},
                ],
                data: {},
                pagination: {
                    current_page: 1
                },
                filters: {
                    name: '',
                    address: '',
                    phone_number: '',
                },
            }
        },
        mounted() {
            this.getData()
        },
        methods: {
            getUrl() {
                let pathFilter = `&name=${this.filters.name}&address=${this.filters.address}&phone_number=${this.filters.phone_number}`;
                let url = `${this.listRoute}?page=${this.pagination.current_page}` + pathFilter
                return url;
            },
            getData() {
                axios.get(this.getUrl())
                    .then((response) => {
                        this.data = response.data.data;
                        this.pagination = response.data.pagination;
                        this.$forceUpdate();
                    })
                    .catch((error) => {
                        if (error.response.status === 404) {
                            if (this.pagination.current_page > 1) {
                                this.pagination.current_page = 1;
                                this.getData();
                                return false;
                            }
                            this.data = {};
                        } else {
                            alert('handle server error from here');
                        }
                    });
            },
            destroy(id) {
                this.$alertify.confirm(
                    'Estas seguro que deseas borrar el registro?',
                    () => {
                        let url = this.getRouteWithId(this.destroyRoute, id);
                        axios.delete(url)
                            .then(resp => {
                                this.$alertify.success(resp.data.message);
                                this.getData();
                            })
                            .catch(error => {
                                this.$alertify.error(error.response.data.message);
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
    #grid table > tbody {
        font-size: 12px;
    }
</style>

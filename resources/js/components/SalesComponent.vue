<template>
    <div class="container" id="grid-sales">
        <form v-on:submit.prevent>
            <div class="form-group row">
                <div class="col-md-6">
                    <div class="input-group">
                        <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filter"
                                @change="getData">
                            <option value="consecutive">Consecutivo</option>
                            <option value="client_name">Cliente</option>
                            <option value="status">Estado</option>
                        </select>
                        <input type="text" id="texto" name="texto" class="form-control" v-model="search"
                               v-on:keyup.enter="getData"
                               placeholder="Buscar">
                        <button type="button" class="btn btn-primary" @click="getData"><i
                            class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6" v-if="userCanCreate == 1">
                    <a v-bind:href="createRoute" class="btn btn-success btn-md pull-right">
                        <i class="fa fa-plus">Nuevo</i>
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">

            <table class="table table-striped table-hover table-bordered table-sm" ref="table">
                <thead class="thead-light">
                <tr class="text-center">
                    <th v-for="field in fields">{{field.label}}</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(body) in data" class="text-center">
                    <td>{{body.prefix}}-{{body.consecutive}}</td>
                    <td> {{body.client_name}}</td>
                    <td> {{body.client_contact}}</td>
                    <td> ${{formatPrice(body.totals.total)}}</td>
                    <td> {{body.date}}</td>
                    <td> {{body.status}}</td>
                    <td>
                        <a v-if="userCanView == 1" v-bind:href="getRouteWithId(viewRoute, body.id)" title="Ver"
                           class="btn btn-success btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a v-if="userCanCancel == 1 && body.status !== 'Anulada'" href="javascript:;" title="Anular"
                           v-on:click="cancel(body.id)"
                           class="btn btn-danger btn-sm">
                            <i class="fas fa-ban"></i>
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
        name: 'products-component',
        props: {
            listRoute: {
                type: String,
                required: true
            },
            createRoute: {
                type: String,
                required: true
            },
            viewRoute: {
                type: String,
                required: true
            },
            cancelRoute: {
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
            userCanCancel: {
                type: String,
                required: true
            },
        },
        data() {
            return {
                fields: [
                    {key: 'invoice_number', label: 'Consecutivo'},
                    {key: 'client_name', label: 'Cliente'},
                    {key: 'client_contact', label: 'Contacto'},
                    {key: 'total', label: 'Total'},
                    {key: 'date', label: 'Fecha'},
                    {key: 'status', label: 'Estado'},
                ],
                data: {},
                pagination: {
                    current_page: 1
                },
                filter: 'consecutive',
                search: ''
            }
        },
        mounted() {
            this.getData()
        },
        methods: {
            getUrl() {
                let pathFilter = `&${this.filter}=${this.search}`;
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
            cancel(roleId) {
                this.$alertify.confirm(
                    'Estas seguro que deseas Anular la factura de venta?',
                    () => {
                        let url = this.getRouteWithId(this.cancelRoute, roleId);
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
            formatPrice(value) {
                let val = (value / 1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },

        },
    }
</script>
<style>
    #grid-sales table > tbody {
        font-size: 12px;
    }
</style>

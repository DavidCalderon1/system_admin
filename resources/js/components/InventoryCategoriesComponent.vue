<template>
    <div class="container" id="grid">
        <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-control col-md-3" name="opcion" v-model="filter" @change="getData">
                        <option v-for="field in fields"
                                v-bind:value="field.key">{{field.label}}
                        </option>
                    </select>
                    <input type="text" class="form-control" v-model="search" ref="search"
                           v-on:keyup.enter="getData"
                           @keypress="getData"
                           @keyup.delete="getData"
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
                    <td> {{body.description}}</td>
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
        name: 'grid-component',
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
                    {key: 'description', label: 'Descripción'}
                ],
                data: {},
                pagination: {
                    current_page: 1
                },
                filter: 'name',
                search: ''
            }
        },
        mounted() {
            this.$refs.search.focus()

            console.log(this.userCanCreate)
            this.getData()
        },
        methods: {
            getData() {
                let pathFilter = '';

                if (this.search !== '') {
                    pathFilter = `&${this.filter}=${this.search}`
                }

                let url = `${this.listRoute}?page=${this.pagination.current_page}` + pathFilter

                axios.get(url)
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

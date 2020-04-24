<template>
    <div class="container">
        <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filter" @change="getData">
                        <option value="name">Nombre</option>
                        <option value="slug">Alias</option>
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
                <a v-bind:href="roleCreateRoute" class="btn btn-success btn-md pull-right">
                    <i class="fa fa-plus">Nuevo</i>
                </a>
            </div>
        </div>
        <div class="table-responsive">

            <table class="table table-bordered">
                <tr>
                    <th>Nombre</th>
                    <th>Alias</th>
                    <th>Acciones</th>
                </tr>
                <tr v-for="roles in data">
                    <td>{{ roles.name }}</td>
                    <td>{{ roles.slug }}</td>
                    <td>
                        <a v-if="userCanUpdate == 1" v-bind:href="getRouteWithId(roleEditRoute, roles.id)"
                           class="btn btn-warning btn-md">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a v-if="userCanDelete == 1" href="javascript:;"
                           v-on:click="destroy(roles.id)"
                           class="btn btn-danger btn-md">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <br>
                <pagination-component :pagination="pagination" @paginate="getData()" :offset="1">
                </pagination-component>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'grid-component',
        props: [
            'fields',
            'key',
            'listRoute',
            'createRoute',
            'editRoute',
            'destroyRoute',
            'userCanCreate',
            'userCanUpdate',
            'userCanDelete',
        ],
        mounted() {
            this.getData();
        },
        data() {
            return {
                fields: [
                    {key: 'name', label: 'Nombre'},
                    {key: 'slug', label: 'Alias'},
                    {key: 'actions', label: 'Acciones'}
                ],
                data: {},
                pagination: {},
                filter: 'name',
                search: ''
            }
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
                        this.data = response.data.data.roles;
                        this.pagination = response.data.data.pagination;
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
            destroy(roleId) {
                if (confirm("Estas seguro que deseas borrar el registro?")) {
                    let url = this.getRouteWithId(this.destroyRoute, roleId);
                    axios.delete(url)
                        .then(resp => {
                            alert(resp.data.message);
                            this.getData();
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                        })
                }
            },
            getRouteWithId: function (route, id) {
                return route.replace('__ID__', id);
            },
        },
    }
</script>

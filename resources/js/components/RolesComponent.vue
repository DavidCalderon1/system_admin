<template>
    <div class="container" id="roles-table">
        <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filter" @change="getRoles">
                        <option value="name">Nombre</option>
                        <option value="slug">Alias</option>
                    </select>
                    <input type="text" id="texto" name="texto" class="form-control" v-model="search"
                           v-on:keyup.enter="getRoles"
                           @keypress="getRoles"
                           @keyup.delete="getRoles"
                           placeholder="Buscar">
                    <button type="button" class="btn btn-primary" @click="getRoles"><i
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
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Alias</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="roles in data">
                    <td>{{ roles.name }}</td>
                    <td>{{ roles.slug }}</td>
                    <td>
                        <a v-if="userCanUpdate == 1 && roles.id != roleAdminId"
                           v-bind:href="getRouteWithId(roleEditRoute, roles.id)"
                           class="btn btn-warning btn-sm">
                            <i class="fa fa-pencil"></i>
                        </a>

                        <a v-if="userCanDelete == 1 && roles.id != roleAdminId" href="javascript:;"
                           v-on:click="destroy(roles.id)"
                           class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination-component :pagination="pagination" @paginate="getRoles()" :offset="1">
            </pagination-component>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'roles-component',
        props: [
            'roleListRoute',
            'roleCreateRoute',
            'roleEditRoute',
            'roleDestroyRoute',
            'userCanCreate',
            'userCanUpdate',
            'userCanDelete',
            'roleAdminId',
        ],
        mounted() {
            this.getRoles();
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
            getRoles() {
                let pathFilter = '';

                if (this.search !== '') {
                    pathFilter = `&${this.filter}=${this.search}`
                }

                let url = `${this.roleListRoute}?page=${this.pagination.current_page}` + pathFilter

                axios.get(url)
                    .then((response) => {
                        this.data = response.data.data.roles;
                        this.pagination = response.data.data.pagination;
                    })
                    .catch((error) => {
                        if (error.response.status === 404) {
                            if (this.pagination.current_page > 1) {
                                this.pagination.current_page = 1;
                                this.getRoles();
                                return false;
                            }
                            this.data = {};
                        } else {
                            alert('handle server error from here');
                        }
                    });
            },
            destroy(roleId) {

                this.$alertify.confirm(
                    'Estas seguro que deseas borrar el rol?',
                    () => {
                        let url = this.getRouteWithId(this.roleDestroyRoute, roleId);
                        axios.delete(url)
                            .then(resp => {
                                this.$alertify.success(resp.data.message);
                                this.getRoles();
                            })
                            .catch(error => {
                                this.$alertify.error(error.response.data.message);
                            })
                    },
                );
            },
            getRouteWithId: function (route, roleId) {
                return route.replace('__ID__', roleId);
            },
        },
    }
</script>
<style>
    #roles-table table > tbody {
        font-size: 14px;
    }
</style>

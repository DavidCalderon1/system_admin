<template>
    <div class="container" id="users-table">
        <div class="form-group row">
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filter" @change="getUsers">
                        <option value="name">Nombre</option>
                        <option value="email">Correo</option>
                    </select>
                    <input type="text" id="texto" name="texto" class="form-control" v-model="search"
                           v-on:keyup.enter="getUsers"
                           placeholder="Buscar">
                    <button type="button" class="btn btn-primary" @click="getUsers"><i
                        class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6" v-if="userCanCreate == 1">
                <a v-bind:href="userCreateRoute" class="btn btn-success btn-md pull-right">
                    <i class="fa fa-plus">Nuevo</i>
                </a>
            </div>
        </div>
        <div class="table-responsive">

            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in data">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td><label class="badge badge-success" v-for="role in user.roles"> {{ role.name }}</label>
                    </td>
                    <td>
                        <a v-if="userCanUpdate == 1" v-bind:href="getRouteWithUserId(userEditRoute, user.id)"
                           class="btn btn-warning btn-sm">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a v-if="userCanDelete == 1 && user.id != userAdminId" href="javascript:;"
                           v-on:click="destroyUser(user.id)"
                           class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination-component :pagination="pagination" @paginate="getUsers()" :offset="1">
            </pagination-component>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'users-component',
        props: [
            'userListRoute',
            'userCreateRoute',
            'userEditRoute',
            'userDestroyRoute',
            'userCanCreate',
            'userCanUpdate',
            'userCanDelete',
            'userAdminId',
        ],
        mounted() {
            this.getUsers();
        },
        data() {
            return {
                fields: [
                    {key: 'name', label: 'Nombre'},
                    {key: 'email', label: 'Correo'},
                    {key: 'roles', label: 'Roles'},
                    {key: 'actions', label: 'Acciones'}
                ],
                data: {},
                pagination: {},
                filter: 'name',
                search: ''
            }
        },
        methods: {
            getUsers() {
                let pathFilter = '';

                if (this.search !== '') {
                    pathFilter = `&${this.filter}=${this.search}`
                }

                let url = `${this.userListRoute}?page=${this.pagination.current_page}` + pathFilter

                axios.get(url)
                    .then((response) => {
                        this.data = response.data.data.users;
                        this.pagination = response.data.data.pagination;
                    })
                    .catch((error) => {
                        if (error.response.status === 404) {
                            if (this.pagination.current_page > 1) {
                                this.pagination.current_page = 1;
                                this.getUsers();
                                return false;
                            }
                            this.data = {};
                        } else {
                            alert('handle server error from here');
                        }
                    });
            },
            destroyUser(userId) {
                this.$alertify.confirm(
                    'Estas seguro que deseas borrar el usuario?',
                    () => {
                        let url = this.getRouteWithUserId(this.userDestroyRoute, userId);
                        axios.delete(url)
                            .then(resp => {
                                this.$alertify.success(resp.data.message);
                                this.getUsers();
                            })
                            .catch(error => {
                                this.$alertify.error(error.response.data.message);
                            })
                    },
                );
            },
            getRouteWithUserId: function (route, userId) {
                return route.replace('__userId__', userId);
            },
        },
    }
</script>
<style>
    #users-table table > tbody {
        font-size: 14px;
    }
</style>

<template>
    <div class="container">
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
            <div class="col-md-6">
                <a v-bind:href="userCreateRoute" class="btn btn-success btn-md">
                    <i class="fa fa-plus">Nuevo</i>
                </a>
            </div>
        </div>
        <div class="table-responsive">

            <table class="table table-bordered">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
                <tr v-for="user in data.users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td><label class="badge badge-success" v-for="role in user.roles"> {{ role.name }}</label>
                    </td>
                    <td>
                        <a v-bind:href="getRouteWithUserId(userEditRoute, user.id)" class="btn btn-warning btn-md">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a href="javascript:;" v-on:click="destroyUser(user.id)" class="btn btn-danger btn-md">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <br>
                <pagination-component :pagination="data" @paginate="getUsers()" :offset="1">
                </pagination-component>
            </table>
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
            'userCanList',
            'userCanCreate',
            'userCanUpdate',
            'userCanDelete',
        ],
        mounted() {
            console.log(this.userCanList);
            console.log(this.userCanCreate);
            console.log(this.userCanUpdate);
            console.log(this.userCanDelete);
            console.log(this.userEditRoute)
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
                dataFilter: {},
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

                let url = `${this.userListRoute}?page=${this.data.current_page}` + pathFilter

                axios.get(url)
                    .then((response) => {
                        this.data = response.data.data;
                    })
                    .catch((error) => {
                        if (error.response.status === 404) {
                            if (this.data.current_page > 1) {
                                this.data.current_page = 1;
                                this.getUsers();
                                return false;
                            }
                            this.data = {};
                        } else {
                            console.log('handle server error from here');
                        }
                    });
            },
            destroyUser(userId) {
                if (confirm("Estas seguro que deseas borrar el usuario?")) {
                    let url = this.getRouteWithUserId(this.userDestroyRoute, userId);
                    axios.delete(url)
                        .then(resp => {
                            alert(resp.data.message);
                            this.getUsers();
                        })
                        .catch(error => {
                            alert(error.data)
                        })
                }
            },
            getRouteWithUserId: function (route, userId) {
                return route.replace('__userId__', userId);
            },
        },
    }
</script>

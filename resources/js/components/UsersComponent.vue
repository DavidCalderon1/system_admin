<template>
    <div class="container">
        <div>
            <b-table striped hover :items="items"></b-table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'users-component',
        props: ['userListRoute'],
        mounted() {
            console.log('Component mounted.')
            axios.get(this.userListRoute)
                .then(res => {
                    this.items = res.data.data.users;
                }).catch(err => {
                console.log(err)
            })
        },
        data() {
            return {
                items: []
            }
        }
    }
</script>

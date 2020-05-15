<template>
    <img v-bind:src="data.image" width="50px" height="50px">
</template>

<script>
    export default {
        name: "ImageDataTableComponent",
        props:{
            data:{}
        }
    }
</script>

<style scoped>

</style>

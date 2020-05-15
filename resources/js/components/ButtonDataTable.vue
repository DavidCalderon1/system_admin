<template>
    <div>
        <button :class="classes.view" @click="click(data, 'view')" title="Ver" v-if="classes.view.userCanView==1">
        <span>
            <i :class="classes.view.ico" aria-hidden="true"></i>
        </span>
            {{ name }}
        </button>
        <button :class="classes.edit" @click="click(data, 'edit')" title="Editar"
                v-if="classes.edit.userCanEdit==1 && data.status !== 'Anulada'">
        <span>
            <i :class="classes.edit.ico" aria-hidden="true"></i>
        </span>
            {{ name }}
        </button>
        <button :class="classes.cancel" @click="click(data, 'cancel')"
                v-if="classes.cancel.userCanCancel==1 && data.status !== 'Anulada'">
        <span>
            <i :class="classes.cancel.ico" aria-hidden="true"></i>
        </span>
            {{ name }}
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            data: {},
            name: {},
            click: {
                type: Function,
                default: () => {
                }
            },
            classes: {
                type: Object,
                view: {
                    userCanView: String,
                    ico: {
                        type: Object,
                        default: () => ({
                            'fa fa-eye': true
                        }),
                    },
                    default: () => ({
                        'btn': true,
                        'btn-success': true,
                        'btn-sm': true,
                    }),
                },
                edit: {
                    type: Object,
                    ico: {
                        type: Object,
                        default: () => ({
                            'fa fa-pencil': true
                        }),
                    },
                    default: () => ({
                        'btn': true,
                        'btn-primary': true,
                        'btn-sm': true,
                    }),
                },
                cancel: {
                    userCanCancel: String,
                    type: Object,
                    ico: {
                        type: Object,
                        default: () => ({
                            'fas fa-ban': true
                        }),
                    },
                    default: () => ({
                        'btn': true,
                        'btn-danger': true,
                        'btn-sm': true,
                    }),
                },
            },
        }
    }
</script>

<style scoped>

</style>

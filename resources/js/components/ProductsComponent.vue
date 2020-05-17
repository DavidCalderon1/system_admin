<template>
    <div>
        <data-table
            :columns="columns"
            :url="listRoute"
            :translate="{ nextButton: 'Siguiente', previousButton: 'Atrás', placeholderSearch: 'Buscar...'}"
            ref="dataTable">
            <div slot="filters" slot-scope="{ tableData, perPage }">
                <div class="row mb-2">
                    <div class="col-md-6" id="content-filter">
                        <select class="form-control" v-model="tableData.length" id="select-length-data-table">
                            <option :key="page" v-for="page in perPage">{{ page }}</option>
                        </select>
                        <input
                            name="name"
                            class="form-control"
                            v-model="tableData.search"
                            placeholder="Buscar"
                            id="search"
                        >
                    </div>
                    <div class="col-md-6" v-if="userCanCreate == 1">
                        <a v-bind:href="createRoute" class="btn btn-success btn-sm pull-right">
                            <i class="fa fa-plus">Nuevo</i>
                        </a>
                    </div>
                </div>
            </div>
        </data-table>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             id="productInfoModal"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{productViewData.code}} -
                            {{productViewData.reference}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6"
                                 v-if="productViewData.image !== '' && typeof productViewData !== 'undefined'">
                                <div class="form-group">
                                    <img v-bind:src="productViewData.image" width="80%" height="80%" class="ml-5">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código: </label> {{productViewData.code}} <br>
                                    <label>Referencia: </label> {{productViewData.reference}}<br>
                                    <label>Ctegoría: </label> {{productViewData.categoryName}}<br>
                                    <label>Costo: </label> ${{productViewData.base_price}}<br>
                                    <label>Precio: </label> ${{productViewData.price}}<br>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4>Existencias en bodegas</h4>
                        <div class="form-row">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th scope="col">Bodega</th>
                                    <th scope="col">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="warehouse in productViewData.warehouses">
                                    <td>{{warehouse.name}}</td>
                                    <td>{{warehouse.quantity}} Unidades</td>
                                </tr>
                                <tr>
                                    <th colspan="1">TOTAL CANTIDADES</th>
                                    <th>{{totalQuantityWarehouses}} Unidades</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonDataTable from "./ButtonDataTable";
    import ImageDataTableComponent from "./ImageDataTableComponent";

    export default {
        name: "PurchasesComponent",
        props: {
            listRoute: String,
            viewRoute: String,
            createRoute: String,
            updateRoute: String,
            deleteRoute: String,
            userCanCreate: String,
            userCanView: String,
            userCanEdit: String,
            userCanDelete: String,
        },
        data() {
            return {
                columns: [
                    {
                        label: 'Imagen',
                        name: 'image',
                        orderable: true,
                        component: ImageDataTableComponent,
                    },
                    {
                        label: 'Código',
                        name: 'code',
                        orderable: true,
                    },
                    {
                        label: 'Referencia',
                        name: 'reference',
                        orderable: true,
                    },
                    {
                        label: 'Costo',
                        name: 'base_price',
                        orderable: true,
                    },
                    {
                        label: 'Precio',
                        name: 'price',
                        orderable: true,
                    },
                    {
                        label: 'Iva',
                        name: 'vat',
                        orderable: true,
                    },
                    {
                        label: 'Categoría',
                        name: 'category.name',
                        orderable: false,
                    },
                    {
                        label: '',
                        name: '',
                        orderable: false,
                        classes: {
                            view: {
                                userCanView: this.userCanView,
                                ico: {
                                    'fa fa-eye': true
                                },
                                'btn': true,
                                'btn-success': true,
                                'btn-sm': true,
                            },
                            edit: {
                                userCanEdit: this.userCanEdit,
                                ico: {
                                    'fa fa-pencil': true
                                },
                                'btn': true,
                                'btn-primary': true,
                                'btn-sm': true,
                            },
                            cancel: {
                                userCanCancel: this.userCanDelete,
                                ico: {
                                    'fa fa-trash': true
                                },
                                'btn': true,
                                'btn-danger': true,
                                'btn-sm': true,
                            }

                        },
                        event: "click",
                        handler: this.view,
                        component: ButtonDataTable,
                    },
                ],
                productViewData: {
                    image: '',
                    code: '',
                    reference: '',
                    categoryName: '',
                    base_price: '',
                    price: '',
                    warehouses: [
                        {
                            quantity: 0,
                        }
                    ]
                }
            }
        },
        computed: {
            totalQuantityWarehouses() {
                let total = 0;
                for (let i in this.productViewData.warehouses) {
                    total += this.productViewData.warehouses[i].quantity
                }
                return total;
            }
        },
        methods: {
            view(data, action) {
                switch (action) {
                    case 'view':
                        this.showInfoProduct(data.id)
                        break;
                    case 'edit':
                        window.location.href = this.getRouteWithId(this.updateRoute, data.id);
                        break;
                    case 'cancel':
                        this.cancel(data.id);
                        break;
                }
            },
            showInfoProduct(id) {
                axios.get(this.getRouteWithId(this.viewRoute, id))
                    .then(resp => {
                        this.productViewData.image = resp.data.image
                        this.productViewData.code = resp.data.code
                        this.productViewData.reference = resp.data.reference
                        this.productViewData.categoryName = resp.data.category.name
                        this.productViewData.base_price = resp.data.base_price
                        this.productViewData.price = resp.data.price
                        this.productViewData.warehouses = resp.data.warehouses

                        $('#productInfoModal').modal('show')
                    })
                    .catch(error => {
                        this.$alertify.error('Ha ocurrido un error obteniendo el producto');
                    })
            },
            cancel(id) {
                this.$alertify.confirm(
                    'Estas seguro que deseas eliminar el producto?',
                    () => {
                        let url = this.getRouteWithId(this.deleteRoute, id);
                        axios.delete(url)
                            .then(resp => {
                                this.$alertify.success(resp.data.message);
                                this.$refs.dataTable.getData()
                            })
                            .catch(error => {
                                this.$alertify.error(error.response.data.message);
                                this.$refs.dataTable.getData()

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
    td.laravel-vue-datatable-td {
        border: 2px solid #dee2e6 !important;
        font-size: 12px;
        text-align: center;
        padding: 5px;
    }

    th.laravel-vue-datatable-th {
        text-align: center;
        border: 1px solid #dee2e6 !important;
    }

    #select-length-data-table {
        width: 75px;
        margin-right: 10px;
    }

    div#content-filter {
        display: flex;
    }
</style>

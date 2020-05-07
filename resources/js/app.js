/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('../../node_modules/bootstrap-select/dist/js/bootstrap-select');

import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import VueAlertify from 'vue-alertify';
import select2 from 'select2'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import "bootstrap-select/sass/bootstrap-select.scss";



window.Vue = require('vue');
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueAlertify,{
    notifier: {
        // auto-dismiss wait time (in seconds)
        delay: 5,
        // default position
        position: 'top-right',
        // adds a close button to notifier messages
        closeButton: false,
    },
    glossary: {
        // dialogs default title
        title: '',
        // ok button text
        ok: 'Aceptar',
        // cancel button text
        cancel: 'Cancelar',
    },
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pagination-component', require('./components/PaginationComponent').default);
Vue.component('users-component', require('./components/UsersComponent.vue').default);
Vue.component('roles-component', require('./components/RolesComponent.vue').default);
Vue.component('grid-component', require('./components/GridComponent').default);
Vue.component('form-create-third-component', require('./components/FormCreateThirdComponent').default);
Vue.component('form-edit-third-component', require('./components/FormEditThirdComponent').default);
Vue.component('inventory-categories-component', require('./components/InventoryCategoriesComponent').default);
Vue.component('warehouses-component', require('./components/WarehousesComponent').default);
Vue.component('form-create-warehouse-component', require('./components/FormCreateWarehouseComponent').default);
Vue.component('form-create-product-component', require('./components/FormCreateProductComponent').default);
Vue.component('products-component', require('./components/ProductsComponent').default);
Vue.component('create-invoices-component', require('./components/CreateInvoicesComponent').default);
Vue.component('currency-input-component', require('./components/CurrencyInputComponent').default);
Vue.component('sales-component', require('./components/SalesComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

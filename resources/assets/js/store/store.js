require('./../bootstrap');
import Vue from 'vue';
import Vuex from 'vuex';
// Modules
import invoices from './modules/invoice/invoices';
import purchases from './modules/purchases/purchases';
import routers from './modules/router/routers';
import shiptickets from './modules/shiptickets/shiptickets';
import purchaseorders from './modules/purchaseorders/purchaseorders';
import users from './modules/users';
import products from './modules/products';
import customers from './modules/customers';
import vendors from './modules/vendors';
import departments from './modules/departments';
// Tasks
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';
// Use Statements
Vue.use(Vuex);

/**
 * Sets up the vuex global state object
 */
export const store = new Vuex.Store({
    state: {
        id: 0,
        permission: 0
    },
    getters,
    mutations,
    actions,
    modules: {
        users,
        invoices,
        purchases,
        routers,
        shiptickets,
        products,
        customers,
        vendors,
        departments,
        purchaseorders
    }
});
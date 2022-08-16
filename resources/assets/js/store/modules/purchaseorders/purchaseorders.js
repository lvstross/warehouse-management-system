// Tasks
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';

const state = {
    purchaseorders_open: [],
    purchaseorders_closed: [],
    purchaseorder: {
        id: 0,
        due_date: '',
        will_ship: '',
        ship_date: '',
        rating: '',
        sooner: '',
        customer: '',
        po_num: '',
        part_num: '',
        qty: '',
        stock: '',
        sales: '',
        need_routers: '',
        routers: [],
        cust_req: '',
        status: '',
        invoice: '',
        key: 0
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
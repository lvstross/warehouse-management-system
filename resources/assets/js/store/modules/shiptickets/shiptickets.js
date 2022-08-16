// Tasks
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';
// Utils
import Utils from '../../../modules/utils.js';
const state = {
    // Array of shiptickets
    shiptickets: [],
    shipticket: {
        part_num: '',
        po_num: '',
        qty: '',
        customer: '',
        status: 'Unapproved',
        cust_req: '',
        // routers [{ id: String, r_num: String, apply_qty: [Number, String], old_qty: [Number, String], new_router_total: [Number, String] }]
        routers: [],
        // boxes [{ router_num: String , cure_qtr: String , qty: Number , wt: String , dim: String }]
        boxes: [],
        // log [{ item: String }]
        log: [],
        date: Utils.getDateStr(),
        trip_count: 'No'
    },
    // Value that shows routers on the ship ticket form
    pnSet: false,
    edit: false,
    read: false
};

export default {
    state,
    getters,
    mutations,
    actions
};

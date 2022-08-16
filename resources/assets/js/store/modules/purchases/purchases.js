// Tasks
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';

const state = {
    purchases_open: [],
    purchases_closed: [],
    purchase: {
        id: 0,
        po_num: '',
        recv_num: '',
        vendor: '0',
        due_date: '',
        recv_date: '',
        terms: 'Choose An Item',
        carrier: '',
        vendor_confirm_order: '',
        items: [],
        po_total: '',
        entered_by: '',
        enter_date: '',
        modified_by: '',
        modify_date: '',
        status: 'Choose An Option',
        comment: ''
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
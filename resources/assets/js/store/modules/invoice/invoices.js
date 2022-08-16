// Tasks
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';
const state = {
    // Array for storing invoice collections
    invoices: [],
    inv_total: 0,
    /*
    * THE INVOICE STATE:
    * 1.) inv_num - Invoice Number | Type: Number
    * 2.) date - Shipping Date | Type: Date
    * 3.) customer - Customer Snap Shot | Type: Object 
    * 4.) po_num - Purchase Order Number | Type: String
    * 5.) line_items - List of line items | Type: Array of Objects 
    * 6.) misc_char - Miscellaneous Charges | Type: Number
    * 7.) ship_fee - Shipping Fees | Type: Number
    * 8.) total - Total Price of all Line Items Extended Prices | Type: Number
    * 9.) carrier - Shipping Carrier Name | Type: String
    * 10.) memo - Alternate information for the invoice | Type: String
    */
    invoice: {
        inv_num: 0,
        date: '',
        customer: {},
        po_num: '',
        line_items: [
            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
            { item: '', product: '', qty: 0, unit: 0, extended: 0 }
        ],
        misc_char: 0,
        ship_fee: 0,
        total: 0,
        carrier: '',
        memo: ''
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
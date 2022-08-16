// Tasks
import * as getters from './getters';
import * as mutations from './mutations';
import * as actions from './actions';
const state = {
    loading: true,
    // Array for storing routers collections
    routers: [],
    // Routers sorted by date in inventory
    routers_by_date: [],
    // Routers sorted by router number
    routers_by_router_num: [],
    // Routers 'In Production' that missed the drop window for dept_name change
    missed_routers: [],
    // the total number of routers
    router_total: 0,
    // the total of routers search by part number
    part_num_search_total: 0,
    /*
    * index, nextDept, nextStatus and element are values that are used when dragging
    * and dropping items in the partflow secion. These values are captured when dragging
    * and assigned to the dropped element.
    */
    index: Number,
    nextDept: '',
    nextStatus: '', 
    element: {},
    /*
    * the buffer is to indicate if the sortRouters action has already been called at least once in request.
    * if the request has already gone out in the last 500 milliseconds, it will be rejected by the client.
    * if buffer is 0, request will launch, else if 1, request will cancel.
    */
    buffer: 0,
    /*
    * Values for searching router information between different production area tabs
    */
    search_router: '',
    search_pn_num: '',
    search_pn_total: 0,
    /*
    * THE ROUTER STATE:
    * 1.) router_num - Router Number | Type: Number
    * 2.) part_num - Part Number | Type: String
    * 3.) qty - Quantity of router | Type: Number
    * 4.) stock_qty - Quanity add to stock when router reaches "II" status | Type: Number
    * 5.) status - Status Type (See Router Migration) | Type: String
    * 6.) dept_name - Department Name | Type: String
    * 7.) move_log - The log kept for evey movement of a router | Type: Array
    * 8.) date - The date the router was created | Type: String
    * 9.) date_in_inv - The date the router reached "II" status | Type: String
    * 10.) the key for the drag and dropability | Type: Number
    */
    router: {
        router_num: '',
        part_num: '',
        qty: '',
        stock_qty: '',
        status: '',
        dept_name: '',
        move_log: [], // { item: }
        date: '',
        date_in_inv: '',
        key: 0 // will always start at the routers id number
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};

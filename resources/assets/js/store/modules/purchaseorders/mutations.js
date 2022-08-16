/**
 * Mutations for setting the state with the array of purchaseorders
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 */
export const setPurchaseordersOpen = (state, payload) => {
    let pos = payload;
    pos.sort((a,b) => {
        return a.key - b.key;
    }).forEach((el,i,a) => {
        return a[i].key = i+1;
    });
    state.purchaseorders_open = pos;
};

/**
 * Mutations for setting the state with the array of purchaseorders
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 */
export const setPurchaseordersClosed = (state, payload) => {
    state.purchaseorders_closed = payload;
};

/**
 * Sort Purchaseorders
 *
 * @param state | vuex state
 * @return void
 */
export const sortPurchaseorders = (state, payload) => {
    state.purchaseorders_open = payload;
    for(let i=0; i < state.purchaseorders_open.length; i++){
        state.purchaseorders_open[i].key = i+1;
    }
};

/**
 * Mutations for setting the purchase order state
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 */
export const setPurchaseorderData = (state, payload) => {
    state.purchaseorder = payload;
};

/**
 * Update Due Date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePODueDate = (state, payload) => {
  state.purchaseorder.due_date = payload;
};

/**
 * Update Will Ship Date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOWillShip = (state, payload) => {
  state.purchaseorder.will_ship = payload;
};

/**
 * Update Ship Date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOShipDate = (state, payload) => {
  state.purchaseorder.ship_date = payload;
};

/**
 * Set Ship Date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const setPOShipDate = state => {
    if(state.purchaseorder.invoice == null || state.purchaseorder.invoice == ''){
        alert('Invoice number required before shipping a purchase order! Ship date not set.');
    }else{
        // Init Date
        let d = new Date();
        // Day
        let day = d.getDate();
        day = day.toString();
        if(day.length != 2){ day = '0' + day; }
        // Month
        let month = parseInt(d.getMonth()) + 1;
        month = month.toString();
        if(month.length != 2){ month = '0' + month }
        // Set Full Date
        let date = d.getFullYear() + '-' + month + '-' + day;
        state.purchaseorder.ship_date = date;
        alert('Ship date of ' + date + ' has been set. Will not be saved until you submit the form.');
    }

};

/**
 * Update rating
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePORating = (state, payload) => {
  state.purchaseorder.rating = payload;
};

/**
 * Update sooner
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOSooner = (state, payload) => {
    if(payload == 'CAO'){
        state.purchaseorder.sooner = 'No';
    }else{
        state.purchaseorder.sooner = payload;
    }
};

/**
 * Update customer
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOCustomer = (state, payload) => {
  state.purchaseorder.customer = payload;
};

/**
 * Update po number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePONum = (state, payload) => {
  state.purchaseorder.po_num = payload;
};

/**
 * Update part number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOPartNum = (state, payload) => {
    state.purchaseorder.part_num = payload;
};

/**
 * Update qty
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOQty = (state, payload) => {
      state.purchaseorder.qty = payload;
};

/**
 * Update stock
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOStock = (state, payload) => {
  state.purchaseorder.stock = payload;
};

/**
 * Update sales
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOSales = (state, payload) => {
  state.purchaseorder.sales = payload;
};

/**
 * Update need routers
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePONeedRouters = (state, payload) => {
    if(payload == 'CAO'){
        state.purchaseorder.need_routers = 'Yes';    
    }else{
        state.purchaseorder.need_routers = payload;
    }
};

/**
 * Update add a router
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOAddRouters = (state, payload) => {
  state.purchaseorder.routers.push(payload);
};

/**
 * Update remove a router
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePORemoveRouter = (state, payload) => {
  state.purchaseorder.routers.splice(payload, 1);
};

/**
 * Update customer requirements
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOCustReq = (state, payload) => {
  state.purchaseorder.cust_req = payload;
};

/**
 * Update status
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOStatus = (state, payload) => {
    if(payload == 'CAO'){
        state.purchaseorder.status = 'Open';
    }else{
        if(state.purchaseorder.invoice == null || state.purchaseorder.invoice == ''){
            alert('Invoice number required before closing a purchase order');
            state.purchaseorder.status = 'Open';
        }else{
            state.purchaseorder.status = payload;
        }
    }
};

/**
 * Update Invoice
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePOInvoice = (state, payload) => {
  state.purchaseorder.invoice = payload;
};

/**
 * Add a router to the po routers array
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const addPORouter = (state, payload) => {
    let router = {
        router: payload.router,
        qty: payload.qty
    }
  state.purchaseorder.routers.push(router);
};

/**
 * Add a router to the po routers array
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const removePORouter = (state, payload) => {
  state.purchaseorder.routers.splice(payload, 1);
};

/**
 * Change the router number of the router by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexRouter = (state, payload) => {
    if(payload.value){
      state.purchaseorder.routers[payload.index].router = payload.value;
    }
};

/**
 * Change the router qty of the router by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexQty = (state, payload) => {
    if(payload.value){
        state.purchaseorder.routers[payload.index].qty = payload.value;
    }
};

/**
 * Reset State
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const resetState = state => {
    state.purchaseorder = {
        id: 0,
        due_date: '',
        will_ship: '',
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
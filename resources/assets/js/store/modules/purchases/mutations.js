/**
 * Mutations for setting the state with the array of purchases
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 */
export const setPurchasesOpen = (state, payload) => {
    state.purchases_open = payload;
};

/**
 * Mutations for setting the state with the array of purchases
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 */
export const setPurchasesClosed = (state, payload) => {
    state.purchases_closed = payload;
};

/**
 * Mutations for setting the purchase state
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 */
export const setPurchaseData = (state, payload) => {
    state.purchase = payload;
};

/**
 * Update po number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseNum = (state, payload) => {
  state.purchase.po_num = payload;
};

/**
 * Update recv number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseRecvNum = (state, payload) => {
  state.purchase.recv_num = payload;
};

/**
 * Update vendor
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseVendor = (state, payload) => {
  state.purchase.vendor = payload;
};

/**
 * Update Due Date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseDueDate = (state, payload) => {
  state.purchase.due_date = payload;
};

/**
 * Update Receive Date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseRecvDate = (state, payload) => {
  state.purchase.recv_date = payload;
};

/**
 * Update Terms
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseTerms = (state, payload) => {
  state.purchase.terms = payload;
};

/**
 * Update carrier
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseCarrier = (state, payload) => {
  state.purchase.carrier = payload;
};

/**
 * Update vendor confirm order
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseVendorConfirmOrder = (state, payload) => {
    state.purchase.vendor_confirm_order = payload;
};

/**
 * Update add a item
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseAddItem = (state, payload) => {
  state.purchase.items.push(payload);
};

/**
 * Update remove an item
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseRemoveItem = (state, payload) => {
  state.purchase.items.splice(payload, 1);
};

/**
 * Update status
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseStatus = (state, payload) => {
    if(payload == 'CAO' || payload == 'Open'){
        state.purchase.status = 'Open';
    }else{ // payload == 'Closed'
        state.purchase.status = payload;
    }
};

/**
 * Update comments
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePurchaseComments = (state, payload) => {
    state.purchase.comments = payload;
};

/**
 * Add Purchase item
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const addPurchaseItem = (state, payload) => {
    if(!payload.qty_ordered){
      payload.qty_ordered = 0;
    }
    if(!payload.unit_cost){
      payload.unit_cost = 0.00;
    }
    state.purchase.items.push(payload);
    updatePoTotal(state);
};

/**
 * Add Purchase item
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const removePurchaseItem = (state, payload) => {
    state.purchase.items.splice(payload, 1);
    updatePoTotal(state);
};

/**
 * update po total
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updatePoTotal = (state) => {
      let total = 0;
      state.purchase.items.forEach(el => {
        total += el.unit_cost * el.qty_ordered;
      });
      state.purchase.po_total = total;
};

/**
 * Change the part number of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexPartNum = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].part_num = payload.value;
    }
};

/**
 * Change the ordered qty of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexQtyOrdered = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].qty_ordered = payload.value;
      updatePoTotal(state);
    }
};

/**
 * Change the qty received good of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexQtyRecvGood = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].qty_recv_good = payload.value;
    }
};

/**
 * Change the qty canceled of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexQtyCanceled = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].qty_canceled = payload.value;
    }
};

/**
 * Change the qty rejected of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexQtyRejected = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].qty_rej = payload.value;
    }
};

/**
 * Change the unit cost of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexUnitCost = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].unit_cost = payload.value;
      updatePoTotal(state);
    }
};

/**
 * Change the unit of measure of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexUnitOfMeasure = (state, payload) => {
      state.purchase.items[payload.index].unit_of_measure = payload.value;
};

/**
 * Change the received date of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexRecvDate = (state, payload) => {
    state.purchase.items[payload.index].recv_date = payload.value;
};

/**
 * Change the back order qty of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexBackOrderQty = (state, payload) => {
    if(payload.value){
      state.purchase.items[payload.index].back_order_qty = payload.value;
    }
};

/**
 * Change the vendor confirm date of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexVendorConfirmDate = (state, payload) => {
      state.purchase.items[payload.index].vendor_confirm_date = payload.value;
};

/**
 * Change the description of the items by index
 *
 * @param state | vuex state
 * @param payload | string
 * @return void
 */
export const changeIndexDescription = (state, payload) => {
    state.purchase.items[payload.index].description = payload.value;
};

/**
 * Reset State
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const resetState = state => {
    state.purchase = {
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
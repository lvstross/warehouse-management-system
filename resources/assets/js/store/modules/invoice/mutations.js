/**
 * Set the invoices array for loading into the list computed property 
 * in descending order by default
 *
 * @param state | vuex invoices state
 * @param payload | payload commited from dispatcher
 * @return void
 */
export const setInvoices = (state, payload) => {
  let invoices = payload;
    invoices.sort((a,b) => {
        return b.inv_num - a.inv_num;
    });
    state.invoices = invoices;
};

/**
 * Reset a specific line item to default values
 *
 * @param state | vuex invoices state
 * @param payload | payload commited from dispatcher
 * @return void
*/
export const resetLineItem = (state, payload) => {
    state.invoice.line_items[payload] = { item: '', product: '', qty: 0, unit: 0, extended: 0 };
};

/**
 * Sort the invoices in descending order
 *
 * @param state | vuex invoices state
 * @return void
 */
export const orderByDesc = state => {
    state.invoices.sort((a,b) => {
        return b.inv_num - a.inv_num;
    });
};

/**
 * Sort the invoices in ascending order
 *
 * @param state | vuex invoices state
 * @return void
 */
export const orderByAsc = state => {
    state.invoices.sort((a,b) => {
        return a.inv_num - b.inv_num;
    });
};

/**
 * Set the invoice report total
 *
 * @param state | vuex invoices state
 * @return void
 */
export const setInvTotal = state => {
    state.inv_total = 0;
    for(let i = 0; i < state.invoices.length; i++){
        state.inv_total += parseFloat(state.invoices[i].total);
    }
    state.inv_total = state.inv_total.toFixed(2);
}

/**
 * Resets the invoice report total back to zero
 *
 * @param state | vuex state
 * @return void
 */
export const resetTotal = state => {
    state.inv_total = 0;
}

/**
 * Sets the customer snap shot in the invoice state
 * to be stored with the invoice
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const setCustomer = (state, payload) => {
    state.invoice.customer = payload;
};

/**
 * Updates the invoice number with the next value
 * available in the database
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateInvNum = (state, payload) => {
    state.invoice.inv_num = parseInt(payload);
};

/**
 * Updates the date state from the date value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateDate = (state, payload) => {
    state.invoice.date = payload;
};

/**
 * Updates the PO state from the PO value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updatePo = (state, payload) => {
    state.invoice.po_num = payload;
};

/**
 * Updates the carrier state from the carrier value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateCarrier = (state, payload) => {
    state.invoice.carrier = payload;
};

/**
 * Updates the memo state from the memo value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateMemo = (state, payload) => {
    state.invoice.memo = payload;
};

/**
 * Updates the line item state from the line item value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateLineItem = (state, payload) => {
    state.invoice.line_items[payload.item].item = payload.event;
};

/**
 * Updates the product state from the product value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateProduct = (state, payload) => {
    state.invoice.line_items[payload.item].product = payload.event;
};

/**
 * Updates the qty state from the qty value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateQty = (state, payload) => {
    state.invoice.line_items[payload.item].qty = payload.event;
};

/**
 * Updates the unit price state from the unite price value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateUnit = (state, payload) => {
    state.invoice.line_items[payload.item].unit = payload.event;
};

/**
 * Updates the extended state from the extended value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateExtended = (state, payload) => {
    state.invoice.line_items[payload.item].extended = payload.ext;
};

/**
 * Updates the ship fee state from the ship fee value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateShipFee = (state, payload) => {
    state.invoice.ship_fee = payload;  
};

/**
 * Updates the misc. char state from the misc. char value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateMiscChar = (state, payload) => {
    state.invoice.misc_char = payload;  
};

/**
 * Updates the total state from the total value in the 
 * invoice form
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const updateTotal = (state, payload) => {
    state.invoice.total = payload;  
};

/**
 * Reset the invoice state back to original values
 *
 * @param state | vuex state
 * @return void
 */
export const resetState = (state) => {
    for(let key in state.invoice){
        if(key == 'inv_num' || key == 'ship_fee' || key == 'misc_char' || key == 'total'){
            state.invoice[key] = 0;
        }else if(key == 'customer'){
            state.invoice.customer = {};
        }else if(key == 'line_items'){
            for(let i = 0; i < 7; i++){
                for(let key in state.invoice.line_items[i]){
                    if(key == 'item' || key == 'product'){
                        state.invoice.line_items[i][key] = '';
                    }else{
                        state.invoice.line_items[i][key] = 0;
                    }
                }
            }  
        }else {
            state.invoice[key] = '';
        }
    }
};

/**
 * Sets the invoice data to the invoice model for updating
 *
 * @param state | vuex state
 * @param payload | payload from dispatcher
 * @return void
 */
export const setInvoiceData = (state, payload) => { 
    for(var key in payload.data){
        if(key === 'customer'){
            var cust = payload.data[key];
            if(typeof cust == 'string'){
                while(typeof cust == 'string'){
                    var cust = JSON.parse(cust);
                }
            }
            state.invoice.customer = cust;
        } else if (key === 'line_items'){
            var line = payload.data[key];
            if(typeof line == 'string'){
                while(typeof line == 'string'){
                    line = JSON.parse(line);
                }
            }
            for(var i = 0; i < line.length; i++){
                for(var l in line[i]){
                    state.invoice.line_items[i][l] = line[i][l];
                }
            }
        } else {
            state.invoice[key] = payload.data[key];
        }
    }
};
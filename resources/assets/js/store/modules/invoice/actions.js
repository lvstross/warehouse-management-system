 /**
 * This function is responsible for retrieving invoice
 * data and parsing the parts that need json parsing.
 * These parts specificlly are the customer information
 * and the line item information. This data is commited
 * to the setInvoices mutations function.
 *
 * @param commit | object
 * @return Array
 */
export const commitInvoices = ({ commit }) => {
    axios({
        method: 'get',
        url: 'api/invoices',
        validateStatus(status) {
            if(status >= 200 && status < 300){
                return status;
            }else if(status == 401){
                alert("Your session has timed out. You will now be redirected.");
                window.location = window.location.origin + '/login';
            }
        }
    }).then((response) => {
        var newData = () => {
            var data = response.data;
            for(var i = 0; i < data.length; i++){
                for(var key in data[i]){
                    if(key === 'customer'){
                        while( typeof data[i].customer == 'string' ){
                            data[i].customer = JSON.parse(data[i].customer);
                        }
                    }
                }
            }
            return data;
        }
        commit('setInvoices', newData());
    }).catch((error) => {
        throw new Error('commitInvoices action failed!!! ' + error);
    });
};

 /**
 * Same code from above but to a different route
 *
 * @param commit | object
 * @return Array
 */
export const commitAllInvoices = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/invoices/all',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'customer'){
                            while( typeof data[i].customer == 'string' ){
                                data[i].customer = JSON.parse(data[i].customer);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setInvoices', newData());
            resolve();
        }).catch((error) => {
            throw new Error('commitAllInvoices action failed!!! ' + error);
            reject();
        });
    });
};

 /**
 * This function is responsible for retrieving the data for
 * the customer that was chosen by the user to be stored
 * with the invoice. This function commits the data to the
 * setCustomer mutations function.
 *
 * @param commit | object
 * @param payload | object
 */
export const commitOneCustomer = ( { commit }, payload ) => {
    let id = payload.split('-');
    axios({
        method: 'get',
        url: 'api/customers/' + parseInt(id[0]),
        validateStatus(status) {
            if(status >= 200 && status < 300){
                return status;
            }else if(status == 401){
                alert("Your session has timed out. You will now be redirected.");
                window.location = window.location.origin + '/login';
            }
        }
    }).then((response) => {
        commit('setCustomer', response.data);
    }).catch((error) => {
        throw new Error('commitOneCustomer action failed' + error);
    });
};


 /**
 * Action responsible for adding up the extended prices of each line item
 *
 * @param context | object for commits
 * @param payload = {
 *        item: type=Number | 0-6 traversing an array,
 *        event: input event.target.value,
 *        set: type=Number | 0 = qty, 1 = unit
 *    }
 * @return void
 */
export const commitMath = (context, payload) => {
    if(payload.set === 0){
        context.commit('updateQty', {item: payload.item, event: payload.event});
    } else if(payload.set === 1){
        context.commit('updateUnit', {item: payload.item, event: payload.event});
    } else {
        throw new Error('Unexpected:' + payload.set + '. Expecting 0 or 1 for commitMath action.');
    }
    let extended = context.state.invoice.line_items[payload.item].qty *
                   context.state.invoice.line_items[payload.item].unit;
    context.commit('updateExtended', {item: payload.item, ext: extended});
};

 /**
 * Action responible for adding up all the extended prices, the shipping
 * fee and the misc charges.
 *
 * @param context | object
 * @return void
 */
export const commitTotal = context => {
    let total = () => {
        let t = 0;
        for(let i = 0; i < 7; i++){
            t += context.state.invoice.line_items[i].extended;
        }
        t += parseFloat(context.state.invoice.ship_fee);
        t += parseFloat(context.state.invoice.misc_char);
        return t;
    }
    let totalToFloat = total();
    context.commit('updateTotal', totalToFloat.toFixed(2));
};

 /**
 * Responsible for createing a new invoice and then committing
 * the resetState mutations function. Promise Added due to component
 * methods needing execution after promise is finished.
 *
 * @param context | object
 * @return new Promise
 */
export const createNewInvoice = context => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.invoice);
        axios({
            method: 'post',
            url: 'api/invoices/store',
            data: params,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            context.commit('resetState');
            resolve();
        }).catch((error) => {
            throw new Error('createNewInvoice action failed!' + error);
            reject();
        });
    });
};

 /**
 * Responsible for pull invoice info to be edited
 * Promise Added due to component methods needing execution
 * after promise is finished.
 *
 * @param commit | object
 * @param payload | object
 * @return new Promise
 */
export const showInvoice = ( { commit }, payload ) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/invoices/' + payload,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setInvoiceData', response);
            resolve();
        }).catch((error) => {
            throw new Error('show invoice action failed!' + error);
            reject();
        });
    });
};

/**
 * Update an invoice resource
 *
 * @param context | object
 * @param payload | object
 * @return new Promise
 */
export const updateInvoice = (context, payload) => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.invoice);
        axios({
            method: 'patch',
            url: 'api/invoices/' + payload,
            data: params,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then(() => {
            context.commit('resetState');
            resolve();
        }).catch((error) => {
            throw new Error('updateInvoice failed! ' + error);
            reject();
        });
    });
};

/**
 * Delete specific invoice
 *
 * @param context | object
 * @param payload | object
 * @return new Promise
 */
export const deleteInvoice = (context, payload) => {
    return new Promise((resolve, reject) => {
        if(confirm('Are you sure you want to delete this invoice?')){
            axios({
                method: 'delete',
                url: 'api/invoices/' + payload,
                validateStatus(status) {
                    if(status >= 200 && status < 300){
                        return status;
                    }else if(status == 401){
                        alert("Your session has timed out. You will now be redirected.");
                        window.location = window.location.origin + '/login';
                    }
                }
            }).then(() => {
                resolve();
            }).catch((error) => {
                throw new Error('deleteInvoice failed! ' + error);
                reject();
            });
        }else{
            return;
        }
    });
};

/**
 * Gets data for an invoice report between two different dates
 *
 * @param commit | object
 * @param payload | object
 * @return new Promise
 */
export const dateRangeSearch = ({ commit }, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/invoices/report/' + payload.start + '/' + payload.end,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'customer'){
                            while(typeof data[i].customer === 'string'){
                                data[i].customer = JSON.parse(data[i].customer);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setInvoices', newData());
            commit('setInvTotal');
        }).catch((error) => {
            throw new Error('date range search action failed!!! ' + error);
        });
    });
};

/**
 * Searchs for an invoice by invoice number
 *
 * @param commit | object
 * @param payload | object
 * @return new Promise
 */
export const searchInv = ({ commit }, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/invoices/search/' + payload,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'customer'){
                            while(typeof data[i].customer === 'string'){
                                data[i].customer = JSON.parse(data[i].customer);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setInvoices', newData());
        }).catch((error) => {
            throw new Error('searchInv failed!' + error);
        });
    });
};

/**
 * Commits the next invoice number in line from the database
 *
 * @param commit | object
 * @return void
 */
export const commitNextInvNum = ({ commit }) => {
    axios({
        method: 'get',
        url: 'api/invoice/prefix',
        validateStatus(status) {
            if(status >= 200 && status < 300){
                return status;
            }else if(status == 401){
                alert("Your session has timed out. You will now be redirected.");
                window.location = window.location.origin + '/login';
            }
        }
    }).then((response) => {
        let data = response.data;
        commit('updateInvNum', data);
    }).catch((error) => {
        throw new Error('commit next invoice number failed' + error);
    });
};

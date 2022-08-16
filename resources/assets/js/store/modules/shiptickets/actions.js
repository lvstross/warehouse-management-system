 /**
 * Commit the shiptickets list 
 *
 * @param commit | object
 * @return Array
 */
export const commitShipTickets = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/inventory',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setShipTicList', response.data);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('commit ship ticket action failed!!! ' + error);
        });
    });
};

 /**
 * Commit the shiptickets list all
 *
 * @param commit | object
 * @return Array
 */
export const commitAllShipTickets = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/inventory/all',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setShipTicList', response.data);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('commit all ship ticket action failed!!! ' + error);
        });
    });
};

 /**
 * Responsible for createing a new ship ticket and then committing
 * the resetState mutations function. Promise Added due to component
 * methods needing execution after promise is finished.
 *
 * @param context | object
 * @return new Promise
 */
export const createNewShipTic = context => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.shipticket);
        axios({
            method: 'post',
            url: 'api/inventory/store',
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
            if(response.data.value == true){
                context.commit('resetState');
                resolve(response);
            }else if(response.data.value == false){
                resolve(response);
            }
        }).catch((error) => {
            throw new Error('create ship ticket action failed!' + error);
            reject();
        });
    });
};

 /**
 * Responsible for pull router info to be edited
 * Promise Added due to component methods needing execution
 * after promise is finished.
 *
 * @param commit | object
 * @param payload | Number
 * @return new Promise
 */
export const showShipTic = ( context, payload ) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/inventory/' + payload,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            context.commit('setShipTicData', response.data);
            resolve();
        }).catch((error) => {
            throw new Error('show ship ticket action failed!' + error);
            reject();
        });
    });
};

/**
 * Update an ship ticket resource
 *
 * @param context | object
 * @param payload | object
 * @return new Promise
 */
export const updateShipTic = (context, payload) => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.shipticket);
        axios({
            method: 'patch',
            url: 'api/inventory/' + payload,
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
            throw new Error('update ship ticket failed! ' + error);
            reject();
        });
    });
};

/**
 * Delete specific ship ticket
 *
 * @param context | object
 * @param payload | object
 * @return new Promise
 */
export const deleteShipTic = (context, payload) => {
    return new Promise((resolve, reject) => {
        if(confirm('Are you sure you want to delete this ship ticket?')){
            axios({
                method: 'delete',
                url: 'api/inventory/' + payload,
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
                throw new Error('delete ship ticket failed! ' + error);
                reject();
            });
        }else{
            return;
        }
    });
};

 /**
 * Search Inventory ship tickets by part number
 *
 * @param commit | object
 * @return Array
 */
export const shipTicPnSearch = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/inventory/search/partnum/' + payload,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            context.commit('setShipTicList', response.data);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('shipTicPnSearch action failed!!! ' + error);
        });
    });
};

 /**
 * Search Inventory ship tickets by date
 *
 * @param commit | object
 * @return Array
 */
export const shipTicDateSearch = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/inventory/search/date/' + payload,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            context.commit('setShipTicList', response.data);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('shipTicDateSearch action failed!!! ' + error);
        });
    });
};

 /**
 * Search Inventory ship tickets by status
 *
 * @param commit | object
 * @return Array
 */
export const shipTicStSearch = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/inventory/search/status/' + payload,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            context.commit('setShipTicList', response.data);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('shipTicStSearch action failed!!! ' + error);
        });
    });
};
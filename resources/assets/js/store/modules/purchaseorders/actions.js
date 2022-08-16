/**
 * Gets open purchase orders data
 *
 * @param commit | object
 * @return void
 */
export const commitOpenPurchaseorders = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios.get('api/purchaseorders/open')
        .then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'routers'){
                            while( typeof data[i].routers == 'string' ){
                                data[i].routers = JSON.parse(data[i].routers);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setPurchaseordersOpen', newData());
            resolve();
        }).catch((error) => {
            throw new Error("commit open purchaseorders failed!!" + error);
            reject();
        });
    });
};

/**
 * Clear All Purchase Order Ratings
 *
 * @param commit | object
 * @return void
 */
export const clearAllRating = ({ commit }) => {
    if(confirm('Are you sure you want to clear all ratings? This can not be undone.')){
        return new Promise((resolve, reject) => {
            axios.patch('api/purchaseorders/clear')
            .then(() => {
                resolve();
            }).catch((error) => {
                throw new Error("clearAllRating failed!!" + error);
                reject();
            });
        });
    }
};

/**
 * Gets open purchase orders data
 *
 * @param commit | object
 * @return void
 */
export const commitClosedPurchaseorders = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios.get('api/purchaseorders/closed')
        .then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'routers'){
                            while( typeof data[i].routers == 'string' ){
                                data[i].routers = JSON.parse(data[i].routers);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setPurchaseordersClosed', newData());
            resolve();
        }).catch((error) => {
            throw new Error("commit closed purchaseorders failed!!" + error);
            reject();
        });
    });
};

/**
 * Resort the purchaseorders
 *
 * @param state | vuex state
 * @return void
 */
export const sortPurchaseorders = (context, payload) => {
    context.commit('sortPurchaseorders', payload);
    axios({
        method: 'post',
        url: 'api/purchaseorders/sort',
        data: context.state.purchaseorders_open,
        validateStatus(status) {
            return status >= 200 && status < 300;
        }
    }).then((response) => {
        // console.log('Purchase orders sorted!');
    }).catch((error) => {
        throw new Error('sortPurchaseorders action failed!' + error);
    });
};

 /**
 * Responsible for createing a new purchase order
 *
 * @param context | object
 * @return new Promise
 */
export const createNewPO = context => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.purchaseorder);
        axios({
            method: 'post',
            url: 'api/purchaseorders/store',
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
            throw new Error('createNewPO action failed!' + error);
            reject();
        });
    });
};

 /**
 * show purchase order data
 *
 * @param commit | object
 * @param payload | object
 * @return new Promise
 */
export const showPO = ( { commit }, payload ) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchaseorders/' + payload,
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
                    for(var key in data){
                        if(key === 'routers'){
                            while( typeof data.routers == 'string' ){
                                data.routers = JSON.parse(data.routers);
                            }
                        }
                    }
                return data;
            }
            commit('setPurchaseorderData', newData());
            resolve();
        }).catch((error) => {
            throw new Error('show purchase order action failed!' + error);
            reject();
        });
    });
};

 /**
 * Responsible for updating a po
 *
 * @param context | object
 * @return new Promise
 */
export const updatePO = (context, payload) => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.purchaseorder);
        axios({
            method: 'patch',
            url: 'api/purchaseorders/' + payload,
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
            throw new Error('updatePO action failed!' + error);
            reject();
        });
    });
};

 /**
 * Responsible for deleting a po
 *
 * @param context | object
 * @return new Promise
 */
export const deletePO = (context, payload) => {
    return new Promise((resolve, reject) => {
        if(confirm('Are you sure you want to delete this purchaseorder?')){
            axios({
                method: 'delete',
                url: 'api/purchaseorders/' + payload,
                validateStatus(status) {
                    if(status >= 200 && status < 300){
                        return status;
                    }else if(status == 401){
                        alert("Your session has timed out. You will now be redirected.");
                        window.location = window.location.origin + '/login';
                    }
                }
            }).then((response) => {
                resolve();
            }).catch((error) => {
                throw new Error('deletePO action failed!' + error);
                reject();
            });
        }
    });
};

/**
 * Search PO number
 *
 * @param commit | object
 * @return void
 */
export const searchPONum = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchaseorders/search/byponum/' + payload.status + '/' + payload.value,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            if(payload.status == 'Open'){
                context.commit('setPurchaseordersOpen', response.data);
            }else if(payload.status == 'Closed'){
                context.commit('setPurchaseordersClosed', response.data);
            }
            resolve();
        }).catch((error) => {
            throw new Error("search by purchase order number failed!!" + error);
            reject();
        });
    });
};

/**
 * Search customer
 *
 * @param commit | object
 * @return void
 */
export const searchCust = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchaseorders/search/bycust/' + payload.status + '/' + payload.value,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            if(payload.status == 'Open'){
                context.commit('setPurchaseordersOpen', response.data);
            }else if(payload.status == 'Closed'){
                context.commit('setPurchaseordersClosed', response.data);
            }
            resolve();
        }).catch((error) => {
            throw new Error("search by customer failed!!" + error);
            reject();
        });
    });
};

/**
 * Search PO number
 *
 * @param commit | object
 * @return void
 */
export const searchPartNum = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchaseorders/search/bypartnum/' + payload.status + '/' + payload.value,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            if(payload.status == 'Open'){
                context.commit('setPurchaseordersOpen', response.data);
            }else if(payload.status == 'Closed'){
                context.commit('setPurchaseordersClosed', response.data);
            }
            resolve();
        }).catch((error) => {
            throw new Error("search by part number failed!!" + error);
            reject();
        });
    });
};
/**
 * Gets open purchases data
 *
 * @param commit | object
 * @return void
 */
export const commitOpenPurchases = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios.get('api/purchases/open')
        .then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'items'){
                            while( typeof data[i].items == 'string' ){
                                data[i].items = JSON.parse(data[i].items);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setPurchasesOpen', newData());
            resolve();
        }).catch((error) => {
            throw new Error("commit open purchases failed!!" + error);
            reject();
        });
    });
};

/**
 * Gets open purchase orders data
 *
 * @param commit | object
 * @return void
 */
export const commitClosedPurchases = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios.get('api/purchases/closed')
        .then((response) => {
            var newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'items'){
                            while( typeof data[i].items == 'string' ){
                                data[i].items = JSON.parse(data[i].items);
                            }
                        }
                    }
                }
                return data;
            }
            commit('setPurchasesClosed', newData());
            resolve();
        }).catch((error) => {
            throw new Error("commit closed purchases failed!!" + error);
            reject();
        });
    });
};

 /**
 * Responsible for createing a new purchase order
 *
 * @param context | object
 * @return new Promise
 */
export const createNewPurchase = context => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.purchase);
        axios({
            method: 'post',
            url: 'api/purchases/store',
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
export const showPurchase = ( { commit }, payload ) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchases/' + payload,
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
                        if(key === 'items'){
                            while( typeof data.items == 'string' ){
                                data.items = JSON.parse(data.items);
                            }
                        }
                    }
                return data;
            }
            commit('setPurchaseData', newData());
            resolve();
        }).catch((error) => {
            throw new Error('show purchase action failed!' + error);
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
export const updatePurchase = (context, payload) => {
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.purchase);
        axios({
            method: 'patch',
            url: 'api/purchases/' + payload,
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
export const deletePurchase = (context, payload) => {
    return new Promise((resolve, reject) => {
        if(confirm('Are you sure you want to delete this purchase?')){
            axios({
                method: 'delete',
                url: 'api/purchases/' + payload,
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
export const searchPurchaseNum = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchases/search/byponum/' + payload.status + '/' + payload.value,
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
                context.commit('setPurchasesOpen', response.data);
            }else if(payload.status == 'Closed'){
                context.commit('setPurchasesClosed', response.data);
            }
            resolve();
        }).catch((error) => {
            throw new Error("search by purchase number failed!!" + error);
            reject();
        });
    });
};

/**
 * Search vendor
 *
 * @param commit | object
 * @return void
 */
export const searchVendor = (context, payload) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/purchases/search/byvendor/' + payload.status + '/' + payload.value,
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
                context.commit('setPurchasesOpen', response.data);
            }else if(payload.status == 'Closed'){
                context.commit('setPurchasesClosed', response.data);
            }
            resolve();
        }).catch((error) => {
            throw new Error("search by vendor failed!!" + error);
            reject();
        });
    });
};
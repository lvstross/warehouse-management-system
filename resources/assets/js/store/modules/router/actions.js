import Utils from '../../../modules/utils.js'

 /**
 * Commit the routers to be mutated to their assigned 
 *
 * @param commit | object
 * @return Array
 */
export const commitRouters = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/routers',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            let newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'date'){
                            data[i].date = data[i].date;
                        }else if(key === 'move_log'){
                            data[i].move_log = JSON.parse(data[i].move_log);
                        }
                    }
                }
                return data;
            }
            let routers = newData();
            commit('setRouters', routers);
            commit('routersMisPlaced', routers);
            // commit('sortRoutersByDate', routers);
            commit('sortRoutersByRouterNum', routers);
            commit('sortRoutersList', routers);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('commitRouters action failed!!! ' + error);
        });
    });
};

 /**
 * Commit all routers
 *
 * @param commit | object
 * @return Array
 */
export const commitAllRouters = ({ commit }) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/routers/all',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            let newData = () => {
                var data = response.data;
                for(var i = 0; i < data.length; i++){
                    for(var key in data[i]){
                        if(key === 'date'){
                            data[i].date = data[i].date;
                        }else if(key === 'move_log'){
                            data[i].move_log = JSON.parse(data[i].move_log);
                        }
                    }
                }
                return data;
            }
            let routers = newData();
            commit('sortRoutersByRouterNum', routers);
            commit('sortRoutersByDate', routers);
            resolve();
        }).catch((error) => {
            reject();
            throw new Error('commitRouters action failed!!! ' + error);
        });
    });
};

 /**
 * Responsible for createing a new router and then committing
 * the resetState mutations function. Promise Added due to component
 * methods needing execution after promise is finished.
 *
 * @param context | object
 * @return new Promise
 */
export const createNewRouter = context => {
    // Check to see if qty or stock_qty are empty
    if(context.state.router.qty == '' || context.state.router.qty == null){
        context.commit('updateRouterQty', 0);
    }
    if(context.state.router.stock_qty == '' || context.state.router.stock_qty == null){
        context.commit('updateRouterStQty', 0);
    }
    return new Promise((resolve, reject) => {
        let params = Object.assign({}, context.state.router);
        axios({
            method: 'post',
            url: 'api/routers/store',
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
                resolve(response.data.value);    
            }else{
                resolve(response.data.value);
                context.commit('resetState');
            }
        }).catch((error) => {
            throw new Error('createRouter action failed!' + error);
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
 * @param payload | {r_id: Number, mode: String 'edit' || 'view'}
 * @return new Promise
 */
export const showRouter = ( { commit }, payload ) => {
    return new Promise((resolve, reject) => {
        axios({
            method: 'get',
            url: 'api/routers/' + payload.r_id,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setRouterData', {resp: response.data, mode: payload.mode});
            resolve();
        }).catch((error) => {
            throw new Error('show router action failed!' + error);
            reject();
        });
    });
};

/**
 * Update an router resource
 *
 * @param context | object
 * @param payload | object
 * @return new Promise
 */
export const updateRouter = (context, payload) => {
    return new Promise((resolve, reject) => {
        context.commit('reverseMoveLog');
        console.log(context.state.router);
        let params = Object.assign({}, context.state.router);
        axios({
            method: 'patch',
            url: 'api/routers/' + payload,
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
            throw new Error('updateRouter failed! ' + error);
            reject();
        });
    });
};

/**
 * Delete specific router
 *
 * @param context | object
 * @param payload | object
 * @return new Promise
 */
export const deleteRouter = (context, payload) => {
    return new Promise((resolve, reject) => {
        if(confirm('Are you sure you want to delete this router?')){
            axios({
                method: 'delete',
                url: 'api/routers/' + payload,
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
                throw new Error('deleteRouter failed! ' + error);
                reject();
            });
        }else{
            return;
        }
    });
};

/**
 * Resort the routers
 *
 * @param state | vuex state
 * @return void
 */
export const sortRouters = (context, payload) => {
    let moveLog = generateMoveLogEntry(context.state, context.state.element);
    if(moveLog != false){
        context.state.element.move_log.push(moveLog);
    }
    context.state.element.dept_name = context.state.nextDept;
    context.state.element.status = context.state.nextStatus;
    context.state.element.key = context.state.index;
    // Send new router keys
    context.commit('sortRoutersList', context.state.routers);
    if(context.state.buffer == 0){
        // log the sorted router in the system log
        logSorted(context.state.element);
        context.commit('setBuffer', 1);
        axios({
            method: 'post',
            url: 'api/routers/sort',
            data: context.state.routers,
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            setTimeout(()=>{
                context.commit('setBuffer', 0);
            }, 150);
        }).catch((error) => {
            throw new Error('sortRouters action failed!' + error);
        });
    }
};

/**
 * log the sorted router
 *
 * @param element
 * @param dept_name
 * @param status
 * @return void
 */
 export const logSorted = (element)  => {
    axios({
        method: 'post',
        url: 'api/routers/log/sort',
        data: element,
        validateStatus(status) {
            if(status >= 200 && status < 300){
                return status;
            }else if(status == 401){
                alert("Your session has timed out. You will now be redirected.");
                window.location = window.location.origin + '/login';
            }
        }
    }).then((response) => {
        //
    }).catch((error) => {
        throw new Error('logSorted action failed!' + error);
    });
 };

/**
 * Generates a move log entry object to be pushed to the related elements
 * move_log property.
 *
 * @param el | Object
 * @return Boolean if log = '' || Object if log != ''
 */
export const generateMoveLogEntry = (state, el) => {
    let log = '';
    if(el.dept_name != state.nextDept && el.status == state.nextStatus ){
        if(state.nextDept != ''){
            log += 'At ';
            log += Utils.convertMilitaryTime() + ' on ';
            log += Utils.getDate() + ', ';
            log += 'router moved into the ' + state.nextDept + ' department.';
        } else {
            return false;
        }
    } else if (el.status != state.nextStatus){
        log += 'At ';
        log += Utils.convertMilitaryTime() + ' on ';
        log += Utils.getDate() + ', ';
        log += "router status went from '" + el.status + "' to '" + state.nextStatus + "'";
        if(el.dept_name == '' && state.nextDept != ''){
            log += " and moved into the " + state.nextDept + ' department';
        } else if (el.dept_name != '' && state.nextDept == '') {
            if(el.dept_name != null){
                log += ' and moved out of the ' + el.dept_name + ' department';
            }
        }
        log += '.';
    } else {
        return false;
    }
    if(log != ''){
        let logEntry = { item: '' }
        logEntry.item = log;
        return logEntry;
    }
};

/**
 * Switch STFI router to II status and
 * equal the stock_qty to the qty
 */
export const switchToII = (context) => {
    return new Promise((resolve, reject) => {
        context.commit('stfiToII');
        axios({
            method: 'post',
            url: 'api/routers/inventory',
            data: context.state.routers,
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
            throw new Error('updateRouter failed! ' + error);
            reject();
        });
    });
};
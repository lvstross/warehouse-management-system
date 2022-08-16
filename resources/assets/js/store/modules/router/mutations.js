import Utils from '../../../modules/utils.js';

/**
 * The the routers array for loading into the list computed property
 *
 * @param state | vuex router state state
 * @param payload | payload commited from dispatcher
 * @return void
 */
export const setLoad = (state, payload) => {
    state.loading = payload;
};

/**
 * The the routers array for loading into the list computed property
 *
 * @param state | vuex router state state
 * @param payload | payload commited from dispatcher
 * @return void
 */
export const setRouters = (state, payload) => {
    state.routers = payload;
};

/**
 * Set the butter value
 *
 * @param state | vuex router state state
 * @param payload | payload commited from dispatcher
 * @return void
 */
export const setBuffer = (state, payload) => {
    state.buffer = payload;
};

/**
 * The the routers array for loading into the list computed property
 *
 * @param state | vuex router state state
 * @param payload | payload commited from dispatcher
 * @return void
 */
export const routersMisPlaced = (state, payload) => {
    let routers = payload
    routers = routers.filter((el,i,a) => {
        return el.status == 'IP' && el.dept_name == null;
    });
    state.missed_routers = routers;
};

/**
 * Sets the future index of the router in the part flow
 *
 * @param state | vuex router state
 * @param payload | Number
 */
export const setFutureIndex = (state, payload) => {
  state.index = payload;
  // console.log("Index: ", state.index);
};

/**
 * Sets the next department for the dragged element
 *
 * @param state | vuex router state
 * @param payload | String
*/
export const setNextDept = (state, payload) => {
  state.nextDept = payload;
  // console.log("Next Dept: ", state.nextDept);
};

/**
 * Sets the next status for the dragged element
 *
 * @param state | vuex router state
 * @param payload | String
*/
export const setNextStatus = (state, payload) => {
  state.nextStatus = payload;
  // console.log("Next Status: ", state.nextStatus);
};

/**
 * saves the dragged element to be pushed back into the original state
 *
 * @param state | vuex router state
 * @param payload | Object
*/
export const setDraggedElement = (state, payload) => {
  state.element = payload;
  // console.log("Element: ", state.element);
};

/**
 * Update router number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterNum = (state, payload) => {
  payload == '' ? state.router.router_num = 0 : state.router.router_num = payload;
};

/**
 * Update router part number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterPartNum = (state, payload) => {
  state.router.part_num = payload;
};

/**
 * Update router quantity
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterQty = (state, payload) => {
  // payload == '' ? state.router.qty = 0 : state.router.qty = payload;
  state.router.qty = payload;
};

/**
 * Update router stock quantity
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterStQty = (state, payload) => {
  // if(payload == ''){
  //   state.router.stock_qty = 0;
  //   return;
  // }
  state.router.stock_qty = payload;
};

/**
 * Update router status
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterStatus = (state, payload) => {
  if(payload != 'II'){
    state.router.date_in_inv = null;
    state.router.stock_qty = 0;
  }
  if(payload == 'NIP' || payload == 'STFI' || payload == 'II' || payload == 'A'){
    state.router.dept_name = null;
    state.router.status = payload;
  }else if(payload == 'CAI'){
    return; 
  }
};

/**
 * Update router department
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterDeptName = (state, payload) => {
  state.router.dept_name = payload;
};

/**
 * Update router move log
 *
 * @param state | vuex state
 * @param payload | payload from commit {eventVal: e.target.value, logIndex: index}
 * @return void
 */
export const updateRouterMoveLog = (state, payload) => {
  state.router.move_log[payload.logIndex].item = payload.eventVal;
};

/**
 * Update router date
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterDate = (state, payload) => {
  state.router.date = payload;
};

/**
 * Update router date in inventory
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterDateII = (state, payload) => {
  state.router.date_in_inv = payload;
};

/**
 * Update part_num_search_total
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateRouterPnSearchTotal = (state, payload) => {
  state.part_num_search_total = payload;
};

/**
 * Remove a move log item by its index
 *
 * @param state | vuex state
 * @param payload | Number
 * @return void
 */
export const deleteLogItem = (state, payload) => {
  if(confirm('Are you sure you want to delete this move log entry?')){
    state.router.move_log.splice(payload, 1);
  }
};

/**
 * Remove all move log items
 *
 * @param state | vuex state
 * @param payload | Number
 * @return void
 */
export const removeAllMoveLog = (state, payload) => {
  if(state.router.move_log.length > 0){
    if(confirm('Are you sure you want to delete all the move log items?')){
      if(confirm('Are you really sure you want to remove them all? These move log items are not recoverable after this point.')){
        state.router.move_log = [];
      }
    }
  }else {
    alert('You have no move log items to delete.');
  }
};

/**
 * Add a log item to the move_log index of that given router.
 *
 * @param state | vuex state
 * @param payload | String
 * @return void
 */
export const createLogItem = (state, payload) => {
    let log_item = { item: payload };
    state.router.move_log.unshift(log_item);
};

/**
 * Reset the router state back to original values
 *
 * @param state | vuex state
 * @return void
 */
export const resetState = (state) => {
    for(let key in state.router){
        if(key == 'rating' || key == 'key'){
            state.router[key] = 0;
        }else if(key == 'move_log'){
            state.router[key] = [];
        }else {
            state.router[key] = '';
        }
    }
};

/**
 * Sets the router data to the router model for updating
 *
 * @param state | vuex state
 * @param payload | payload { resp: Object, mode: String 'edit' || 'view'}
 * @return void
 */
export const setRouterData = (state, payload) => {
    for(var key in payload.resp){
      if(key == 'move_log'){
        if(payload.mode === 'view'){
          state.router[key] = JSON.parse(payload.resp[key]);
        }else if(payload.mode === 'edit'){
           let log = JSON.parse(payload.resp[key]);
           let newLog = [];
           for(let i = 0; i<log.length; i++){
              newLog.unshift(log[i]);
           }
           state.router[key] = newLog;
        }
      }else if(key == 'router_num'){
        state.router[key] = payload.resp[key].toString();
      }else{
        state.router[key] = payload.resp[key];
      }
    }
    // console.log(state.router);
};

/**
 * Reverse movelog data
 */
export const reverseMoveLog = state => {
     let log = state.router.move_log;
     let newLog = [];
     for(let i = 0; i<log.length; i++){
        newLog.unshift(log[i]);
     }
     state.router.move_log = newLog;
};

/**
 * Sorts all the routers by their key and then
 * sets the keys to equal their index number as to
 * avoid duplicate values in the keys.
 *
 * @return void
 */
export const sortRoutersList = (state, payload) => {
  let routers = payload;
    routers.stableSort((a,b) => {
        return a.key - b.key;
    }).forEach((el,i,a) => {
        return a[i].key = i+1;
    });
    state.routers = routers;
};

/**
 * Sorts all the routers by date
 *
 * @return void
 */
export const sortRoutersByDate = (state, payload) => {
    let routers = payload;
    routers = routers.map((el) => {
        return el;
    }).stableSort((a,b) => {
        return new Date(b.date) - new Date(a.date);
    });
    state.routers_by_date = routers;
};

/**
 * Sorts all the routers by router number
 *
 * @return void
 */
export const sortRoutersByRouterNum = (state, payload) => {
    let routers = payload;
    routers = routers.map((el) => {
      return el;
    }).stableSort((a,b) => {
      return b.router_num - a.router_num;
    });
    state.routers_by_router_num = routers;
};

/**
 * Sorts all the routers by router number in descending order
 *
 * @return void
 */
export const orderByDesc = state => {
    state.routers_by_router_num.sort((a,b) => {
        return b.router_num - a.router_num;
    });
};

/**
 * Sorts all the routers by router number in ascending order
 *
 * @return void
 */
export const orderByAsc = state => {
    state.routers_by_router_num.sort((a,b) => {
        return a.router_num - b.router_num;
    });
};

/**
 * Switchs all stfi status routers to ii status routers
 * @param state | vuex state
 * @return void
 */
export const stfiToII = (state) => {
    state.routers.forEach((el) => {
      if(el.status == 'STFI'){
        el.status = 'II';
        el.stock_qty = el.qty;
        el.date_in_inv = Utils.getDateStr();
        let move = {
          item: 'On ' + Utils.getDate() + ', router was submitted to inventory with a qty of ' + el.qty + ' peice(s).'
        }
        el.move_log.push(move);
      }
    });
};

/**
 * Filter Routers by Router Number
 * @param state | vuex state
 * @param payload | String 'flow', 'date', 'router'
 * @return void
 */
export const searchByRouter = (state, payload) => {
    state.search_pn_total = 0;
    if(payload == 'flow'){
      state.routers = state.routers.filter((el) => {
          return el.router_num == state.search_router;
      });
    } else if (payload == 'date') {
      state.routers_by_date = state.routers_by_date.filter((el) => {
          return el.router_num == state.search_router;
      });
    } else if (payload == 'router'){
      state.routers_by_router_num = state.routers_by_router_num.filter((el) => {
          return el.router_num == state.search_router;
      });
    }
};

/**
 * Filter Routers by Part Number
 * @param state | vuex state
 * @param payload | String 'flow', 'date', 'router'
 * @return void
 */
export const searchByPn = (state, payload) => {
    state.part_num_search_total = 0;
    if(payload == 'flow'){
      state.routers = state.routers.filter((el) => {
          return el.part_num == state.search_pn_num;
      });
    } else if (payload == 'date'){
      state.routers_by_date = state.routers_by_date.filter((el) => {
          return el.part_num == state.search_pn_num;
      });
    } else if (payload == 'router'){
      state.routers_by_router_num = state.routers_by_router_num.filter((el) => {
          return el.part_num == state.search_pn_num;
      });
      state.routers_by_router_num.forEach((el) => {
        if(el.status == 'NIP' || el.status == 'IP'){
          state.part_num_search_total += el.qty;
        }else if(el.status == 'II'){
          state.part_num_search_total += el.stock_qty;
        }
      });
    }
};

/**
 * Filter Routers by Part Number that status being 'II'
 * @param state | vuex state
 * @return void
 */
export const searchByPnII = state => {
    state.routers = state.routers.filter((el) => {
        return el.part_num == state.search_pn_num && el.status == 'II';
    }).stableSort((a,b) => {
        return new Date(a.date_in_inv) - new Date(b.date_in_inv);
    });
};

/**
 * Filter Routers by Status
 * @param state | vuex state
 * @param payload | String
 * @return void
 */
export const searchBySt = (state, payload) => {
    state.search_pn_total = 0;
    if(payload == 'By Status'){
        return
    }
    state.routers_by_router_num = state.routers_by_router_num.filter((el) => {
        return el.status == payload;
    });
};

/**
 * Update the search router state
 * @param state | vuex state
 * @param payload | String
 * @return void
 */
export const updateSearchRouter = (state, payload) => {
  state.search_router = payload;
};

/**
 * Update the search router number state
 * @param state | vuex state
 * @param payload | String
 * @return void
 */
export const updateSearchPnNum = (state, payload) => {
  state.search_pn_num = payload;
};

/**
 * Update the search router number state
 * @param state | vuex state
 * @return void
 */
export const updateSearchPnTotal = state => {
  state.search_pn_total = 0;
};

/**
 * Get search by part number total for inventory
 *
 * @param state | vuex state
 * @return void
 */
 export const getSearchPnTotal = state => {
    state.search_pn_total = 0;
    state.routers_by_date.forEach((el) => {
      if(el.part_num == state.search_pn_num){
        state.search_pn_total += el.stock_qty;
      } 
    });
 };
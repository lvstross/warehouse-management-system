import Utils from '../../../modules/utils.js';

/**
 * Update the edit
 *
 * @param state | vuex state
 * @param payload | Boolean
 * @return void
 */
export const updateEdit = (state, payload) => {
    state.edit = payload;
};

/**
 * Update the read
 *
 * @param state | vuex state
 * @param payload | Boolean
 * @return void
 */
export const updateRead = (state, payload) => {
    state.read = payload;
};

/**
 * Update the pnset
 *
 * @param state | vuex state
 * @param payload | Boolean
 * @return void
 */
export const updatePnSet = (state, payload) => {
    state.pnSet = payload;
};

/**
 * Update the shipticket part number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateShipTicPartNum = (state, payload) => {
    state.shipticket.part_num = payload;
};

/**
 * update the shipticket po number
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateShipTicPoNum = (state, payload) => {
    state.shipticket.po_num = payload;
};

/**
 * update the ship ticket qty
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateShipTicQty = (state, payload) => {
    state.shipticket.qty = payload;
};

/**
 * update the ship ticket customer
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateShipTicCust = (state, payload) => {
    state.shipticket.customer = payload;
};

/**
 * update the ship ticket status
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateShipTicStatus = (state, payload) => {
    state.shipticket.status = payload;
};

/**
 * update the ship ticket customer requirements
 *
 * @param state | vuex state
 * @param payload | payload from commit
 * @return void
 */
export const updateShipTicCustReq = (state, payload) => {
    state.shipticket.cust_req = payload;
};

/**
 * update the ship ticket routers
 *
 * @param state | vuex state
 * @param payload | Array of Objects [{}]
 * @return void
 */
export const updateShipTicRouters = (state, payload) => {
    state.shipticket.routers.push(payload);
};

/**
 * update the ship ticket trip_count
 *
 * @param state | vuex state
 * @param payload | String
 * @return void
 */
export const updateShipTicTripCount = (state, payload) => {
    state.shipticket.trip_count = payload;
};

/**
 * remove the shipticket routers
 *
 * @param state | vuex state
 * @return void
 */
export const removeShipTicRouters = state => {
    state.shipticket.routers = [];
};

/**
 * push to boxes array
 *
 * @param state | vuex state
 * @param payload | Object { router_num: String , cure_qtr: String , qty: Number , wt: String , dim: String }
 * @return void
 */
export const pushToShipTicBoxes = (state, payload) => {
    state.shipticket.boxes.push(payload);
};

/**
 * remove a box from boxes array
 *
 * @param state | vuex state
 * @param payload | Number: index number
 * @return void
 */
export const removeShipTicBox = (state, payload) => {
    state.shipticket.boxes.splice(payload, 1);
};

/**
 * push to log array
 *
 * @param state | vuex state
 * @param payload | Object { item: }
 * @return void
 */
export const pushToShipTicLog = (state, payload) => {
    state.shipticket.log.push(payload);
};

/**
 * push to log array
 *
 * @param state | vuex state
 * @param payload | Number: index number
 * @return void
 */
export const removeShipTicLogItem = (state, payload) => {
    state.shipticket.log.splice(payload, 1);
};

/**
 * push to log array
 *
 * @param state | vuex state
 * @param payload | Number: index number
 * @return void
 */
export const removeShipTicLogAll = state => {
    state.shipticket.log = [];
};

/**
 * Reset the state back to its original values
 * @param state | vuex state
 * @return void
 */
export const resetState = state => {
    state.shipticket = {
        part_num: '',
        po_num: '',
        qty: '',
        customer: '',
        status: 'Unapproved',
        cust_req: '',
        routers: [],
        boxes: [],
        log: [],
        date: Utils.getDateStr(),
        trip_count: 'no'
    }
};

/**
 * Sets the ship ticket data to the router model for updating
 *
 * @param state | vuex state
 * @param payload | payload
 * @return void
 */
export const setShipTicData = (state, payload) => {
    for(var key in payload){
      if(key === 'log' || key === 'boxes' || key === 'routers'){
          state.shipticket[key] = JSON.parse(payload[key]);
      }else{
        state.shipticket[key] = payload[key];
      }
    }
};

/**
 * Sets the ship tickets list
 *
 * @param state | vuex state
 * @param payload | payload
 * @return void
 */
export const setShipTicList = (state, payload) => {
    state.shiptickets = payload;
};
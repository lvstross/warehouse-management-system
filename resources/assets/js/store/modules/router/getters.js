/**
 * Get the routers from the vuex state
 *
 * @param state | Vuex state
 * @return array
 */
export const getRouters = state => state.routers

/**
 * Get the routers from the vuex state sorted by date
 *
 * @param state | Vuex state
 * @return array
 */
export const getRoutersByDate = state => state.routers_by_date

/**
 * Get the routers from the vuex state sorted by router number
 *
 * @param state | Vuex state
 * @return array
 */
export const getRoutersByRouterNum = state => state.routers_by_router_num

/**
 * Get the loading status
 *
 * @param state | Vuex state
 * @return array
 */
export const getLoad = state => state.loading

/**
 * Get the 'II' status routers from the vuex state
 *
 * @param state | Vuex state
 * @return array
 */
export const getInventory = state => {
    return state.routers_by_date.filter((el) => {
        return el.status == 'II';
    }).sort((a,b) => {
        return new Date(b.date_in_inv) - new Date(a.date_in_inv);
    });
}

/**
 * Get the routers from the vuex state that have no
 * dept_name and have a status of 'IP' which would cause
 * them to not show up on the partflow. This values is then used to 
 * show another department container to through these routers into.
 *
 * @param state | Vuex state
 * @return array
 */
export const getMissedRouters = state => state.missed_routers

/**
 * Get the routers total for report mode
 *
 * @param state | Vuex state
 * @return number
 */
export const getRouteTotal = state => state.router_total

/**
 * Get the routers total for the router search in the routers area
 *
 * @param state | Vuex state
 * @return number
 */
export const getRouterSearchTotal = state => state.part_num_search_total

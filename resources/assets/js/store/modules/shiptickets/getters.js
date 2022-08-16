/**
 * Get ship tickets
 *
 * @param state | Vuex state
 * @return array
 */
export const getShipTickets = state => state.shiptickets

/**
 * Get the pnSet value
 *
 * @param state | Vuex state
 * @return Boolean
 */
export const getPnSet = state => state.pnSet

/**
 * Get the edit value
 *
 * @param state | Vuex state
 * @return Boolean
 */
export const getEdit = state => state.edit

/**
 * Get the read value
 *
 * @param state | Vuex state
 * @return Boolean
 */
export const getRead = state => state.read
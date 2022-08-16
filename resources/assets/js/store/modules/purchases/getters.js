/**
 * Gets the purchases list of open orders
 *
 * @param state
 * @return Array
 */
export const getPurchasesOpen = state => state.purchases_open

/**
 * Gets the purchases list of closed orders
 *
 * @param state
 * @return Array
 */
export const getPurchasesClosed = state => state.purchases_closed

/**
 * Get the purchases object
 * @param state
 * @return Object
 */
export const getPurchaseObject = state => state.purchase
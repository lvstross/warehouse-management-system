/**
 * Get the invoices from the vuex state
 *
 * @param state | Vuex state
 * @return array 
 */
export const getInvoices = state => state.invoices

/**
 * Get the invoices total for report mode
 *
 * @param state | Vuex state
 * @return number
 */
export const getInvTotal = state => state.inv_total
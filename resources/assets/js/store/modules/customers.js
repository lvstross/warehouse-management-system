/**
 * @const customers state | object
 */
const state = {
    customers: []
};

/**
 * @const customers getters | object
 */
const getters = {
    /**
     * Gets the customer list
     *
     * @param state
     * @return Array
     */
    getCustomers: (state) => {
        return state.customers;
    }
};

/**
 * @const Customers Mutations | object
 */
const mutations = {
    /**
     * Mutations for setting the state with the array of customers
     *
     * @param state | vuex state
     * @param payload | payload from dispatcher
     */
    setCustomers: (state, payload) => {
        state.customers = payload;
    }
};

/**
 * @const Customers Actions | object
 */
const actions = {
    /**
     * Gets customers data to be commited to mutator
     *
     * @param commit | object
     * @return void
     */
    commitCustomers: ({ commit }) => {
        axios({
            method: 'get',
            url: 'api/customers',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setCustomers', response.data);
        }).catch((error) => {
            throw new Error("commit customer failed!!" + error);
        });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
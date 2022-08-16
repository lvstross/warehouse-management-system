/**
 * @const vendors state | object
 */
const state = {
    vendors: []
};

/**
 * @const vendors getters | object
 */
const getters = {
    /**
     * Gets the vendor list
     *
     * @param state
     * @return Array
     */
    getVendors: (state) => {
        return state.vendors;
    }
};

/**
 * @const Vendors Mutations | object
 */
const mutations = {
    /**
     * Mutations for setting the state with the array of vendors
     *
     * @param state | vuex state
     * @param payload | payload from dispatcher
     */
    setVendors: (state, payload) => {
        state.vendors = payload;
    }
};

/**
 * @const Vendors Actions | object
 */
const actions = {
    /**
     * Gets vendors data to be commited to mutator
     *
     * @param commit | object
     * @return void
     */
    commitVendors: ({ commit }) => {
        axios({
            method: 'get',
            url: 'api/vendors',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setVendors', response.data);
        }).catch((error) => {
            throw new Error("commit vendors failed!!" + error);
        });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
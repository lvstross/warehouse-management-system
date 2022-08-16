/**
 * @const product state | object
 */
const state = {
    products: []
};

/**
 * @const products getters | object
 */
const getters = {
    /**
     * Gets the product list
     *
     * @param state
     * @return Array
     */
    getProducts: (state) => {
        return state.products;
    }
};

/**
 * @const Products Mutations | object
 */
const mutations = {
    /**
     * Mutations for setting the state with the array of products
     *
     * @param state | vuex state
     * @param payload | payload from dispatcher
     */
    setProducts: (state, payload) => {
        state.products = payload;
    }
};

/**
 * @const Products Actions | object
 */
const actions = {
    /**
     * Gets products data to be commited to mutator
     *
     * @param commit | object
     * @return void
     */
    commitProducts: ({ commit }) => {
        axios({
            method: 'get',
            url: 'api/products',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setProducts', response.data);
        }).catch((error) => {
            throw new Error("commit products failed!!" + error);
        });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};

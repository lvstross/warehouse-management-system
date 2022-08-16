/**
 * @const users state | object
 */
const state = {
    users: []
};

/**
 * @const users getters | object
 */
const getters = {
    /**
     * Gets the user list
     *
     * @param state
     * @return Array
     */
    getUsers: (state) => {
        return state.users;
    }
};

/**
 * @const Users Mutations | object
 */
const mutations = {
    /**
     * Mutations for setting the state with the array of users
     *
     * @param state | vuex state
     * @param payload | payload from dispatcher
     */
    setUsers: (state, payload) => {
        state.users = payload;
    }
};

/**
 * @const Users Actions | object
 */
const actions = {
    /**
     * Gets Users data to be commited to mutator
     *
     * @param commit | object
     * @return void
     */
    commitUsers: ({ commit }) => {
        axios({
            method: 'get',
            url: 'api/users',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setUsers', response.data);
        }).catch((error) => {
            throw new Error(" commit users faild!!" + error);
        });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
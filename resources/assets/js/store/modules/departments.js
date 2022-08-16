/**
 * @const departments state | object
 */
const state = {
    departments: []
};

/**
 * @const departments getters | object
 */
const getters = {
    /**
     * Gets the department list
     *
     * @param state
     * @return Array
     */
    getDepartments: (state) => {
        return state.departments;
    }
};

/**
 * @const Departments Mutations | object
 */
const mutations = {
    /**
     * Mutations for setting the state with the array of departments
     *
     * @param state | vuex state
     * @param payload | payload from dispatcher
     */
    setDepartments: (state, payload) => {
        state.departments = payload;
        state.departments.sort((a,b) => {
            return a.key - b.key;
        }).forEach((el,i,a) => {
            return a[i].key = i+1;
        });
    }
};

/**
 * @const Departments Actions | object
 */
const actions = {
    /**
     * Gets departments data to be commited to mutator
     *
     * @param commit | object
     * @return void
     */
    commitDepartments: ({ commit }) => {
        axios({
            method: 'get',
            url: 'api/departments',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        }).then((response) => {
            commit('setDepartments', response.data);
        }).catch((error) => {
            throw new Error("commit departments failed!!" + error);
        });
    },

    /**
     * Resort the departments
     *
     * @param state | vuex state
     * @return void
     */
    sortDepartments: (context, payload) => {
        // Sort the departments
        context.state.departments = payload;
        for(let i=0; i < context.state.departments.length; i++){
            context.state.departments[i].key = i+1;
        }
        // Send new department keys
        axios({
            method: 'post',
            url: 'api/departments/sort',
            data: state.departments,
            validateStatus(status) {
                return status >= 200 && status < 300;
            }
        }).then((response) => {
            // console.log(response);
        }).catch((error) => {
            throw new Error('sortDepartments action failed!' + error);
        });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};

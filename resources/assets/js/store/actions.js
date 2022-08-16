/**
 * Gets the user permission level in order to restrict certain users
 *
 * @param commit | object
 * @return void
 */
export const commitPermission = ({ commit }) => {
    axios({
        method: 'get',
        url: 'api/user',
        validateStatus(status) {
            if(status >= 200 && status < 300){
                return status;
            }else if(status == 401){
                alert("Your session has timed out. You will now be redirected.");
                window.location = window.location.origin + '/login';
            }
        }
    }).then((response) => {
        commit('setPermission', response.data);
    }).catch((error) => {
        throw new Error(" commit permission failed!" + error);
    });
};
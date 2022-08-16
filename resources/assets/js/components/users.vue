<template>
    <div>
        <Loader v-show="loading"></Loader>

        <transition name="fade">
            <div v-show="!loading">
                <!-- User Table -->
                <div id="users-table" v-if="list.length > 1" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Permissions</th>
                                <th>Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="user.id == 1" v-for="users in list">
                                <td>{{ users.name }}</td>
                                <td>{{ users.email }}</td>
                                <td>{{ users.permission }}</td>
                                <td><button @click="showUser(users.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="users.id != 1"><button @click="deleteUser(users.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                            <tr v-else>
                                <td v-if="users.id != 1">{{ users.name }}</td>
                                <td v-if="users.id != 1">{{ users.email }}</td>
                                <td v-if="users.id != 1">{{ users.permission }}</td>
                                <td v-if="users.id != 1"><button @click="showUser(users.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="users.id != 1"><button @click="deleteUser(users.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <p class="alert alert-info text-center">You currently have no users to show.</p>
                </div>
                <!-- end of users table -->
                <hr>
                <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
                <SuccessMessage :successMes="successMessage"></SuccessMessage>
                <div v-show="edit" class="pull-right">
                    <button type="button" @click="cancelEdit" class="btn btn-default">X</button>
                </div>
                <!-- Add User Form -->
                <div>
                    <h2 class="text-center">Add User</h2>
                    <form action="#" @submit.prevent="edit ? updateUser(users.id) : createUser()">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input v-model="users.name" type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input v-model="users.email" type="email" name="email" class="form-control" required>
                        </div>
                        <div v-show="!edit" class="form-group">
                            <label for="password">Password</label>
                            <input v-model="users.password" type="password" name="password" class="form-control">
                        </div>
                        <div v-show="users.id != 1" class="form-group">
                            <label>Permissions</label>
                            <select v-model="users.permission" class="form-control" required>
                                <option>Choose an option</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>
                        <submitBtns :editMode="edit" :name="name='User'"></submitBtns>
                    </form>
                </div>
                <!-- End of add user form -->
                <Instructions></Instructions>
            </div>
        </transition>
    </div>
</template>

<script>
    // Imports
    import SubmitBtns from '../components/partials/submit-btn.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Instructions from '../components/info-components/users-inst.vue';
    import Loader from '../components/partials/loader.vue';
    export default {
        data() {
            return {
                edit: false,
                instruction: false,
                loading: true,
                users: {
                    id: '',
                    name: '',
                    email: '',
                    permission: ''
                },
                errorMessage: '',
                successMessage: ''
            }
        },
        mounted() {
            this.getUser();
            this.getUsers();
            this.loadIn();
        },
        components: {
            SubmitBtns,
            ErrorMessage,
            SuccessMessage,
            Loader,
            Instructions
        },
        computed: {
            user() { 
                return this.$store.getters.getUser; 
            },
            list() { 
                return this.$store.getters.getUsers; 
            }
        },
        methods: {
            loadIn(){
                setTimeout(()=>{
                    this.loading = false;
                }, 1000)
            },
            getUser(){ 
                this.$store.dispatch('commitPermission'); 
            },
            getUsers(){ 
                this.$store.dispatch('commitUsers'); 
            },
            createUser(){
                axios({
                    method: 'post',
                    url: 'api/users/store',
                    data: this.users,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then(() => {
                    this.reset();
                    this.message("User has successfully been created!", 'success', 5000);
                }).catch( error => {
                    this.message("Sorry! Something went wrong when adding your user!", 'error', 10000);
                    throw new Error("Create User Failed! " + error.message);
                });
            },
            updateUser(id){
                axios({
                    method: 'patch',
                    url: 'api/users/' + id,
                    data: this.users,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then(() => {
                    this.reset();
                    this.message("User has successfully been updated!", "success", 5000);
                }).catch( error => {
                    this.message("Sorry! Something went wrong when updating your user!", 'error', 10000)
                    throw new Error("Update User Failed! " + error.message);
                });
            },
            showUser(id){
                axios({
                    method: 'get',
                    url: 'api/users/' + id,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then( response => {
                    for (var key in this.users){
                        this.users[key] = response.data[key];
                    }
                }).catch( error => {
                    this.message("Sorry! Something went wrong when retrieving your user!", "error", 10000);
                    throw new Error("Show User Failed! " + error.message);
                });
                this.edit = true;
            },
            deleteUser(id){
                if(id == 1){
                    alert('Sorry!! You are not allowed to delete the admin user.');
                }else{
                    if(confirm('Are you sure you want to delete this user?')){
                        axios({
                            method: 'delete',
                            url: 'api/users/' + id,
                            validateStatus(status) {
                                if(status >= 200 && status < 300){
                                    return status;
                                }else if(status == 401){
                                    alert("Your session has timed out. You will now be redirected.");
                                    window.location = window.location.origin + '/login';
                                }
                            }
                        }).then((response) => {
                            this.getUsers();
                            this.message("User has successfully been deleted!", 'success', 5000);
                        }).catch( error => {
                            this.message("Sorry! Something went wrong when updating you user!", 'error', 10000);
                            throw new Error("Delete User Failed! " + error.message);
                        });
                    }else{
                        return;
                    }
                }
            },
            reset(){
                for(var key in this.users){
                    this.users[key] = '';
                }
                this.edit = false;
                this.getUsers();
            },
            cancelEdit(){
                for(var key in this.users){
                    this.users[key] = '';
                }
                this.edit = false;
            },
            message(message, setting="success", timing){
                if(setting == 'success'){
                    this.successMessage = message;
                    setTimeout(()=>{
                        this.successMessage = '';
                    }, timing);
                } else if (setting == 'error'){
                    this.errorMessage = message;
                    setTimeout(()=>{
                        this.errorMessage = '';
                    }, timing);
                }
            }
        }
    }
</script>
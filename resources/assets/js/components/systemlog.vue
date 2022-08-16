<template>
    <div>
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <select ref="bymonth" @change="getByMonth" class="form-control">
                        <option value="na">How many months ago?</option>
                        <option v-for="n in 36">{{ n }}</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <input ref="bydate" class="form-control" @blur="getByDate" type="date" name="date">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p v-if="last" class="text-center months-ago">Last {{lastXMonths}} month(s)</p>
                <p v-else class="text-center months-ago">On {{newDate(lastXMonths)}}</p>
            </div>
        </div>
        <!-- Error and Success Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <Loader v-show="loading"></Loader>
        <transition name="fade">
            <div v-if="!loading">
                <div id="log-table" v-if="list.length > 0" class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Time / Date</th>
                                <th>Log Entry</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="log in list">
                                <td>{{ log.time }} / {{ newDate(log.date) }}</td>
                                <td>{{ log.logs }}</td>
                                <td v-if="user.permission == 1"><button @click="deleteLogItem(log.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else> 
                    <p class="alert alert-info text-center">You currently have no log entries to show.</p>
                </div>
                <!-- end of products table -->
            </div>
        </transition>
    </div>
</template>
<script>
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Loader from '../components/partials/loader.vue';
    import Utils from '../modules/utils.js';
    export default {
        data(){
            return {
                list: [],
                last: true,
                loading: true,
                successMessage: '',
                errorMessage: '',
                lastXMonths: 3
            }
        },
        mounted(){
            this.getUser();
            this.getSystemLog();
            this.interval = setInterval(()=>{
                this.getSystemLog();
            }, 10000);
        },
        computed: {
            user() { return this.$store.getters.getUser; },
        },
        components: {
            ErrorMessage,
            SuccessMessage,
            Loader
        },
        methods: {
            getUser(){ this.$store.dispatch('commitPermission'); },
            orderByDesc() {
                this.list.sort((a,b) => {
                    return b.date - a.date;
                });
            },
            orderByAsc() {
                this.list.sort((a,b) => {
                    return a.date - b.date;
                });
            },
            getByMonth(e){
                clearInterval(this.interval);
                if(e.target.value != 'na'){
                    this.loading = true;
                    axios({
                        method: 'get',
                        url: 'api/systemlog/month/' + e.target.value,
                        validateStatus(status) {
                            if(status >= 200 && status < 300){
                                return status;
                            }else if(status == 401){
                                alert("Your session has timed out. You will now be redirected.");
                                window.location = window.location.origin + '/login';
                            }
                        }
                    }).then((response) => {
                        this.$refs.bydate.value = '';
                        this.last = true;
                        this.lastXMonths = e.target.value;
                        this.list = response.data;
                        this.loading = false;
                    }).catch((error) => {
                        throw new Error("get by month failed!!" + error);
                    });
                }
            },
            getByDate(e){
                clearInterval(this.interval);
                if(e.target.value != ''){
                    this.loading = true;
                    axios({
                        method: 'get',
                        url: 'api/systemlog/date/' + e.target.value,
                        validateStatus(status) {
                            if(status >= 200 && status < 300){
                                return status;
                            }else if(status == 401){
                                alert("Your session has timed out. You will now be redirected.");
                                window.location = window.location.origin + '/login';
                            }
                        }
                    }).then((response) => {
                        this.$refs.bymonth.value = 'na';
                        this.last = false;
                        this.lastXMonths = e.target.value;
                        this.list = response.data;
                        this.loading = false;
                    }).catch((error) => {
                        throw new Error("get by month failed!!" + error);
                    });
                }
            },
            getSystemLog() {
                axios({
                    method: 'get',
                    url: 'api/systemlog',
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    if(response.data.length != this.list.length){
                        this.list = response.data;
                        console.log('List updated.');
                        this.loading = false;
                    }
                }).catch((error) => {
                    throw new Error("get system log failed!!" + error);
                });
            },
            getSystemLogAll() {
                this.loading = true;
                axios({
                    method: 'get',
                    url: 'api/systemlog/all',
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    this.list = response.data;
                    this.loading = false;
                }).catch((error) => {
                    throw new Error("get system log failed!!" + error);
                });
            },
            deleteLogItem(id){
                if(confirm('Are you sure you want to delete this log entry?')){
                    axios({
                        method: 'delete',
                        url: 'api/systemlog/' + id,
                        validateStatus(status) {
                            if(status >= 200 && status < 300){
                                return status;
                            }else if(status == 401){
                                alert("Your session has timed out. You will now be redirected.");
                                window.location = window.location.origin + '/login';
                            }
                        }
                    }).then((response) => {
                        this.getSystemLog();
                        this.message("System log entry has successfully been deleted!", 'success', 5000);
                    }).catch((error) => {
                        this.message("Sorry! Something went wrong when deleting your system log entry", 'error', 10000);
                        throw new Error("Delete System log Failed! " + error.message);
                    });
                }else{
                    return;
                }
            },
            newDate(date){
                return Utils.invertDate(date);
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
<style scoped>
    #log-table {
        box-shadow: 1px 2px 11px #aaa;
        border-radius: 3px;
        max-height: 700px;
        overflow: scroll;
    }
    .months-ago {
        margin-bottom: 0;
        line-height: 40px;
    }
</style>
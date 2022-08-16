<template>
    <div>
        <h1 class="text-center">Departments</h1>
        <hr>
        <!-- Error and Success Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- Start of Departments Table -->
        <Loader v-show="loading"></Loader>
        <transition name="fade">
            <div v-show="!loading">
                <p class="text-center">Sorted By: Drag &amp; Drop</p>
                <div id="department-table" v-if="list.length > 0" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Sort</th>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Background Color</th>
                                <th>Text Color</th>
                                <th>View</th>
                                <th v-if="user.permission == 1 || user.permission == 2 || user.permission == 3">Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <Draggable
                            v-model="list"
                            :options="{group:'departments'}"
                            :element="'tbody'"
                            class="cursor-move"
                            >
                            <tr v-for="dept in list" :key="dept.key" class="move-shadow">
                                <td><i class="fa fa-bars fa-2x" aria-hidden="true"></i></td>
                                <td>{{ dept.key }}</td>
                                <td>{{ dept.dept_name }}</td>
                                <td>{{ dept.dept_bg_color }}</td>
                                <td>{{ dept.dept_txt_color }}</td>
                                <td><button @click="viewDepartment(dept.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td v-if="user.permission == 1 || user.permission == 2 || user.permission == 3"><button @click="showDepartment(dept.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1"><button @click="deleteDepartment(dept.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </Draggable>
                    </table>
                </div>
                <div v-else>
                    <p class="alert alert-info text-center">You currently have no routers to show.</p>
                </div>
            </div>
        </transition>
        <!-- End of Departments table -->
        <!-- Start of Viewing Area -->
        <transition name="fade">
            <div v-show="read">
                <div class="well" :style="read_text">
                    <h2 class="text-center lg-font underline">{{department.dept_name}}</h2>
                    <p class="text-center">Sample text to see your color.</p>
                    <button @click="closeView" class="btn btn-danger full-width">Close Viewing</button>
                </div>
            </div>
        </transition>
        <!-- End of Viewing Area -->
        <!-- Start Departments Form -->
        <transition name="fade">
            <div v-show="!read && !loading">
                <h2 class="text-center">Department Details</h2>
                <form action="#" @submit.prevent="edit ? updateDepartment(department.id) : createDepartment()">
                    <div class="form-group">
                      <label for="dept_name">Department Name</label>
                      <input v-model="department.dept_name" class="form-control" type="text" name="dept_name" maxlength="50" required>
                    </div>
                    <div class="form-group">
                      <label for="dept_bg_color">Background Color:</label>
                      <input ref="bg_color" v-model="department.dept_bg_color" type="text" @change="setBgColor()" class="jscolor form-control" name="dept_bg_color">
                    </div>
                    <div class="form-group">
                      <label for="dept_txt_color">Text Color:</label>
                      <input ref="txt_color" v-model="department.dept_txt_color" type="text" @change="setTxtColor()" class="jscolor form-control" name="dept_txt_color">
                    </div>
                    <SubmitBtn :editMode="edit" :name="'Department'"></SubmitBtn>
                </form>
                <br />
            </div>
        </transition>
        <!-- End Departments Form -->
        <Instructions></Instructions>
    </div>
</template>
<script>
    import TextForm from '../../components/partials/form-text.vue';
    import ErrorMessage from '../../components/partials/error-message.vue';
    import SuccessMessage from '../../components/partials/success-message.vue';
    import Loader from '../../components/partials/loader.vue';
    import SubmitBtn from '../../components/partials/submit-btn.vue';
    import Instructions from '../../components/info-components/departments-inst.vue';
    import Draggable from 'vuedraggable';
    import Utils from '../../modules/utils.js';
    export default {
        data(){
            return {
                read: false,
                edit: false,
                loading: true,
                table: true,
                department: {
                  id: '',
                  dept_name: '',
                  dept_bg_color: 'D6D6D6',
                  dept_txt_color: '000000',
                  key: ''
                },
                successMessage: '',
                errorMessage: '',
                read_text: 'background: #aaa; color: #fff;',
            }
        },
        mounted() {
            this.loadIn();
        },
        components: {
            TextForm,
            ErrorMessage,
            SuccessMessage,
            SubmitBtn,
            Draggable,
            Loader,
            Instructions
        },
        computed: {
          user() { return this.$store.getters.getUser; },
          list: {
            get() {
              return this.$store.getters.getDepartments;
            },
            set(value) {
              this.$store.dispatch('sortDepartments', value);
            }
          },
        },
        methods: {
            loadIn(){
                setTimeout(()=> {
                    this.loading = false;
                }, 1500)
            },
            getDepartments(){ this.$store.dispatch('commitDepartments'); },
            setBgColor(){
                this.department.dept_bg_color = Utils.rgb2hex(this.$refs.bg_color.style.backgroundColor);
            },
            setTxtColor(){
                this.department.dept_txt_color = Utils.rgb2hex(this.$refs.txt_color.style.backgroundColor);
            },
            createDepartment(){
                console.log(this.department);
                let params = Object.assign({}, this.department);
                axios({
                    method: 'post',
                    url: 'api/departments/store',
                    data: params,
                    validateStatus(status) {
                        return status >= 200 && status < 300;
                    }
                }).then(response => {
                    this.resetValues();
                    this.message("Department has successfully been created!", 'success', 5000);
                }).catch(error => {
                    this.message("Sorry! Something went wrong when creating your department.", 'error', 10000);
                    throw new Error("Create Department Failed! " + error.message);
                });
            },
            updateDepartment(id){
                let params = Object.assign({}, this.department);
                axios({
                    method: 'patch',
                    url: 'api/departments/' + id,
                    data: params,
                    validateStatus(status) {
                        return status >= 200 && status < 300;
                    }
                }).then(() => {
                    this.resetValues();
                    this.message("Department has successfully been updated!", 'success', 5000);
                }).catch((error) => {
                    this.message("Sorry! Something went wrong when updating your department.", 'error', 10000);
                    throw new Error("Update Department Failed! " + error.message);
                });
            },
            showDepartment(id){
                this.read = false;
                axios({
                    method: 'get',
                    url: 'api/departments/' + id,
                    validateStatus(status) {
                        return status >= 200 && status < 300;
                    }
                }).then((response) => {
                    for(var key in this.department){
                        for(var k in response.data){
                            if(key === k){
                                this.department[key] = response.data[k];
                            }
                        }
                    }
                    this.$refs.bg_color.style.backgroundColor = '#' + this.department.dept_bg_color;
                    this.$refs.txt_color.style.backgroundColor = '#' + this.department.dept_txt_color;
                }).catch((error) => {
                    this.message("Sorry! Something went wrong retrieving your department.", 'error', 10000);
                    throw new Error("Show Department Failed! " + error.message);
                });
                this.edit = true;
            },
            viewDepartment(id) {
                this.edit = false;
                axios({
                    method: 'get',
                    url: 'api/departments/' + id,
                    validateStatus(status) {
                        return status >= 200 && status < 300;
                    }
                }).then((response) => {
                    for(var key in this.department){
                        for(var k in response.data){
                            if(key === k){
                                this.department[key] = response.data[k];
                            }
                        }
                    }
                    this.read_text = "background: #" + this.department.dept_bg_color +"; color: #" + this.department.dept_txt_color +";";
                }).catch((error) => {
                    this.message("Sorry! Something went wrong when retrieving your product.", 'error', 10000);
                    throw new Error("View Product Failed! " + error.message);
                });
                this.read = true;
            },
            closeView() {
                this.resetValues();
                this.read = false;
            },
            deleteDepartment(id){
                if(confirm('Are you sure you want to delete this department?')){
                    axios.delete('api/departments/' + id)
                    .then((response) => {
                        this.getDepartments();
                        this.message("Department has successfully been deleted!", 'success', 5000);
                    }).catch((error) => {
                        this.message("Sorry! Something went wrong when deleting your department.", 'error', 10000);
                        throw new Error("Delete Department Failed! " + error.message);
                    });
                }else{
                    return;
                }
            },
            resetValues(){
                this.department = {
                  id: '',
                  dept_name: '',
                  dept_bg_color: 'D6D6D6',
                  dept_txt_color: '000000',
                  key: ''
                };
                this.$refs.bg_color.style.backgroundColor = '#' + this.department.dept_bg_color;
                this.$refs.txt_color.style.backgroundColor = '#' + this.department.dept_txt_color;
                this.getDepartments();
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
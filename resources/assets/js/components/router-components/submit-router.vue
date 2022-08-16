<template>
    <div>
        <h1 class="text-center">Employee Submit Form</h1>
        <p v-if="message.length > 0" class="alert alert-success text-center">{{ message }}</p>
        <p v-if="error.length > 0" class="alert alert-danger text-center">{{ error }}</p>
        <form action="#" @submit.prevent="">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="empl_name">Employee Name</label>
                        <input v-model="router.empl_name" class="form-control" type="text" name="empl_name" maxlength="50">
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="router_id">Router Number</label>
                        <select v-model="router.router_id" class="form-control" name="router_id">
                            <option>Choose An Option</option>
                            <option v-for="item in routers" :value="item.id">{{ item.router_num }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="dept_name">Department this order is moving to</label>
                        <select v-model="router.dept_name" class="form-control" name="dept_name">
                            <option>Choose An Option</option>
                            <option v-for="items in departments">{{ items.dept_name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <button @click="submitRouter" class="btn btn-primary full-width">Submit</button>
        </form>
    </div>
</template>
<script>
    export default{
        data(){
            return {
                router: {
                    empl_name: '',
                    router_id: 'Choose An Option',
                    dept_name: 'Choose An Option'
                },
                message: '',
                error: ''
            }
        },
        computed: {
            routers() { return this.$store.getters.getRoutersByRouterNum; },
            departments() { return this.$store.getters.getDepartments; }
        },
        methods: {
            submitRouter() {
                console.log(this.router);
                if(this.router.empl_name == '' || this.router.router_id == 'Choose An Option' || this.router.dept_name == 'Choose An Option'){
                    alert('Some fields are empty.');
                    return;
                }
                let params = Object.assign({}, this.router);
                axios({
                    method: 'post',
                    url: 'api/routers/department',
                    data: params,
                    validateStatus(status) {
                        return status >= 200 && status < 300;
                    }
                }).then((response) => {
                    this.router = {
                        empl_name: '',
                        router_id: 'Choose An Option',
                        dept_name: 'Choose An Option'
                    }
                    this.message = response.data.message;
                    setTimeout(()=>{
                        this.message = '';
                    }, 5000);
                }).catch((error) => {
                    this.error = 'Sorry! Something went wrong. Please notify a manger.';
                    setTimeout(()=>{
                        this.error = '';
                    }, 10000);
                    throw new Error('updateRouter failed! ' + error);
                });
            }
        }
    }
</script>
<template>
    <div>
      <h2 class="text-center">Router Details</h2>
      <form action="#" @submit.prevent="edit ? updateRouter(routerObj.id) : createRouter()">
        <!-- Start of Normal Router Row -->
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <TextForm :dataModel="routerObj.router_num.toString()" :update="updateRouterNum" :forVal="'router_num'" :inputName="'Router Number'" :inputClass="'form-control'"></TextForm>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <SelectForm :dataModel="routerObj.part_num" :update="updatePartNum" :forVal="'part_num'" :inputName="'Part Number'" :inputClass="'form-control'" :list="products"></SelectForm>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <NumberForm :dataModel="routerObj.qty" :update="updateQty" :forVal="'qty'" :inputName="'Qty'":inputClass="'form-control'"></NumberForm>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <DateForm :dataModel="routerObj.date" :update="updateDate" :forVal="'date'" :inputName="'Date'" :inputClass="'form-control'" :req="true"></DateForm>
          </div>
        </div>
        <!-- End of Normal Router Row -->
        <!-- Hidden Edit Router Row -->
        <div class="flex-center">
          <span v-if="edit && (user.permission == 1 || user.permission == 2)" class="underline text-center cursor-pointer" @click="showAdvanced()">Advanced Options</span>
        </div>
        <div class="row" v-show="edit && advanced">
          <div class="col-xs-12">
            <p class="alert alert-warning text-center">Warning: The following items should be edited with caution as they represent data that is captured in the part flow.</p>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <DateForm :dataModel="routerObj.date_in_inv" :update="updateDateII" :forVal="'date_in_inv'" :inputName="'Date In Inventory'" :inputClass="'form-control'" :req="false"></DateForm>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <NumberForm :dataModel="routerObj.stock_qty" :update="updateStockQty" :forVal="'stock_qty'" :inputName="'Stock Qty'":inputClass="'form-control'"></NumberForm>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4">
            <div class="form-group">
              <label>Status</label>
              <select v-model="routerObj.status" @blur="updateStatus" class="form-control">
                <option value="CAI">Choose An Item</option>
                <option v-for="item in statusList" :value="item.value">{{ item.name }}</option>
              </select>
            </div>
          </div>
          <div v-if="routerObj.status == 'IP'" class="col-xs-12 col-sm-6 col-lg-4">
            <div class="form-group">
              <label>Department</label>
              <select v-model="routerObj.dept_name" @blur="updateDeptName" class="form-control">
                <option value="">Choose An Item</option>
                <option v-for="item in deptList">{{ item.dept_name }}</option>
              </select>
            </div>
          </div>
          <div v-else class="col-xs-12 col-sm-6 col-lg-4">
            <p class="alert alert-info full-width text-center">Department form disabled will router status is not<br>"In Production".</p>
          </div>
          <div class="col-xs-12">
            <!-- Move Log Section -->
            <div class="row">
              <div class="col-xs-12 flex-center">
                <span @click="moveLogShow == false ? moveLogShow = true : moveLogShow = false" class="underline text-center cursor-pointer">Show Move Log</span>
              </div>
              <div v-show="moveLogShow">
                <div class="col-xs-12">
                  <h3 class="text-center">Move Log</h3>
                </div>
                <div class="row logcon">
                  <div class="col-xs-12 col-sm-10">
                    <div class="form-group">
                      <label for="move_log_add">Add Move Log Item</label>
                      <input type="text" name="move_log_add" v-model="move_log_add" class="form-control">
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-2">
                    <button class="btn btn-success logbtn" type="button" @click="addLogItem()"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <hr class="dashed">
                  </div>
                </div>
                <div v-if="routerObj.move_log.length > 0" class="row logcon" v-for="(log_item, index) in routerObj.move_log">
                  <div class="col-xs-12 col-sm-10">
                    <TextFormMoveLog :dataModel="log_item.item" :update="updateMoveLog" :forVal="'move_log_' + index" :index="index" :inputName="'Move Log Entry'" :inputClass="'form-control'"></TextFormMoveLog>
                  </div>
                  <div class="col-xs-12 col-sm-2">
                    <button v-if="user.permission == 1" class="btn btn-danger logbtn" type="button" @click="removeLogItem(index)"><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>
                  </div>
                </div>
                <div v-if="routerObj.move_log.length > 0 && user.permission == 1" class="col-xs-12">
                  <button class="btn btn-danger btn-xs rm-all-btn" @click="removeAllLog" type="button"><i class="fa fa-trash" aria-hidden="true"></i> Remove All Move Log Items <i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>
                <div v-if="routerObj.move_log.length == 0" class="col-xs-12">
                  <p class="alert alert-info text-center">Move Log Empty</p>
                </div>
                <!-- End of Move Log Section -->
              </div>
            </div>
          </div>
        </div>
        <!-- End of hidden edit router row -->
          <SubmitBtn :editMode="edit" :name="'Router'"></SubmitBtn>
      </form>
    </div>
</template>
<script>
    import SelectForm from '../../../components/partials/form-select.vue';
    import SelectFormSearch from '../../../components/partials/form-select-search.vue';
    import TextForm from '../../../components/partials/form-text.vue';
    import TextFormMoveLog from '../../../components/partials/form-text-move-log.vue';
    import NumberForm from '../../../components/partials/form-number.vue';
    import DateForm from '../../../components/partials/form-date.vue';
    import SubmitBtn from '../../../components/partials/submit-btn.vue';
    export default {
        props: {
          edit: Boolean,
          table: Boolean
        },
        mounted() {
            // this.getUser();
            // this.getProducts();
            // this.getDepartments();
            this.$emit('updateRouterFunction', this.updateRouter);
            this.$emit('showRouterFunction', this.showRouter);
            this.$emit('deleteRouterFunction', this.deleteRouter);
            this.$emit('readRouterFunction', this.readRouter);
        },
        components: {
            SelectForm,
            SelectFormSearch,
            TextForm,
            TextFormMoveLog,
            NumberForm,
            DateForm,
            SubmitBtn
        },
        computed: {
            // GETTERS
            user() { return this.$store.getters.getUser; },
            deptList() { return this.$store.getters.getDepartments; },
            products() { return this.$store.getters.getProducts; },
            routerObj() { return this.$store.state.routers.router; }
        },
        data(){
            return {
              statusList: [
                  { name: 'Not In Production', value: 'NIP' },
                  { name: 'In Production', value: 'IP' },
                  { name: 'Staged For Inventory', value: 'STFI' },
                  { name: 'In Inventory', value: 'II' },
                  { name: 'Archive', value: 'A' },
              ],
              advanced: false,
              moveLogShow: false,
              move_log_add: '',
            }
        },
        methods: {
            // MUTATIONS
            updateRouterNum(e) { this.$store.commit('updateRouterNum', e.target.value); },
            updatePartNum(e) { this.$store.commit('updateRouterPartNum', e.target.value); },
            updateQty(e) { this.$store.commit('updateRouterQty', e.target.value); },
            updateStockQty(e) { this.$store.commit('updateRouterStQty', e.target.value); },
            updateStatus(e) { this.$store.commit('updateRouterStatus', e.target.value); },
            updateDeptName(e) { this.$store.commit('updateRouterDeptName', e.target.value); },
            updateMoveLog(e,index) { this.$store.commit('updateRouterMoveLog', {eventVal: e.target.value, logIndex: index}); },
            updateDate(e) { this.$store.commit('updateRouterDate', e); },
            updateDateII(e) { this.$store.commit('updateRouterDateII', e); },
            removeLogItem(index) {this.$store.commit('deleteLogItem', index); },
            removeAllLog() {this.$store.commit('removeAllMoveLog'); },
            addLogItem() { 
              this.$store.commit('createLogItem', this.move_log_add);
              this.move_log_add = '';
            },
            // ACTIONS
            // getUser() { this.$store.dispatch('commitPermission'); },
            // getProducts(){ this.$store.dispatch('commitProducts'); },
            // getDepartments(){ this.$store.dispatch('commitDepartments'); },
            createRouter(){ // post request to add a router
                this.$store.dispatch('createNewRouter')
                .then((resp) => {
                    if(resp == true){
                      this.$emit("errorMessage", {message: "You already have a router with that router number. Please select a new router number.", status: 'error', time: 10000});
                    }else{
                      this.$store.commit('setLoad', true);
                      this.resetValues();
                      this.$emit("successMessage", {message: "Router successfully created!", status: 'success', time: 5000});
                      this.$emit('createEndFalse', false);
                      this.$emit('createEndTrue', true);
                    }
                })
                .catch((error) => {
                    this.$emit("errorMessage", {message: "Sorry! Something went wrong when creating your router", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for createNewRouter');
                });
            },
            updateRouter(id){ // patch request to update a router
                if(this.routerObj.status == 'II'){
                  if(this.routerObj.date_in_inv == '' || this.routerObj.date_in_inv == null){
                    this.$emit("errorMessage", {message: "Date in inventory field is empty. Since you are moving this router to inventory, it requires a date at which it entered into inventory.", status: 'error', time: 15000});
                    return;
                  }
                  if(this.routerObj.stock_qty == 0){
                    this.$emit("errorMessage", {message: "A router can not be submited to inventory with a zero stock quantity. Please give the router a stock quantity.", status: 'error', time: 15000});
                    return;
                  }
                }
                this.$store.dispatch('updateRouter', id)
                .then(() => {
                    this.resetValues();
                    this.advanced = false;
                    this.moveLogShow = false;
                    this.move_log_add = '';
                    this.$emit("ReadEvent", false);
                    this.$emit("FormEvent", false);
                    this.$emit("SearchEvent", false);
                    this.$store.commit('setLoad', false);
                    this.$emit("successMessage", {message: "Router successfully updated!", status: 'success', time: 5000});
                })
                .catch((error) => {
                    this.$emit("errorMessage", {message: "Sorry! Something went wrong when updating your router!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for updateRouter');
                });
            },
            showRouter(id){ // get request to show an router for editing
                this.$store.dispatch('showRouter', {r_id: id, mode: 'edit'}) // dispatch expects either 'edit' or 'view' for the mode parameter
                .then(() => {
                    this.$emit("TableEvent", false);
                    this.$emit("ReadEvent", false);
                    this.$emit("EditEvent", true);
                    this.$emit("FormEvent", true);
                })
                .catch((error) => {
                    this.$emit("errorMessage",{message: "Sorry! Something went wrong when retrieving your router!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for showRouter');
                });
            },
            readRouter(id){ // get request to show an router for viewing
                this.$store.dispatch('showRouter', {r_id: id, mode: 'view'}) // dispatch expects either 'edit' or 'view' for the mode parameter
                .then(() => {
                    this.$emit("ReadEvent", true);
                })
                .catch((error) => {
                    this.$emit("errorMessage",{message: "Sorry! Something went wrong when retrieving your router!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for showRouter');
                });
            },
            deleteRouter(id){
                this.$store.dispatch('deleteRouter', id)
                .then(() => {
                    this.$store.commit('setLoad', true);
                    this.$store.dispatch('commitRouters')
                    .then(()=>{
                      this.$store.commit('setLoad', false);
                      this.$emit("successMessage", {message: "Router successfully deleted!", status: 'success', time: 5000});
                    })
                    .catch((error)=>{
                        this.$emit("errorMessage", {message: "Sorry! Something went wrong when getting your router!", status: 'error', time: 10000});
                        throw new Error('Something went wrong with the dispatch for commitRouter');                      
                    })
                })
                .catch((error) => {
                    this.$emit("errorMessage", {message: "Sorry! Something went wrong when deleting your router!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for deleteRouter');
                })
            },
            resetValues(){
                this.$store.dispatch('commitRouters');
                this.$emit("EditEvent", false);
            },
            showAdvanced(){
                if(this.advanced != true){
                  this.advanced = true;
                }else{
                  this.advanced = false;
                }
            },
        }
    }
</script>
<style scoped>
  .cursor-pointer{
    cursor: pointer;
  }
  .rm-all-btn {
    margin-top: 10px;
    margin-left: 16px;
  }
  .logbtn {
    margin-top: 24px;
    width: 100%;
  }
  .logcon {
    padding: 5px 15px;
    box-sizing: border-box;
    margin-left: 0;
    margin-right: 0;
  }
  @media(max-width: 767px){
    .logbtn {
      margin-top: 0;
      margin-bottom: 10px;
    }
    .logcon {
      border: 1px solid #000;
      padding: 5px 0;
      border-radius: 10px;
      margin-top: 5px;
      margin-bottom: 5px;
    }
  }
</style>

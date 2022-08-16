<template>
    <div>
        <h1 class="text-center">Part Flow</h1>
        <!-- Error and Success Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- Start of Search Froms -->
        <div class="row">
            <!-- Start of Search by Router Number Form -->
            <div class="col-xs-12 col-md-6 col-lg-4">
                <form action="#" @submit.prevent="">
                    <div class="row">
                        <div class="col-xs-8 col-sm-8">
                            <div class="form-group">
                                <input v-model="search_router_num" type="text" name="search" class="form-control" placeholder="Router Number">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4">
                            <div class="form-group">
                                <button @click="searchByRouter(search_router_num)" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <button @click="cancelSearch" v-show="search_router" class="btn btn-danger full-width btn-sm"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
            </div>
            <!-- End of Search by Router Number Form -->
            <!-- Start of Search by Part Number Form -->
            <div class="col-xs-12 col-md-6 col-lg-4">
                <form action="#" @submit.prevent="">
                    <div class="row">
                        <div class="col-xs-8 col-sm-8">
                            <div class="form-group">
                                <input v-model="search_pn_num" type="text" name="search" class="form-control" placeholder="Part Number">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4">
                            <div class="form-group">
                                <button @click="searchByPn(search_pn_num)" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <button @click="cancelSearchPn" v-show="search_pn" class="btn btn-danger full-width btn-sm"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
            </div>
            <!-- End of Search by Part Number Form -->
            <!-- To Departments button -->
            <div class="col-xs-12 col-md-6 col-lg-4">
                <button type="button" @click="toDepartments" class="btn btn-default full-width"><i class="fa fa-wrench" aria-hidden="true"></i> Departments</button>
            </div>
        </div>
        <!-- End of Search Forms -->
        <Loader v-show="loading"></Loader>
        <!-- Start of Departments Table -->
        <!-- ========= Start of Main Column Container ========== -->
        <transition name="fade">
            <div v-show="!loading">
                <p v-if="departments.length == 0" class="alert alert-info text-center">Add a department by clicking the 'Departments' button.</p>
                <p class="text-center">Sorted By: Drag &amp; Drop</p>
                <div id="scroll-container">
                    <div class="dept-main">
                        <!-- Start of 'NIP' Container -->
                        <div class="dept-cont nip-cont" id="NIP">
                            <div>
                                <h4 class="text-center">Not In Production</h4>
                                <Draggable
                                    v-model="list"
                                    class="full-width radius"
                                    :element="'div'"
                                    :options="dragOptions"
                                    :move="onMove"
                                    >
                                    <div id="nip-container" v-for="route in list" :key="route.key" class="full-width">
                                        <RouterContainer
                                            v-if="route.status == 'NIP'"
                                            :edit="showRouter"
                                            :data="route"
                                        ></RouterContainer>
                                    </div>
                                    <div class="router-cont dotted" style="background-color:#a1b9c6;color:#fff;"></div>
                                </Draggable>
                            </div>
                        </div>
                        <!-- End of 'NIP' Container -->

                        <!-- Start of 'IP' Container -->
                        <div class="dept-cont-ip">
                            <h4 class="text-center" style="margin-top: 4px;">In Production</h4>
                            <div class="ip-cont" id="IP">
                                <div v-if="missedRouters.length > 0" class="dept-cont-inner-ip" :style="'background: #db8596; color: #fff;'">
                                    <h5 class="text-center text-justify" style="white-space: normal;">
                                        If you see this red box, it's because one or more of your routers either missed the dropzone 
                                        or was not assigned a department name when the status was manually changed to "In Production". 
                                        Please move routers from here into another container.
                                    </h5>
                                    <!-- Container for those routers that some how miss the target 
                                        drop window but have a change in status and not department. -->
                                    <Draggable
                                        v-model="list"
                                        class="full-width radius"
                                        :element="'div'"
                                        :options="dragOptions"
                                        :move="onMove"
                                        >
                                        <div v-for="route in list" :key="route.key" class="full-width">
                                            <RouterContainer
                                                v-if="route.dept_name == null && route.status == 'IP'"
                                                :edit="showRouter"
                                                :data="route"
                                            ></RouterContainer>
                                        </div>
                                        <div class="router-cont dotted" :style="'background: #db8596; color: #fff;'"></div>
                                    </Draggable>
                                </div>
                                <div :id="dept.dept_name" v-for="dept in departments" class="dept-cont-inner-ip" :style="'background: #'+dept.dept_bg_color+';'+'color: #'+dept.dept_txt_color+';'">
                                    <h5 class="text-center">{{ dept.dept_name }}</h5>
                                    <!-- In production Departmented Routers -->
                                    <Draggable
                                        v-model="list"
                                        class="full-width radius"
                                        :element="'div'"
                                        :options="dragOptions"
                                        :move="onMove"
                                        >
                                        <div v-for="route in list" :key="route.key" class="full-width">
                                            <RouterContainer
                                                v-if="dept.dept_name == route.dept_name && route.status == 'IP'"
                                                :edit="showRouter"
                                                :data="route"
                                            ></RouterContainer>
                                        </div>
                                        <div class="router-cont dotted" :style="'background:#'+dept.dept_bg_color+';'+'color:#'+dept.dept_txt_color+';'"></div>
                                    </Draggable>
                                </div>
                            </div>
                        </div>
                        <!-- Start of 'IP' Container -->

                        <!-- Start of 'STFI' Container -->
                        <div class="dept-cont ii-cont" id="STFI">
                            <h4 class="text-center">Staged For Inventory</h4>
                            <div class="full-width ii-inner-cont">
                            <Draggable
                                v-model="list"
                                class="full-width radius"
                                :element="'div'"
                                :options="dragOptions"
                                :move="onMove"
                                >
                                <div v-for="route in list" :key="route.key" class="full-width">
                                    <RouterContainer
                                        v-if="route.status == 'STFI'"
                                        :edit="showRouter"
                                        :data="route"
                                    ></RouterContainer>
                                </div>
                                <div class="router-cont dotted" style="background:#a1b9c6;color:#fff;"></div>
                            </Draggable>
                            </div>
                            <button @click="submitToII" type="button" class="btn btn-primary full-width">Submit</button>
                        </div>
                        <!-- End of 'II' Container -->
                    </div>
                </div>
            </div>
        </transition>
        <!-- ========= End of Main Column Container ========== -->
        <!-- Start of Modal Container -->
        <div v-show="edit" class="modal-cont">
            <div class="modal-inner-cont">
                <div class="full-width">
                    <button @click="cancelEdit" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                  <RouterForm
                    :edit="edit"
                    @EditEvent="edit = $event"
                    @SearchEvent="cancelAllSearch()"
                    @successMessage="message($event.message, $event.status, $event.time)"
                    @errorMessage="message($event.message, $event.status, $event.time)"
                    @updateRouterFunction="updateRouter = $event"
                    @showRouterFunction="showRouter = $event"
                  ></RouterForm>
            </div>
        </div>
        <!-- End of Modal Container -->
        <Instructions></Instructions>
    </div>
</template>
<script>
    import ErrorMessage from '../../components/partials/error-message.vue';
    import SuccessMessage from '../../components/partials/success-message.vue';
    import Loader from '../../components/partials/loader.vue';
    import RouterContainer from '../../components/router-components/partials/pf-router-container.vue';
    import Draggable from 'vuedraggable';
    import RouterForm from '../../components/router-components/partials/routers-form.vue';
    import Instructions from '../info-components/partflow-inst.vue';
    export default {
        data(){
            return {
                edit: false,
                // loading: true,
                errorMessage: '',
                successMessage: '',
                search_pn: false,
                search_router: false,
                dragOptions: { group: 'routers' }
            }
        },
        mounted() {
            // this.loadIn();
        },
        components: {
            ErrorMessage,
            SuccessMessage,
            Draggable,
            RouterContainer,
            RouterForm,
            Loader,
            Instructions
        },
        computed: {
            loading(){ return this.$store.getters.getLoad; },
            list: {
                get() {
                  return this.$store.getters.getRouters;
                },
                set(value) {
                  this.$store.dispatch('sortRouters', value);
                }
            },
            search_router_num: { 
                get() {
                    return this.$store.state.routers.search_router;   
                },
                set(value) {
                    this.$store.commit('updateSearchRouter', value);
                }
            },
            search_pn_num: {
                get() {
                    return this.$store.state.routers.search_pn_num;
                },
                set(value) {
                    this.$store.commit('updateSearchPnNum', value);
                }
            },
            missedRouters() { return this.$store.getters.getMissedRouters; },
            departments() { return this.$store.getters.getDepartments; }
        },
        methods: {
            toDepartments(){
                this.$emit('toDept');
            },
            loadIn(){
                setTimeout(()=> {
                    this.$store.commit('setLoad', false);
                }, 1500)
            },
            updateRouter(){},
            showRouter(){},
            cancelEdit(){
                this.edit = false;
                this.$store.commit('resetState');
            },
            searchByRouter(router){
                this.$store.dispatch('commitRouters')
                .then(() => {
                    this.$store.commit('searchByRouter', 'flow');
                    this.search_router = true;
                    this.search_pn = false;
                    this.$store.commit('updateSearchPnNum', '');
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchByRouter failed!!! ' + error);
                })
            },
            searchByPn(part){
                this.$store.dispatch('commitRouters')
                .then(() => {
                    this.$store.commit('searchByPn', 'flow');
                    this.search_pn = true;
                    this.search_router = false;
                    this.$store.commit('updateSearchRouter', '');
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchByPn method failed!!! ' + error);
                });
            },
            cancelAllSearch(){
                this.search_router = false;
                this.$store.commit('updateSearchRouter', '');
                this.search_pn = false;
                this.$store.commit('updateSearchPnNum', '');  
            },
            cancelSearch() {
                this.search_router = false;
                this.$store.commit('updateSearchRouter', '');
                this.$store.dispatch('commitRouters');
            },
            cancelSearchPn() {
                this.search_pn = false;
                this.$store.commit('updateSearchPnNum', '');
                this.$store.dispatch('commitRouters');
            },
            /**
             * commits to routers mutation to switch all STFI status
             * routers and covert them to II status routers as well as
             * equal the stock_qty to the qty of the router.
             */
            submitToII() {
                this.$store.dispatch('switchToII')
                .then(() => {
                    this.message("Routers have successfully been submited to inventory.", 'success', 5000);
                })
                .catch((error) => {
                    this.message("Something went wrong when submiting you routers to inventory.", 'error', 5000);
                    throw new Error('Something went wrong with submitToII method.');
                });
            },
            /**
             * When Element is dragged from one dropzone to another
             * get related and dragged context data and set variables.
             * This method is hevily dependant of the html dom tree where
             * the following ID's are present: 'NIP', 'IP' and 'STFI'. Do not
             * add any more nested elements as this method traverses the dom to
             * grab those ID's and use them as data.
             *
             * @param relatedContext | Object
             * @param draggedContext | Object
             * @return void
             */
            onMove({relatedContext, draggedContext}){
                this.$store.commit('setFutureIndex', draggedContext.futureIndex);
                // Sets the id of the drop parent element which is the department name
                this.$store.commit('setNextDept', relatedContext.component.$el.parentElement.id);
                // Set the id of the main parent container which has the Status ID
                this.$store.commit('setNextStatus', relatedContext.component.$el.parentElement.parentElement.id)
                // Saves the element that is being moved
                this.$store.commit('setDraggedElement', draggedContext.element);
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
    h4 {
        color: #fff;
    }
    #scroll-container {
        overflow: scroll;
        width: 100%;
        height: 700px;
        background: #ddd;
        margin: 0;
        border-radius: 10px;
        border: 1px dashed #bbb;
    }
    .dept-main {
        min-width: 770px;
        /*overflow-y: hidden !important;*/
        /*overflow-x: auto;*/
        white-space: nowrap;
        height: 675px !important;
    }
    .dept-cont, .dept-cont-inner-ip {
        border-radius: 10px;
        margin: 5px 7px;
        padding: 10px;
        overflow: scroll;
    }
    .dept-cont-ip {
        border-radius: 10px;
        margin: 5px 7px;
        padding: 10px;
        overflow-y: scroll;
    }
    .nip-cont {
        background: #a1b9c6;
        display: inline-block;
    }
    .dept-cont {
        height: 667px;
        width: 250px;
        display: inline-block;
    }
    .ip-cont {
        height: 500px !important;
    }
    .dept-cont-ip {
        height: 659px;
        min-width: 260px;
        display: inline-block;
        background: #a1b9c6;
    }
    .dept-cont-inner-ip {
        background: #ddd;
        display: inline-block;
        height: 595px;
        width: 235px;
    }
    .ii-cont {
        background: #a1b9c6;
    }
    .ii-inner-cont {
        /*height: 520px;*/
        /*overflow-y: scroll;*/
        overflow: hidden;
        margin-bottom: 5px;
    }
    .dotted {
        border: 2px dotted #fff;
        color: #aaa;
    }
    .radius {
      border-radius: 10px;
    }
    #main-search {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    @media(max-width:1345px){
        .fa-search {
            display: none;
        }
    }
</style>

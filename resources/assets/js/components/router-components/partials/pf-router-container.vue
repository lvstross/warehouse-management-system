<template>
    <div class="router-cont move-shadow">
        <div class="full-width">
            <span class="sm-font">Router #: {{ data.router_num }} | Qty: {{ data.qty }}</span>
            <button class="toggle-btn pull-right" @click="toggle"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
        </div>
        <div class="full-width" v-if="card">
            <hr class="dashed">
            <p><strong>Part #:</strong> {{ data.part_num }}</p>
            <p><strong>Date:</strong> {{ newDate(data.date) }}</p>
            <p><strong>Status:</strong> {{ data.status }}</p>
            <p v-if="data.dept_name"><strong>Department:</strong> {{ data.dept_name }}</p>
            <p v-if="data.stock_qty != 0"><strong>Stock Qty:</strong> {{ data.stock_qty }}</p>
            <p v-if="data.status != 'II' && data.stock_qty != 0" class="alert alert-warning log-item">This router has a stock qty even though it does not have 'In Inventory' status. Please set the stock qty to zero if this is in error!</p>
            <div id="move-cont" class="full-width" v-if="move_log">
                <p class="text-center underline"><strong>Move Log</strong></p>
                <div class="full-width" v-if="data.move_log.length > 0">
                    <p class="log-item" v-for="items in data.move_log">{{items.item}}</p>
                </div>
                <div v-else>
                    <p class="alert alert-info text-center">Move Log Empty</p>
                </div>
            </div>
            <div class="full-width text-center">
                <button @click="edit(data.id)" type="button" class="btn btn-warning btn-xs">Edit</button> &nbsp;
                <button @click="showlog" type="button" class="btn btn-default btn-xs">Move Log</button>
            </div>
        </div>
    </div>
</template>
<script>
    import Utils from '../../../modules/utils.js';
    export default {
        props: {
            edit: Function,
            data: Object,
        },
        data(){
            return {
                card: false,
                move_log: false
            }
        },
        methods: {
            toggle(){
                if(this.card == false){
                    this.card = true;
                }else{
                    this.card = false;
                }
            },
            showlog(){
                if(this.move_log == false){
                    this.move_log = true;
                }else{
                    this.move_log = false;
                }
            },
            newDate(date){
                return Utils.invertDate(date);
            }
        }
    }
</script>
<style>
    .router-cont {
        width: 100%;
        min-height: 50px;
        padding: 10px;
        border-radius: 10px;
        background: #fff;
        color: #000;
        margin: 5px 0;
        display: flex;
        flex-direction: column;
    }
    .log-item {
        /*overflow-x: none;*/
        white-space: normal;
    }
    .toggle-btn {
        border: 1px solid #bbb;
        border-radius: 5px;
    }
    #move-cont {
        max-height: 230px;
        overflow-y: scroll;
    }
    .move-shadow:hover {
      box-shadow: 0 0 5px 3px #000;
    }
    .move-shadow:active {
      box-shadow: 0 0 5px 3px #000;
    }
    .actions {
        color: #25a3bc;
        text-decoration: underline;
    }
    .sm-font {
        font-size: 13px;
    }
</style>

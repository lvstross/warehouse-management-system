<template>
    <div>
        <!-- Error and Success Messages -->
        <errorMessage :errorMes="errorMessage"></errorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- Search Area -->
        <transition name="fadetwo">
            <div v-show="search">
                <div class="full-width">
                    <button @click="closeSearch" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                <div class="clear-both"></div>
                <br>
                <div class="row">
                    <!-- Search By PO -->
                    <div class="col-xs-12 col-sm-4 form-group">
                        <input v-model="po_search" type="text" name="search_po" class="form-control" placeholder="By PO #">
                        <button @click="cancelAllSearch(true)" type="button" v-show="search_po" class="btn btn-danger full-width">Cancel</button>
                    </div>
                    <div class="col-xs-12 col-sm-2 form-group">
                        <button @click="searchPONum" type="button" class="btn btn-default full-width">Search</button>
                    </div>
                    <!-- End of Search by PO -->
                    <!-- Search by vendor -->
                    <div class="col-xs-12 col-sm-4 form-group">
                        <select v-model="vendor_search" name="search_vendor" class="form-control">
                            <option value="0">Choose A Vendor</option>
                            <option v-for="ven in vendors" :value="ven.id">{{ ven.name }}</option>
                        </select>
                        <button @click="cancelAllSearch(true)" type="button" v-show="search_vendor" class="btn btn-danger full-width">Cancel</button>
                    </div>
                    <div class="col-xs-12 col-sm-2 form-group">
                        <button @click="searchVendor" type="button" class="btn btn-default full-width">Search</button>
                    </div>
                    <!-- End of search by vendor -->
                </div>
            </div>
        </transition>
        <!-- End of Search Area -->
        <!-- Start of on time report -->
        <transition name="fadetwo">
            <div v-show="on_time">
                <div class="full-width">
                    <button @click="closeOnTime" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                <div class="clear-both"></div>
                <br>
                <form :action="'/pdf/purchases/ontimereport'+'/'+start_date+'/'+end_date+'/'+vendor" method="get">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                            <label for="start">Start</label>
                            <input v-model="start_date" class="form-control" type="date" name="start" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                            <label for="end">End</label>
                            <input v-model="end_date" class="form-control" type="date" name="end" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 form-group">
                            <label for="vendor">Vendor</label>
                            <select v-model="vendor" name="vendor" class="form-control" required>
                                <option value="0">Choose A Vendor</option>
                                <option v-for="ven in vendors" :value="ven.id">{{ ven.name }}</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" style="padding-top: 25px;">
                            <button type="submit" class="btn btn-primary full-width"><i class="fa fa-print fa-sm"></i> Get Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </transition>
        <!-- End of Start of on time report -->
        <!-- Start of purchasing report -->
        <transition name="fadetwo">
            <div v-show="purchasing">
                <div class="full-width">
                    <button @click="closeSales" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                <div class="clear-both"></div>
                <br>
                <form :action="'/pdf/purchases/purchasingreport'+'/'+start_date+'/'+end_date" method="get">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 form-group">
                            <label for="start">Start</label>
                            <input v-model="start_date" class="form-control" type="date" name="start" required>
                        </div>
                        <div class="col-xs-12 col-sm-4 form-group">
                            <label for="end">End</label>
                            <input v-model="end_date" class="form-control" type="date" name="end" required>
                        </div>
                        <div class="col-xs-12 col-sm-4 form-group" style="padding-top: 25px;">
                            <button type="submit" class="btn btn-primary full-width"><i class="fa fa-print fa-sm"></i> Get Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </transition>
        <!-- End of Start of purchasing report -->
        <!-- End of Error and Success Messages -->
        <h2 class="underline">{{status}}</h2>
        <!-- Buttons -->
        <div>
            <div @click="switchToOpen" class="pull-left"><button class="btn hover-underline black-text btn-sm"><i class="fa fa-credit-card fa-sm"></i> See Purchases</button></div>
            <div @click="switchToClosed" class="pull-left"><button class="btn hover-underline black-text btn-sm"><i class="fa fa-paperclip fa-sm"></i> See Receivals</button></div>
            <div class="pull-left"><button @click="openSearch" class="btn hover-underline black-text btn-sm"><i class="fa fa-search-plus fa-sm"></i> Search Purchases</button></div>
            <div class="pull-left"><button @click="openAddForm" class="btn hover-underline black-text btn-sm"><i class="fa fa-plus fa-sm"></i> Add A Purchases</button></div>
            <div class="pull-left"><button @click="openOnTime" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Get On Time Vendor Report</button></div>
            <div class="pull-left"><button @click="openPurchasing" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Get Purchasing Report</button></div>
        </div>
        <!-- End of Buttons -->
        <!-- Loader -->
        <Loader v-show="loading"></Loader>
        <!-- End of Loader -->
        <!-- Start of purchases table Open -->
        <transition name="fade">
            <div v-if="!loading && open" id="poBox">
                <div v-if="list_open.length > 0">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>P.O. #</th>
                                <th>Vendor</th>
                                <th>Due Date</th>
                                <th>Order Confirmed?</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Print</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pos, index) in list_open">
                                <td class="text-center">{{ pos.po_num }}</td>
                                <td style="color: red;" class="text-center" v-if="pos.vendor == 'Vendor Not Found'">{{ pos.vendor }}</td>
                                <td class="text-center" v-else>{{ pos.vendor }}</td>
                                <td class="text-center">{{ newDate(pos.due_date) }}</td>
                                <td class="text-center" v-if="pos.vendor_confirm_order">{{ newDate(pos.vendor_confirm_order) }}</td>
                                <td class="text-center" v-else>No</td>
                                <td class="text-center">{{ pos.po_total }}</td>
                                <td class="text-center">{{ pos.status }}</td>
                                <td class="text-center" v-if="pos.vendor == 'Vendor Not Found'"><button type="button" class="btn btn-default btn-xs" disabled><i class="fa fa-print"></i> Print</button></td>
                                <td class="text-center" v-else ><a :href="'/pdf/purchases/printout/' + pos.id" class="btn btn-default btn-xs"><i class="fa fa-print"></i> Print</a></td>
                                <td class="text-center"><button @click="viewMore(pos.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td class="text-center"><button @click="editPO(pos.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1" class="text-center"><button @click="deletePO(pos.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <span class="alert alert-info">You currently have no purchases to show.</span>
                </div>
            </div>
        </transition>
        <!-- end of purchases table -->
        <!-- Start of purchases table closed -->
        <transition name="fade">
            <div v-if="!loading && closed" id="poBox">
                <div v-if="list_closed.length > 0">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>P.O. #</th>
                                <th>Receival #</th>
                                <th>Vendor</th>
                                <th>Due Date</th>
                                <th>Date Received</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Print</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pos, index) in list_closed">
                                <td class="text-center">{{ pos.po_num }}</td>
                                <td class="text-center">{{ pos.recv_num }}</td>
                                <td style="color: red;" class="text-center" v-if="pos.vendor == 'Vendor Not Found'">{{ pos.vendor }}</td>
                                <td class="text-center" v-else>{{ pos.vendor }}</td>
                                <td class="text-center">{{ newDate(pos.due_date) }}</td>
                                <td class="text-center">{{ newDate(pos.recv_date) }}</td>
                                <td class="text-center">{{ pos.po_total }}</td>
                                <td class="text-center">{{ pos.status }}</td>
                                <td class="text-center" v-if="pos.vendor == 'Vendor Not Found'"><button type="button" class="btn btn-default btn-xs" disabled><i class="fa fa-print"></i> Print</button></td>
                                <td class="text-center" v-else ><a :href="'/pdf/purchases/receival/' + pos.id" class="btn btn-default btn-xs"><i class="fa fa-print"></i> Print</a></td>
                                <td class="text-center"><button @click="viewMore(pos.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td class="text-center"><button @click="editPO(pos.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1" class="text-center"><button @click="deletePO(pos.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <span class="alert alert-info">You currently have no purchases to show.</span>
                </div>
            </div>
        </transition>
        <!-- end of purchases table Closed -->
        <!-- Start of Modal Form Container -->
        <transition name="fadetwo">
            <div v-show="form" class="modal-cont">
                <div class="modal-inner-cont">
                    <div class="full-width">
                        <button @click="resetToTable" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                    </div>
                    <h3 class="text-center">Purchase Order Details</h3>
                    <form action="#" @submit.prevent="edit ? updatePO(poObj.id) : createPO()">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="po_num">Purchase Number</label>
                                    <input v-model="poObj.po_num" @keyup="updatePNum" class="form-control" type="text" name="po_num" required>
                                </div>
                            </div>
                            <div v-show="edit" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="recv_num">Receival Number</label>
                                    <input v-model="poObj.recv_num" @keyup="updatePRecvNum" class="form-control" type="text" name="recv_num">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="vendor">Vendor</label>
                                    <select v-model="poObj.vendor" @blur="updatePVendor" class="form-control" name="vendor" required>
                                        <option value="0">Choose A Vandor</option>
                                        <option v-for="vendor in vendors" :value="vendor.id">{{ vendor.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="terms">Terms Code</label>
                                    <select v-model="poObj.terms" @blur="updatePTerms" class="form-control" name="terms">
                                        <option>Choose An Item</option>
                                        <option>COD</option>
                                        <option>Collect</option>
                                        <option>NET30</option>
                                        <option>Prepaid</option>
                                        <option>On Receipt</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="carrier">Carrier</label>
                                    <input v-model="poObj.carrier" @keyup="updatePCarrier" class="form-control" type="text" name="carrier">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input v-model="poObj.due_date" @blur="updatePDueDate" class="form-control" type="date" name="due_date" required>
                                </div>
                            </div>
                            <div v-show="edit" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="recv_date">Date Received</label>
                                    <input v-model="poObj.recv_date" @blur="updatePRecvDate" class="form-control" type="date" name="recv_date">
                                </div>
                            </div>
                            <div v-show="edit" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="vendor_confirm_order">Vendor Confirm Order Date</label>
                                    <input v-model="poObj.vendor_confirm_order" @blur="updatePVendorConfirmOrder" class="form-control" type="date" name="vendor_confirm_order">
                                </div>
                            </div>
                            <div v-show="edit" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select v-model="poObj.status" @blur="updatePStatus" class="form-control" name="status">
                                        <option value="CAO">Choose An Option</option>
                                        <option>Open</option>
                                        <option>Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-center">Purchase Items</h3>
                        <div v-if="poObj.items.length > 0" class="max-scroll">
                            <div class="row" v-for="(m, index) in poObj.items">
                                <div class="col-xs-12 text-center">
                                    <div class="badge">{{ index+1 }} of {{ poObj.items.length }}</div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'part_num_' + index">Part #</label>
                                        <input :value="m.part_num" type="text" @keyup="editPartNum" @blur="changePartNum(index)" :name="'part_num_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'qty_ordered_' + index">Qty Ordered</label>
                                        <input :value="m.qty_ordered" type="number" @keyup="editQtyOrdered" @blur="changeQtyOrdered(index)" :name="'qty_ordered_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'qty_recv_good_' + index">Qty Received Good</label>
                                        <input :value="m.qty_recv_good" type="text" @keyup="editQtyRecvGood" @blur="changeQtyRecvGood(index)" :name="'qty_recv_good_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'qty_canceled_' + index">Qty Canceled</label>
                                        <input :value="m.qty_canceled" type="text" @keyup="editQtyCanceled" @blur="changeQtyCanceled(index)" :name="'qty_canceled_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'qty_rej_' + index">Qty Rejected</label>
                                        <input :value="m.qty_rej" type="text" @keyup="editQtyRejected" @blur="changeQtyRejected(index)" :name="'qty_rej_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'unit_cost_' + index">Unit Cost</label>
                                        <input :value="m.unit_cost" type="number" @keyup="editUnitCost" @blur="changeUnitCost(index)" step="0.01" :name="'unit_cost_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'unit_of_measure_' + index">Unit of Measure</label>
                                        <input :value="m.unit_of_measure" type="text" @keyup="editUnitOfMeasure" @blur="changeUnitOfMeasure(index)" :name="'unit_of_measure_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'recv_date_' + index">Receive Date</label>
                                        <input :value="m.recv_date" type="date" @keyup="editRecvDate" @blur="changeRecvDate(index)" :name="'recv_date_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'back_order_qty_' + index">Back Order Qty</label>
                                        <input :value="m.back_order_qty" type="text" @keyup="editBackOrderQty" @blur="changeBackOrderQty(index)" :name="'back_order_qty_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'vendor_confirm_date_' + index">Vendor Confirm Date</label>
                                        <input :value="m.vendor_confirm_date" type="date" @keyup="editVendorConfirmDate" @blur="changeVendorConfirmDate(index)" :name="'vendor_confirm_date_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <label :for="'description_' + index">Description</label>
                                        <input :value="m.description" type="text" @keyup="editDescription" @blur="changeDescription(index)" :name="'description_' + index" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger btn-margin full-width" @click="removeItem(index)">Remove Item</button>
                                    </div>
                                </div>
                                <hr class="dashed">
                            </div>
                        </div>
                        <div v-else>
                            <p class="alert alert-info text-center">No Purchase Items Added</p>
                        </div>
                        <hr class="dashed">
                        <!-- Items Container -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="part_num">Part #</label>
                                    <input v-model="item.part_num" type="text" name="part_num" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="qty_ordered">Qty Ordered</label>
                                    <input v-model="item.qty_ordered" type="number" name="qty_ordered" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="qty_recv_good">Qty Received Good</label>
                                    <input v-model="item.qty_recv_good" type="text" name="qty_recv_good" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="qty_canceled">Qty Canceled</label>
                                    <input v-model="item.qty_canceled" type="text" name="qty_canceled" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="qty_rej">Qty Rejected</label>
                                    <input v-model="item.qty_rej" type="text" name="qty_rej" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="unit_cost">Unit Cost</label>
                                    <input v-model="item.unit_cost" type="number" name="unit_cost" step="0.01" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="unit_of_measure">Unit of Measure</label>
                                    <input v-model="item.unit_of_measure" type="text" name="unit_of_measure" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="recv_date">Receive Date</label>
                                    <input v-model="item.recv_date" type="date" name="recv_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="back_order_qty">Back Order Qty</label>
                                    <input v-model="item.back_order_qty" type="text" name="back_order_qty" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="vendor_confirm_date">Vendor Confirm Date</label>
                                    <input v-model="item.vendor_confirm_date" type="date" name="vendor_confirm_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input v-model="item.description" type="text" name="description" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success btn-margin full-width" @click="addItem">Add Item</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Items Container -->
                        <hr class="dashed">
                        <div class="row">
                            <div v-if="poObj.po_total" class="col-xs-12">
                                <h3 class="pull-right">Purchase Total: ${{ poObj.po_total }}</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="comments">Comments</label>
                                    <textarea v-model="poObj.comments" @keyup="updatePComments" class="form-control" name="comments" row="4""></textarea>
                                </div>
                            </div>
                        </div>
                        <SubmitBtns :editMode="edit" :name="name='Purchase'"></SubmitBtns>
                    </form>
                </div>
            </div>
        </transition>
        <!-- End of Modal Form Container -->
        <!-- More Box -->
        <transition name="fadetwo">
            <div ref="moreBox" v-if="moreBox" class="more-box-right">
                <div class="inner-more-box overflow-scroll">
                    <div class="full-width">
                        <button @click="closeMoreBox" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                        <button @click="moveBoxToggle" class="btn btn-default btn-sm pull-left"><i ref="arrow" class="fa fa-arrow-left"></i></button>
                    </div>
                    <br>
                    <div>
                        <h3>Purchase Details</h3>
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <p>
                                    <strong>P.O. #</strong> {{ poObj.po_num }} <br> 
                                    <strong>Receival #</strong> {{ poObj.recv_num }} <br> 
                                    <strong>Vendor:</strong> {{ poObj.vendor }} <br> 
                                    <strong>Due Date:</strong> {{ newDate(poObj.due_date) }} <br> 
                                    <strong>Receive Date:</strong> {{ newDate(poObj.recv_date) }} <br> 
                                    <strong>Terms:</strong> {{ poObj.terms }} <br> 
                                    <strong>P.O. Total:</strong> {{ poObj.po_total }} <br> 
                                    <strong>Status:</strong> {{ poObj.status }} <br> 
                                </p>     
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <p>
                                    <strong>Entered By:</strong> {{ poObj.entered_by }} <br> 
                                    <strong>Enter Date:</strong> {{ newDate(poObj.enter_date) }} <br> 
                                    <strong>Modified By:</strong> {{ poObj.modified_by }} <br> 
                                    <strong>Modify Date:</strong> {{ newDate(poObj.modify_date) }}
                                </p> 
                            </div>
                        </div>
                    </div>
                    <div class="pull-left">
                        <h3>Comments and Items</h3>
                    </div>
                    <div class="clear-both"></div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-6">
                            <div class="row">
                                <div class="col-xs-12 light-border">
                                    <h4 class="text-center">Comments</h4>
                                    <p v-if="poObj.comments">{{ poObj.comments }}</p>
                                    <p v-else class="alert alert-info text-center">No Comments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 light-border">
                            <h4 class="text-center">Items</h4>
                            <div v-if="poObj.items.length > 0" class="row">
                                <div v-for="(m, index) in poObj.items" class="col-xs-12">
                                    <div class="text-center">
                                        <div class="badge">{{ index+1 }} of {{ poObj.items.length }}</div>
                                    </div>
                                    <p>
                                        <strong>Part #:</strong> {{m.part_num}} <br>
                                        <strong>Qty Ordered:</strong> {{m.qty_ordered}} <br>
                                        <strong>Qty Canceled:</strong> {{m.qty_canceled}} <br>
                                        <strong>Unit Cost:</strong> {{m.unit_cost}} <br>
                                        <strong>Unit Of Measure:</strong> {{m.unit_of_measure}} <br>
                                        <strong>Vendor Confirm Date:</strong> {{newDate(m.vendor_confirm_date)}} <br>
                                        <strong>Received Date:</strong> {{newDate(m.recv_date)}} <br>
                                        <strong>Qty Received Good:</strong> {{m.qty_recv_good}} <br>
                                        <strong>Qty Rejected:</strong> {{m.qty_rej}} <br>
                                        <strong>Back Order Qty:</strong> {{m.back_order_qty}} <br>
                                        <strong>Description:</strong> {{m.description}}
                                    </p>
                                </div>
                            </div>
                            <div v-else>
                                <p class="alert alert-info text-center">No Items</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <!-- End of More Box -->
        <!-- Instruction Area -->
        <Instructions></Instructions>
        <!-- End of Instructions -->
    </div>
</template>
<script>
    import Utils from '../modules/utils.js';
    import SubmitBtns from '../components/partials/submit-btn.vue';
    import Loader from '../components/partials/loader.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Instructions from '../components/info-components/purchases-inst.vue';
    export default {
        data(){
            return {
                form: false,
                moreBox: false,
                edit: false,
                loading: true,
                open: false,
                closed: false,
                on_time: false,
                purchasing: false,
                start_date: '',
                end_date: '',
                vendor: 0,
                search: false,
                s: '', // for being 'Open' or 'Closed' where searching
                search_po: false,
                po_search: '',
                search_vendor: false,
                vendor_search: 0,
                status: 'Loading ...',
                editItem: {
                    part_num: '',
                    qty_ordered: '',
                    qty_recv_good: '',
                    qty_canceled: '',
                    qty_rej: '',
                    unit_cost: '',
                    unit_of_measure: '',
                    recv_date: '',
                    back_order_qty: '',
                    vendor_confirm_date: '',
                    description: ''
                },
                item: {
                    part_num: '',
                    qty_ordered: '',
                    qty_recv_good: '',
                    qty_canceled: '',
                    qty_rej: '',
                    unit_cost: '',
                    unit_of_measure: '',
                    recv_date: '',
                    back_order_qty: '',
                    vendor_confirm_date: '',
                    description: ''
                },
                successMessage: '',
                errorMessage: ''
            }
        },
        mounted() {
            this.loadIn();
            this.getUser();
            this.getVendors();
            this.getOpenPurchases();
            this.getClosedPurchases();
        },
        components: {
            ErrorMessage,
            SuccessMessage,
            Loader,
            SubmitBtns,
            Instructions
        },
        computed: {
            user() { return this.$store.getters.getUser; },
            poObj() { return this.$store.getters.getPurchaseObject; },
            vendors() { return this.$store.getters.getVendors; },
            list_open() {return this.$store.getters.getPurchasesOpen; },
            list_closed() { return this.$store.getters.getPurchasesClosed; }
        },
        methods: {
            loadIn(){
                this.setApplication();
            },
            setApplication(){
                let param = Utils.getUriParam();
                if(param == 'add'){
                    this.open = true;
                    this.status = 'Purchases';
                    this.form = true;
                }else if(param == 'receivals'){
                    this.closed = true;
                    this.status = 'Receivals';
                }else{ // if app=purchases
                    this.open = true;
                    this.status = 'Purchases';
                }
            },
            // MUTATIONS
            updatePNum(e){ this.$store.commit('updatePurchaseNum', e.target.value); },
            updatePRecvNum(e){ this.$store.commit('updatePurchaseRecvNum', e.target.value); },
            updatePVendor(e){ this.$store.commit('updatePurchaseVendor', e.target.value); },
            updatePDueDate(e){ this.$store.commit('updatePurchaseDueDate', e.target.value); },
            updatePRecvDate(e){ this.$store.commit('updatePurchaseRecvDate', e.target.value); },
            updatePTerms(e){ this.$store.commit('updatePurchaseTerms', e.target.value); },
            updatePCarrier(e){ this.$store.commit('updatePurchaseCarrier', e.target.value); },
            updatePVendorConfirmOrder(e){ this.$store.commit('updatePurchaseVendorConfirmOrder', e.target.value); },
            updatePStatus(e){ this.$store.commit('updatePurchaseStatus', e.target.value); },
            updatePComments(e){ this.$store.commit('updatePurchaseComments', e.target.value); },
            // Mutations for Items
            editPartNum(e){ this.editItem.part_num = e.target.value; },
            editQtyOrdered(e){ this.editItem.qty_ordered = e.target.value; },
            editQtyRecvGood(e){ this.editItem.qty_recv_good = e.target.value; },
            editQtyCanceled(e){ this.editItem.qty_canceled = e.target.value; },
            editQtyRejected(e){ this.editItem.qty_rej = e.target.value; },
            editUnitCost(e){ this.editItem.unit_cost = e.target.value; },
            editUnitOfMeasure(e){ this.editItem.unit_of_measure = e.target.value; },
            editRecvDate(e){ this.editItem.recv_date = e.target.value; },
            editBackOrderQty(e){ this.editItem.back_order_qty = e.target.value; },
            editVendorConfirmDate(e){ this.editItem.vendor_confirm_order = e.target.value; },
            editDescription(e){ this.editItem.description = e.target.value; },
            changePartNum(i){ this.$store.commit('changeIndexPartNum', { value: this.editItem.part_num, index: i }); this.editItem.part_num = '';},
            changeQtyOrdered(i){ this.$store.commit('changeIndexQtyOrdered', { value: this.editItem.qty_ordered, index: i }); this.editItem.qty_ordered = '';},
            changeQtyRecvGood(i){ this.$store.commit('changeIndexQtyRecvGood', { value: this.editItem.qty_recv_good, index: i }); this.editItem.qty_recv_good = '';},
            changeQtyCanceled(i){ this.$store.commit('changeIndexQtyCanceled', { value: this.editItem.qty_canceled, index: i }); this.editItem.qty_canceled = '';},
            changeQtyRejected(i){ this.$store.commit('changeIndexQtyRejected', { value: this.editItem.qty_rej, index: i }); this.editItem.qty_rej = '';},
            changeUnitCost(i){ this.$store.commit('changeIndexUnitCost', { value: this.editItem.unit_cost, index: i }); this.editItem.unit_cost = '';},
            changeUnitOfMeasure(i){ this.$store.commit('changeIndexUnitOfMeasure', { value: this.editItem.unit_of_measure, index: i }); this.editItem.unit_of_measure = '';},
            changeRecvDate(i){ this.$store.commit('changeIndexRecvDate', { value: this.editItem.recv_date, index: i }); this.editItem.recv_date = '';},
            changeBackOrderQty(i){ this.$store.commit('changeIndexBackOrderQty', { value: this.editItem.back_order_qty, index: i }); this.editItem.back_order_qty = '';},
            changeVendorConfirmDate(i){ this.$store.commit('changeIndexVendorConfirmDate', { value: this.editItem.vendor_confirm_date, index: i }); this.editItem.vendor_confirm_date = '';},
            changeDescription(i){ this.$store.commit('changeIndexDescription', { value: this.editItem.description, index: i }); this.editItem.description = '';},
            addItem(){
                let obj = Object.assign({}, this.item);
                this.$store.commit('addPurchaseItem', obj);
                for(var key in this.item){
                    this.item[key] = '';
                }
            },
            removeItem(i){
                this.$store.commit('removePurchaseItem', i);
            },
            resetState(){ this.$store.commit('resetState'); },
            // ACTIONS
            getUser(){ this.$store.dispatch('commitPermission'); },
            getVendors(){ this.$store.dispatch('commitVendors'); },
            getOpenPurchases(){ 
                this.$store.dispatch('commitOpenPurchases')
                .then(() => {
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                    this.message("Sorry! Something went wrong when getting your open purchases!", 'error', 10000);
                    throw new Error("Something went wrong with getting your purchases." + error);
                });
            },
            getClosedPurchases(){
                this.$store.dispatch('commitClosedPurchases')
                .then(() => {
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                    this.message("Sorry! Something went wrong when getting your closed purchases!", 'error', 10000);
                    throw new Error("Something went wrong with getting your purchases." + error);
                });
            },
            checkComplete(){
                for(let i = 0; i < this.poObj.items.length; i++){
                    if(this.poObj.items[i].back_order_qty == '' || this.poObj.items[i].back_order_qty == null || this.poObj.items[i].back_order_qty == 0 || this.poObj.items[i].back_order_qty == '0'){
                        console.log('checkComplete returned true');
                        return true;
                    }else{
                        console.log('checkComplete returned false');
                        return false;
                    }
                }
            },
            // CRUDS
            createPO(){
                if(this.poObj.vendor == 0){
                    alert('Please choose a vendor.');
                    return;
                }
                if(this.poObj.items.length == 0){
                    alert('At least one purchase item is required to submit the form.');
                    return;
                }
                this.$store.dispatch('createNewPurchase')
                .then(() => {
                    this.resetToTable();
                    this.cancelAllSearch(true);
                    this.switchToOpen();
                    this.message("Purchases successfully created!", 'success', 5000);
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when creating your purchase", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for createNewPurchase');
                });
            },
            viewMore(id, index){
                this.$store.dispatch('showPurchase', id)
                .then(() => {
                    this.moreBoxIndex = index;
                    this.moreBox = true;
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when viewing your purchase order", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for viewPO');
                });
            },
            editPO(id){
                this.$store.dispatch('showPurchase', id)
                .then(() => {
                    this.edit = true;
                    this.form = true;
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when viewing your purchase order", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for viewPO');
                });
            },
            updatePO(id){
                if(this.poObj.vendor == 0){
                    alert('Please choose a vendor.');
                    return;
                }
                if(this.poObj.items.length == 0){
                    alert('At least one purchase item is required to submit the form.');
                    return;
                }
                if(this.poObj.status == 'Closed'){
                    if(!this.checkComplete()){
                        alert('Purchase can not be closed while items have a back order total not yet received.');
                        return;
                    }
                    if(!this.poObj.recv_date){
                        alert('Please add a receive date before closing.');
                        return;
                    }
                    if(!this.poObj.recv_num){
                        alert('Please add a receival number before closing.');
                        return;
                    }
                }
                this.$store.dispatch('updatePurchase', id)
                .then(() => {
                    this.resetToTable();
                    this.cancelAllSearch(true);
                    this.message("Purchase order successfully updated!", 'success', 5000);
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when updating your purchase order", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for updatePO');
                });
            },
            deletePO(id){
                this.$store.dispatch('deletePurchase', id)
                .then(() => {
                    this.cancelAllSearch(true);
                    this.message("Purchase order successfully deleted!", 'success', 5000);
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when deleting your purchase order", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for deletePO');
                });
            },
            // Searches
            cancelForPONum(refresh){
                this.search_vendor = false;
                this.vendor_search = 0;
                if(refresh){
                    this.getOpenPurchases();
                    this.getClosedPurchases();
                }
            },
            cancelForVendor(refresh){
                this.search_po = false;
                this.po_search = '';
                if(refresh){
                    this.getOpenPurchases();
                    this.getClosedPurchases();
                }
            },
            cancelAllSearch(refresh){
                this.cancelForPONum(false);
                this.cancelForVendor(false);
                if(refresh){
                    this.getOpenPurchases();
                    this.getClosedPurchases();
                }
            },
            searchPONum(){
                this.cancelForPONum(false);
                this.loading = true;
                this.open ? this.s = 'Open' : this.s = 'Closed';
                this.$store.dispatch('searchPurchaseNum', {value: this.po_search, status: this.s})
                .then(()=>{
                    this.loading = false;
                    this.s = '';
                    this.search_po = true;
                }).catch((error)=>{
                    this.message("Sorry! Something went wrong when searching for your po number", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for searchPONum');
                });
            },
            searchVendor(){
                this.cancelForVendor(false);
                this.loading = true;
                this.open ? this.s = 'Open' : this.s = 'Closed';
                this.$store.dispatch('searchVendor', {value: this.vendor_search, status: this.s})
                .then(()=>{
                    this.loading = false;
                    this.s = '';
                    this.search_vendor = true;
                }).catch((error)=>{
                    this.message("Sorry! Something went wrong when searching for your vendor", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for searchVendor');
                });
            },
            // Utils
            moveBoxToggle(){
                if(this.$refs.moreBox.className == 'more-box-right'){
                    this.$refs.moreBox.className = 'more-box-left';
                    this.$refs.arrow.className = 'fa fa-arrow-right';
                }else{
                    this.$refs.moreBox.className = 'more-box-right';
                    this.$refs.arrow.className = 'fa fa-arrow-left';
                }
            },
            openOnTime(){
                this.closeSearch();
                this.closeSales();
                setTimeout(()=>{
                    this.on_time = true;
                }, 300);
            },
            closeOnTime(){
                this.on_time = false;
                this.start_date = '';
                this.end_date = '';
                this.vendor = '';
            },
            closeSales(){
                this.purchasing = false;
                this.start_date = '';
                this.end_date = '';
            },
            openPurchasing(){
                this.closeSearch();
                this.closeOnTime();
                setTimeout(()=>{
                    this.purchasing = true;
                }, 300);
            },
            openSearch(){
                this.closeSales();
                this.closeOnTime();
                setTimeout(()=>{
                    this.search = true;
                }, 300);
            },
            closeSearch(){
                this.search = false;
                this.cancelAllSearch(true);
            },
            openAddForm(){
                this.resetState();
                this.moreBox = false;
                this.form = true;
            },
            switchToClosed(){
                this.moreBox = false;
                this.open = false;
                setTimeout(()=>{
                    this.closed = true;
                    this.status = 'Receivals';
                },300)
            },
            switchToOpen(){
                this.moreBox = false;
                this.closed = false;
                setTimeout(()=>{
                    this.open = true;
                    this.status = 'Purchases';
                },300);
            },
            closeMoreBox(){
                this.moreBoxIndex = '';
                this.moreBox = false;
                this.selected_router = '';
                this.routers_added = false;
                this.resetState();
            },
            resetToTable(){
                this.form = false;
                this.moreBox = false;
                this.edit = false;
                this.resetState();
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
 #poBox {width: 100%;overflow: scroll;}
 #poBox > div {min-width: 1000px;}
 th {text-align: center;}
 td {padding: 1px 1px 1px 3px !important;}
 .light-border {border: .5px solid #aaa;}
 .max-scroll {
    border: .5px solid #aaa;
    max-height: 300px;
    padding: 5px;
    overflow-y: scroll;
    box-shadow: 0px 0px 8px #8e8e8e;
 }
 .btn-margin {margin-top: 26px;}
</style>
<template>
    <div>
        <!-- Error and Success Messages -->
        <errorMessage :errorMes="errorMessage"></errorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <transition name="fadetwo">
            <div v-show="search">
                <div class="full-width">
                    <button @click="closeSearch" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                <div class="clear-both"></div>
                <br>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-lg-3 form-group">
                        <input v-model="po_search" type="text" name="search_po" class="form-control" placeholder="By PO #">
                        <button @click="cancelAllSearch(true)" type="button" v-show="search_po" class="btn btn-danger full-width">Cancel</button>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-1 form-group">
                        <button @click="searchPONum" type="button" class="btn btn-default full-width">Search</button>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-3 form-group">
                        <input v-model="cust_search" type="text" name="search_cust" class="form-control" placeholder="By Customer">
                        <button @click="cancelAllSearch(true)" type="button" v-show="search_cust" class="btn btn-danger full-width">Cancel</button>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-1 form-group">
                        <button @click="searchCust" type="button" class="btn btn-default full-width">Search</button>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-3 form-group">
                        <input v-model="pn_search" type="text" name="search_pn" class="form-control" placeholder="By Part Number">
                        <button @click="cancelAllSearch(true)" type="button" v-show="search_pn" class="btn btn-danger full-width">Cancel</button>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-1 form-group">
                        <button @click="searchPartNum" type="button" class="btn btn-default full-width">Search</button>
                    </div>
                </div>
            </div>
        </transition>
        <!-- Start of on time report -->
        <transition name="fadetwo">
            <div v-show="on_time">
                <div class="full-width">
                    <button @click="closeOnTime" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                <div class="clear-both"></div>
                <br>
                <form :action="'/pdf/coor/ontimereport'+'/'+start_date+'/'+end_date+'/'+title" method="get">
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
                            <label for="title">Report Title</label>
                            <input v-model="title" class="form-control" type="text" name="title">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 form-group" style="padding-top: 25px;">
                            <button type="submit" class="btn btn-primary full-width"><i class="fa fa-print fa-sm"></i> Get Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </transition>
        <!-- End of Start of on time report -->
        <!-- Start of sales report -->
        <transition name="fadetwo">
            <div v-show="sales">
                <div class="full-width">
                    <button @click="closeSales" class="btn btn-default btn-sm pull-right"><i class="fa fa-times"></i></button>
                </div>
                <div class="clear-both"></div>
                <br>
                <form :action="'/pdf/coor/salesreport'+'/'+start_date+'/'+end_date+'/'+show_sales+'/'+show_status" method="get">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-2 form-group">
                            <label for="start">Start</label>
                            <input v-model="start_date" class="form-control" type="date" name="start" required>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 form-group">
                            <label for="end">End</label>
                            <input v-model="end_date" class="form-control" type="date" name="end" required>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 form-group">
                            <label for="sales">Show Sales Details?</label>
                            <select v-model="show_sales" class="form-control" name="sales" required>
                                <option>Choose An Option</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 form-group">
                            <label for="status">Status</label>
                            <select v-model="show_status" class="form-control" name="status" required>
                                <option>Choose An Option</option>
                                <option>Open</option>
                                <option>Closed</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 form-group" style="padding-top: 25px;">
                            <button type="submit" class="btn btn-primary full-width"><i class="fa fa-print fa-sm"></i> Get Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </transition>
        <!-- End of Start of sales report -->
        <!-- End of Error and Success Messages -->
        <h2 class="underline">{{status}}</h2>
        <div>
            <div @click="switchToOpen" class="pull-left"><button class="btn hover-underline black-text btn-sm"><i class="fa fa-folder-open fa-sm"></i> See Open Orders</button></div>
            <div @click="switchToClosed" class="pull-left"><button class="btn hover-underline black-text btn-sm"><i class="fa fa-folder fa-sm"></i> See Closed Orders</button></div>
            <div class="pull-left"><button @click="openSearch" class="btn hover-underline black-text btn-sm"><i class="fa fa-search-plus fa-sm"></i> Search Purchase Orders</button></div>
            <div class="pull-left"><button @click="openAddForm" class="btn hover-underline black-text btn-sm"><i class="fa fa-plus fa-sm"></i> Add A Purchase Order</button></div>
            <div class="pull-left"><button @click="coor" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Print Current Open Order Report</button></div>
            <div class="pull-left"><button @click="coorProduction" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Print Production Planning Sheet</button></div>
            <div class="pull-left"><button @click="coorTask" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Print Employee Task Sheet</button></div>
            <div class="pull-left"><button @click="openOnTime" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Get On Time Delivery Report</button></div>
            <div class="pull-left"><button @click="openSales" class="btn hover-underline black-text btn-sm"><i class="fa fa-print fa-sm"></i> Get Projection Or Sales Report</button></div>
            <div class="pull-left"><button @click="clearAllRating" class="btn hover-underline black-text btn-sm"><i class="fa fa-cut fa-sm"></i> Clear All Rating</button></div>
        </div>
        <!-- Loader -->
        <Loader v-show="loading"></Loader>
        <!-- End of Loader -->
        <!-- Start of purchaseorders table Open -->
        <transition name="fade">
            <div v-if="!loading && open" id="poBox">
                <div v-if="list_open.length > 0">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th v-if="!search" title="Order Identification">OID #</th>
                                <th>Will Ship</th>
                                <th>Due Date</th>
                                <th>Rating</th>
                                <th>Sooner?</th>
                                <th>Customer</th>
                                <th title="Purchase Order Number">P.O. #</th>
                                <th title="Part Number">Part #</th>
                                <th>Qty</th>
                                <th>Stock</th>
                                <th>Sales</th>
                                <th>Need Routers?</th>
                                <th title="Customer Contract Requirements">CCR / Notes</th>
                                <th>More</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <Draggable
                            v-model="list_open"
                            :options="{group:'pos'}" 
                            :element="'tbody'" 
                            class="cursor-move"
                        >
                            <tr v-for="(pos, index) in list_open" :key="pos.key" class="move-shadow">
                                <td v-if="!search" class="text-center">{{ pos.key }}</td>
                                <td class="text-center">{{ newDate(pos.will_ship) }}</td>
                                <td class="text-center">{{ newDate(pos.due_date) }}</td>
                                <td class="text-center">{{ checkIfEmpty(pos.rating) }}</td>
                                <td class="text-center">{{ checkIfStr(pos.sooner, 'Yes') }}</td>
                                <td :title="pos.customer">{{ shortenStr(pos.customer, 15) }}</td>
                                <td>{{ pos.po_num }}</td>
                                <td>{{ pos.part_num }}</td>
                                <td class="text-center">{{ pos.qty }}</td>
                                <td class="text-center" :title="pos.stock">{{ checkIfEmpty(shortenStr(pos.stock, 20)) }}</td>
                                <td class="text-center">{{ pos.sales }}</td>
                                <td class="text-center">{{ checkIfStr(pos.need_routers, 'Yes') }}</td>
                                <td :title="pos.cust_req">{{ shortenStr(pos.cust_req, 50) }}</td>
                                <td class="text-center"><button @click="viewMore(pos.id, index+1)" class="btn btn-default btn-xs full-width"><i class="fa fa-caret-down" aria-hidden="true"></i></button></td>
                                <td class="text-center"><button @click="editPO(pos.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td class="text-center"><button @click="deletePO(pos.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </Draggable>
                    </table>
                </div>
                <div v-else>
                    <span class="alert alert-info">You currently have no purchase orders to show.</span>
                </div>
            </div>
        </transition>
        <!-- end of Invoices table -->
        <!-- Start of purchaseorders table closed -->
        <transition name="fade">
            <div v-if="!loading && closed" id="poBox">
                <div v-if="list_closed.length > 0">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Will Ship</th>
                                <th>Date Shipped</th>
                                <th>Due Date</th>
                                <th>Sooner?</th>
                                <th>Customer</th>
                                <th title="Purchase Order Number">P.O. #</th>
                                <th title="Part Number">Part #</th>
                                <th>Qty</th>
                                <th>Sales</th>
                                <th title="Customer Contract Requirements">CCR / Notes</th>
                                <th>More</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pos, index) in list_closed">
                                <td class="text-center">{{ pos.invoice }}</td>
                                <td class="text-center">{{ newDate(pos.will_ship) }}</td>
                                <td class="text-center">{{ newDate(pos.ship_date) }}</td>
                                <td class="text-center">{{ newDate(pos.due_date) }}</td>
                                <td class="text-center">{{ checkIfStr(pos.sooner, 'Yes') }}</td>
                                <td :title="pos.customer">{{ shortenStr(pos.customer, 15) }}</td>
                                <td>{{ pos.po_num }}</td>
                                <td>{{ pos.part_num }}</td>
                                <td class="text-center">{{ pos.qty }}</td>
                                <td class="text-center">{{ pos.sales }}</td>
                                <td :title="pos.cust_req">{{ shortenStr(pos.cust_req, 50) }}</td>
                                <td class="text-center"><button @click="viewMore(pos.id)" class="btn btn-default btn-xs full-width"><i class="fa fa-caret-down" aria-hidden="true"></i></button></td>
                                <td class="text-center"><button @click="editPO(pos.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td class="text-center"><button @click="deletePO(pos.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <span class="alert alert-info">You currently have no purchase orders to show.</span>
                </div>
            </div>
        </transition>
        <!-- end of Invoices table Closed -->
        <!-- Start of form Container -->
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
                                    <label for="will_ship">Will Ship Date</label>
                                    <input v-model="poObj.will_ship" @blur="updateWillShip" class="form-control" type="date" name="will_ship" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input v-model="poObj.due_date" @blur="updateDueDate" class="form-control" type="date" name="due_date" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="cutomser">Customer</label>
                                    <input v-model="poObj.customer" @keyup="updateCustomer" class="form-control" type="text" name="customer" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="po_num">Purchase Order Number</label>
                                    <input v-model="poObj.po_num" @keyup="updatePurNum" class="form-control" type="text" name="po_num" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="part_num">Part Number</label>
                                    <select v-model="poObj.part_num" @blur="updatePartNum" class="form-control" name="part_num" required>
                                        <option value="CAO">Choose An Option</option>
                                        <option v-for="items in products">{{ items.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input v-model="poObj.qty" @keyup="updateQty" class="form-control" type="text" name="qty" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="stock">Stock Comments</label>
                                    <input v-model="poObj.stock" @keyup="updateStock" class="form-control" type="text" name="stock">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="sales">Sales</label>
                                    <input v-model="poObj.sales" @keyup="updateSales" class="form-control" type="number" min="0" step="0.01" name="sales">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <input v-model="poObj.rating" @keyup="updateRating" class="form-control" type="text" name="rating">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="sooner">Sooner?</label>
                                    <select v-model="poObj.sooner" @blur="updateSooner" class="form-control" name="sooner" required>
                                        <option value="CAO">Choose An Option</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="need_routers">Routers Needed?</label>
                                    <select v-model="poObj.need_routers" @keyup="updateNeedRouters" class="form-control" name="need_routers" required>
                                        <option value="CAO">Choose An Option</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cust_req">Customer Requirements</label>
                                    <textarea v-model="poObj.cust_req" @keyup="updateCustReq" class="form-control" name="cust_req" row="3" placeholder="Note: Press 'Enter' for every new customer requirement."></textarea>
                                </div>
                            </div>
                        </div>
                        <h3 v-show="edit" class="text-center">Closeing Details</h3>
                        <div v-show="edit" class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="invoice">Invoice #</label>
                                    <input v-model="poObj.invoice" @keyup="updateInvoice" class="form-control" type="text" name="invoice">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select v-model="poObj.status" @blur="updateStatus" class="form-control" name="status">
                                        <option value="CAO">Choose An Option</option>
                                        <option>Open</option>
                                        <option>Closed</option>
                                    </select>
                                </div>
                            </div>
                            <div v-show="!poObj.ship_date" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label>Has Order Shipped?</label>
                                    <button type="button" @click="setShipDate" class="btn btn-success full-width">Order Shipped!</button>
                                </div>
                            </div>
                            <div v-show="poObj.ship_date" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="ship_date">Date Shipped</label>
                                    <input v-model="poObj.ship_date" class="form-control" type="date" name="ship_date" readonly="true">
                                </div>
                            </div>
                            <div v-show="user.permission == 1" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="ship_date">Date Shipped</label>
                                    <input v-model="poObj.ship_date" @blur="updateShipDate" class="form-control" type="date" name="ship_date">
                                </div>
                            </div>
                        </div>
                        <SubmitBtns :editMode="edit" :name="name='Purchase Order'"></SubmitBtns>
                    </form>
                </div>
            </div>
        </transition>
        <!-- End of Modal Container -->
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
                        <h3 v-if="poObj.status == 'Open'">OID #: {{ moreBoxIndex }}</h3>
                        <h3 v-else>Closed PO Details:</h3>
                        <div class="row">
                            <div class="col-xs-12 col-lg-6">
                                <p>
                                    <strong>Will Ship:</strong> {{ newDate(poObj.will_ship) }} <br> 
                                    <span v-if="poObj.ship_date"><strong>Ship Date:</strong> {{ newDate(poObj.ship_date) }} <br></span> 
                                    <strong>Due Date:</strong> {{ newDate(poObj.due_date) }} <br> 
                                    <strong>Rating:</strong> {{ poObj.rating }} <br> 
                                    <strong>Sooner?:</strong> {{ poObj.sooner }} <br> 
                                    <strong>Customer:</strong> {{ poObj.customer }} <br> 
                                    <strong>P.O.#:</strong> {{ poObj.po_num }} <br> 
                                </p>     
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <p>
                                    <strong>Part #:</strong> {{ poObj.part_num }} <br> 
                                    <strong>Qty:</strong> {{ poObj.qty }} <br> 
                                    <strong>Stock:</strong> {{ poObj.stock }} <br> 
                                    <strong>Sales:</strong> {{ poObj.sales }} <br> 
                                    <strong>Need Routers?:</strong> {{ poObj.need_routers }} <br>
                                </p> 
                            </div>
                        </div>
                    </div>
                    <div class="pull-left">
                        <h3>CCR, Stock and Routers</h3>
                    </div>
                    <div class="clear-both"></div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-6">
                            <div class="row">
                                <div class="col-xs-12 light-border">
                                    <h4 class="text-center">CCR / Notes</h4>
                                    <p v-if="poObj.cust_req">{{ poObj.cust_req }}</p>
                                    <p v-else class="alert alert-info text-center">No Customer Contract Requirements</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 light-border">
                            <h4 class="text-center">Routers</h4>
                            <div v-if="poObj.routers.length > 0" class="row">
                                <div class="col-xs-12">
                                    <div class="col-xs-5 text-center">*Router #</div>
                                    <div class="col-xs-4 text-center">*Qty</div>
                                    <div class="col-xs-1 text-center">Remove</div>
                                </div>
                                <div v-for="(item, index) in poObj.routers" class="col-xs-12">
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <input type="text" @keyup="updateChangeIndexRouter" @blur="changeIndexRouter(index)" :value="item.router" name="router" class="form-control text-center">
                                        </div>
                                        <div class="col-xs-4">
                                            <input type="text" @keyup="updateChangeIndexQty" @blur="changeIndexQty(index)" :value="item.qty" name="router" class="form-control text-center">
                                        </div>
                                        <div class="col-xs-1">
                                            <button @click="removeRouter(index)" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="alert alert-info text-center">No Routers Assigned</p>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <input v-model="selected_router" type="text" name="selected_router" class="form-control" placeholder="Router #">
                                </div>
                                <div class="col-xs-6 form-group">
                                    <input v-model="selected_qty" type="text" name="selected_qty" class="form-control" placeholder="Qty">
                                </div>
                                <transition name="fade">
                                    <div v-if="selected_router && selected_qty" class="col-xs-12 form-group">
                                            <button @click="addRouter" type="button" class="btn btn-success btn-sm full-width">Add Router</button>
                                    </div>
                                </transition>
                                <transition>
                                    <div v-if="routers_added" class="col-xs-12 form-group">
                                        <button @click="updatePO(poObj.id)" type="button" class="btn btn-primary btn-sm full-width">Submit Routers Data</button>
                                    </div>
                                </transition>
                                <div class="col-xs-12">
                                    <p>*<span class="fine-print">Editable content. Tab or click out of fields before submitting.</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <!-- End of More Box -->
        <br />
        <br />
        <!-- End of add customer form -->
        <!-- Instruction Area -->
        <Instructions></Instructions>
        <!-- End of Instructions -->
    </div>
</template>
<script>
    import Utils from '../modules/utils.js';
    import Draggable from 'vuedraggable';
    import SubmitBtns from '../components/partials/submit-btn.vue';
    import Loader from '../components/partials/loader.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Instructions from '../components/info-components/purchase-order-inst.vue';
    export default {
        data(){
            return {
                form: false,
                moreBox: false,
                moreBoxIndex: '',
                edit: false,
                loading: true,
                open: false,
                closed: false,
                on_time: false,
                sales: false,
                start_date: '',
                end_date: '',
                title: '',
                show_sales: 'Choose An Option',
                show_status: 'Choose An Option',
                routers_added: false,
                search: false,
                s: '', // for being 'Open' or 'Closed' where searching
                search_po: false,
                po_search: '',
                search_cust: false,
                cust_search: '',
                search_pn: false,
                pn_search: '',
                status: 'Loading ...',
                indexRouter: '',
                indexQty: '',
                selected_router: '',
                selected_qty: '',
                successMessage: '',
                errorMessage: ''
            }
        },
        mounted() {
            Utils.openContainer();
            this.loadIn();
            this.getUser();
            this.getOpenPurchaseorders();
            this.getClosedPurchaseorders();
            this.getProducts();
        },
        components: {
            Draggable,
            ErrorMessage,
            SuccessMessage,
            Loader,
            SubmitBtns,
            Instructions
        },
        computed: {
            user() { return this.$store.getters.getUser; },
            products() { return this.$store.getters.getProducts; },
            routers() { return this.$store.getters.getRoutersByDate; },
            poObj() { return this.$store.getters.getPurchaseorderObject; },
            list_open: {
                get() {
                    return this.$store.getters.getPurchaseordersOpen;
                },
                set(value) {
                    this.$store.dispatch('sortPurchaseorders', value);
                }
            },
            list_closed() { return this.$store.getters.getPurchaseordersClosed; }
        },
        methods: {
            coor(){window.location = window.location.origin + '/pdf/coor'; },
            coorTask(){window.location = window.location.origin + '/pdf/coor/task'; },
            coorProduction(){window.location = window.location.origin + '/pdf/coor/production'; },
            loadIn(){
                this.setApplication();
            },
            setApplication(){
                let param = Utils.getUriParam();
                if(param == 'create'){
                    this.open = true;
                    this.status = 'Open Orders';
                    this.form = true;
                }else if(param == 'closed'){
                    this.closed = true;
                    this.status = 'Closed Orders';
                }else{ // if app=open
                    this.open = true;
                    this.status = 'Open Orders';
                }
            },
            // MUTATIONS
            updateDueDate(e){ this.$store.commit('updatePODueDate', e.target.value); },
            updateWillShip(e){ this.$store.commit('updatePOWillShip', e.target.value); },
            updateShipDate(e){ this.$store.commit('updatePOShipDate', e.target.value); },
            setShipDate(){ this.$store.commit('setPOShipDate'); },
            updateRating(e){ this.$store.commit('updatePORating', e.target.value); },
            updateSooner(e){ this.$store.commit('updatePOSooner', e.target.value); },
            updateCustomer(e){ this.$store.commit('updatePOCustomer', e.target.value); },
            updatePurNum(e){ this.$store.commit('updatePONum', e.target.value); },
            updatePartNum(e){ this.$store.commit('updatePOPartNum', e.target.value); },
            updateQty(e){ this.$store.commit('updatePOQty', e.target.value); },
            updateStock(e){ this.$store.commit('updatePOStock', e.target.value); },
            updateSales(e){ this.$store.commit('updatePOSales', e.target.value); },
            updateNeedRouters(e){ this.$store.commit('updatePONeedRouters', e.target.value); },
            updateAddRouters(e){ this.$store.commit('updatePOAddRouters', e.target.value); },
            updateRemoveRouters(e){ this.$store.commit('updatePORemoveRouters', e.target.value); },
            updateCustReq(e){ this.$store.commit('updatePOCustReq', e.target.value); },
            updateStatus(e){ this.$store.commit('updatePOStatus', e.target.value); },
            updateInvoice(e){ this.$store.commit('updatePOInvoice', e.target.value); },
            resetState(){ this.$store.commit('resetState'); },
            updateChangeIndexRouter(e){ this.indexRouter = e.target.value; },
            updateChangeIndexQty(e){ this.indexQty = e.target.value; },
            changeIndexRouter(i){ 
                this.$store.commit('changeIndexRouter', {value: this.indexRouter, index: i}); 
                if(this.indexRouter){ this.routers_added = true; }
            },
            changeIndexQty(i){ 
                this.$store.commit('changeIndexQty', {value: this.indexQty, index: i}); 
                if(this.indexQty){ this.routers_added = true; }
            },
            // ACTIONS
            clearAllRating(){ 
                this.$store.dispatch('clearAllRating')
                .then(()=>{
                    this.getOpenPurchaseorders();
                    this.message("All ratings cleared successfully!", 'success', 5000);
                })
                .catch((error)=>{
                    this.message("Sorry! Something went wrong when clearing your open purchase orders ratings!", 'error', 10000);
                    throw new Error("Something went wrong with clearAllRating." + error);
                });
            },
            getUser(){ this.$store.dispatch('commitPermission'); },
            getProducts(){ this.$store.dispatch('commitProducts'); },
            getOpenPurchaseorders(){ 
                this.$store.dispatch('commitOpenPurchaseorders')
                .then(() => {
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                    this.message("Sorry! Something went wrong when getting your open purchase orders!", 'error', 10000);
                    throw new Error("Something went wrong with getting your purchase orders." + error);
                });
            },
            getClosedPurchaseorders(){
                this.$store.dispatch('commitClosedPurchaseorders')
                .then(() => {
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                    this.message("Sorry! Something went wrong when getting your purchase closed orders!", 'error', 10000);
                    throw new Error("Something went wrong with getting your purchase orders." + error);
                });
            },
            // CRUDS
            createPO(){
                this.$store.dispatch('createNewPO')
                .then(() => {
                    this.resetToTable();
                    this.cancelAllSearch(true);
                    this.switchToOpen();
                    this.message("Purchase order successfully created!", 'success', 5000);
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when creating your purchase order", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for createNewPO');
                });
            },
            addRouter(){
                this.$store.commit('addPORouter', {router: this.selected_router, qty: this.selected_qty});
                this.selected_router = '';
                this.selected_qty = '';
                this.routers_added = true;
            },
            removeRouter(i){
                this.$store.commit('removePORouter', i);
                this.routers_added = true;
            },
            viewMore(id, index){
                this.$store.dispatch('showPO', id)
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
                this.$store.dispatch('showPO', id)
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
                this.$store.dispatch('updatePO', id)
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
                this.$store.dispatch('deletePO', id)
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
                this.search_pn = false; 
                this.search_cust = false;
                this.pn_search = ''; 
                this.cust_search = '';
                if(refresh){
                    this.getOpenPurchaseorders();
                    this.getClosedPurchaseorders();
                }
            },
            cancelForCust(refresh){
                this.search_po = false;
                this.search_pn = false;
                this.po_search = '';
                this.pn_search = '';
                if(refresh){
                    this.getOpenPurchaseorders();
                    this.getClosedPurchaseorders();
                }
            },
            cancelForPartNum(refresh){
                this.search_po = false;
                this.search_cust = false;
                this.po_search = '';
                this.cust_search = '';
                if(refresh){
                    this.getOpenPurchaseorders();
                    this.getClosedPurchaseorders();
                }
            },
            cancelAllSearch(refresh){
                this.search_po = false;
                this.search_cust = false;
                this.search_pn = false;
                this.po_search = '';
                this.cust_search = '';
                this.pn_search = '';
                if(refresh){
                    this.getOpenPurchaseorders();
                    this.getClosedPurchaseorders();
                }
            },
            searchPONum(){
                this.cancelForPONum(false);
                this.loading = true;
                this.open ? this.s = 'Open' : this.s = 'Closed';
                this.$store.dispatch('searchPONum', {value: this.po_search, status: this.s})
                .then(()=>{
                    this.loading = false;
                    this.s = '';
                    this.search_po = true;
                }).catch((error)=>{
                    this.message("Sorry! Something went wrong when searching for your po number", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for searchPONum');
                });
            },
            searchCust(){
                this.cancelForCust(false);
                this.loading = true;
                this.open ? this.s = 'Open' : this.s = 'Closed';
                this.$store.dispatch('searchCust', {value: this.cust_search, status: this.s})
                .then(()=>{
                    this.loading = false;
                    this.s = '';
                    this.search_cust = true;
                }).catch((error)=>{
                    this.message("Sorry! Something went wrong when searching for your customer", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for searchCust');
                });
            },
            searchPartNum(){
                this.cancelForPartNum(false);
                this.loading = true;
                this.open ? this.s = 'Open' : this.s = 'Closed';
                this.$store.dispatch('searchPartNum', {value: this.pn_search, status: this.s})
                .then(()=>{
                    this.loading = false;
                    this.s = '';
                    this.search_pn = true;
                }).catch((error)=>{
                    this.message("Sorry! Something went wrong when searching for your part number", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for searchPartNum');
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
                this.title = '';
            },
            closeSales(){
                this.sales = false;
                this.start_date = '';
                this.end_date = '';
                this.show_sales = 'Choose An Option';
                this.show_status = 'Choose An Option'
            },
            openSales(){
                this.closeSearch();
                this.closeOnTime();
                setTimeout(()=>{
                    this.sales = true;
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
                    this.status = 'Closed Orders';
                },300)
            },
            switchToOpen(){
                this.moreBox = false;
                this.closed = false;
                setTimeout(()=>{
                    this.open = true;
                    this.status = 'Open Orders';
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
                this.routers_added = false;
                this.resetState();
            },
            shortenStr(str, num){
                if(str){
                    if(str.length > num){
                        return str.substring(0, num) + '...';
                    }else{
                        return str.substring(0,str.length);
                    }
                }else{
                    return str;
                }
            },
            checkIfStr(str, ifstr){
                return str != ifstr ? '_' : str
            },
            checkIfEmpty(str){
                return str == null || str == '' ? '_' : str
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
 #poBox > div {min-width: 1480px;}
 th {text-align: center;}
 td {border: .5px solid #aaa; padding: 1px 1px 1px 3px !important;}
 .light-border {border: .5px solid #aaa;}
</style>
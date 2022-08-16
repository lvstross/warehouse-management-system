<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Part Flow Instructions</h3>
                <p>Welcome to the part flow interface. The part flow serves one major purpose: to track routers as they move from department to department. <span v-if="user.permission == 1 || user.permission == 2">To start off the partflow, you will need to go to the 'Departments' area to add and sort your departments 
                which are then fed into the partflow for you to move routers into.</span> Simply drag and drop your routers into the department or status containers. The 'Staged For Inventory' area is where routers are held that are going through any kind of processing before 
                they are submitted to Inventory. When routers are submitted to inventory, whatever quantity value is on the router will be added to the stock quantity of the router. So make sure your quantities are correct before you submit them to inventory. 
                To submit routers to inventory, simply click the 'Submit' button below. At any time, you may search routers by their router number or a part number in order to isolate [a] router(s) for better sorting.</p>
                <p>Each router in the part flow has a drop down button that will show you more information on that router. At that you point, you may toggle the move log by clicking the 'Move Log' button or you may edit a router by clicking the 'Edit' button. When 
                editing, a window will pop up with the same router form that appears in the routers area.</p>
                <p v-if="user.permission == 1 || user.permission == 2">*** QUARK ALERT *** if you are a level 1 user, be cautious when editing department data with routers in them as those routers will no longer appear on the part flow, because they depend on a department value to be sorted into. If there is no 
                department that matches that value, the routers will not show on the part flow. At this point, you can re-create another department with the same name of the one that was deleted, move the routers out of that department and then proceed to delete 
                the department again.</p>
            </div>
        </transition>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                instruction: false
            }
        },
        computed: {
            user() { return this.$store.getters.getUser; },
        },
        methods: {
            open(){
                this.instruction = true;
            }
        }
    }
</script>
<style scoped>

</style>

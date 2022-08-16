<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Departments Instructions</h3>
                <p>Welcome to the Departments interface. This is where you will create all the departments that will feed into your partflow. Each department will represent a container that you will be able to drag and drop
                routers into, allowing you to keep track of your production flow. Here are the following feilds that you will need to fill out in order to create a department.</p>
                <ol>
                    <li><strong>Department Name:</strong> The name of your department as it will appear in the partflow. Be sure to use short one word names that are under 50 characters. Spaces are also not allowed.</li>
                    <li><strong>Background Color:</strong> You can customize the background color of your department container if you would like. If not, a default color of light grey will be applied. By clicking on the feild, a color box will show up, allow you to 
                    select the color of your choice.</li>
                    <li><strong>Text Color:</strong> Along with being able to add your own background color, you may also add your own text color. If you do not chose a text color, a default color of black will be applied.</li>
                </ol>
                <p>Make sure that background color you choice fits well with the text color you choose. If you pick colors that are to close together, you may not be able to to see the department text very well. The departments list
                is also a sortable list from top to bottom. Just hover over the sorting icon on the left of the dapartments table, click down and hold in order to sort the list of departments. The order that the departments appear here 
                are how they are going to appear on the part flow.</p>
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
        methods: {
            open(){
                this.instruction = true;
            }
        }
    }
</script>
<style scoped>

</style>

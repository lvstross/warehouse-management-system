<template>
    <div>
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Importing and Exporting Data</h3>
                <p>The Import / Export section is for creating backups of your data in either an excel format or a CSV format, both of which are readable by CSV programs such as 
                microsoft excel. When you want to create a backup of your data, click either of the export buttons and the file should automaticly download in your browser. When you want to import your data files back into the system, 
                choose them with the 'choose file' buttons. <strong>Make sure that the file you are importing is a file that belongs to it's appropriate section.</strong> If your importing your customer data, make sure 
                it is imported in the customers section. When exporting your data, make sure the exported files actually contain your data before performing any actions that would clear out your
                database.</p>
                <h4>Editting Exported Files</h4>
                <p>If your some reason you desire to edit you exported files. Please abide by these rules so that they may be importable later back into the system. </p>
                <ol>
                    <li>Stick to the same format the previous entries are in. As format changes can cause errors when importing back into the database.</li>
                    <li>Do not change the column names as they are utilized for placing the correct data back into the database.</li>
                    <li>If styleing is desired, apply minimal styleing as some styling may interrupt the importing process which may cause data lose.</li>
                    <li>And finally the most important rule is <strong>don't edit the document if you don't have to</strong> as this will allow for the most amount of success when importing back into the database.
                    These documents are meant to eventually go back into the system which means they should not be tampered with. </li>
                </ol>
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

<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Users Instructions</h3>
                <p>Welcome to the users area of the dataentry system. This area is used for adding users that are able to log into the system under their own 
                login. The dataentry system is a permissions based system which allows up to four different levels of user permission.</p>
                <hr>
                <h4>How to fill out the form</h4>
                <p>Filling out the form is simple. Add the name of the user in the <strong>Name</strong> field, the email address the user will be logging in under in the <strong>Email</strong> field, a secure password of your 
                choice in the <strong>Password</strong> field and the permission level you want the user to have in the <strong>Permissions</strong> drop down menu.</p>
                <hr>
                <h4>Level One: Admins and The Super Admin</h4>
                <p>If you are able to see this page, it is because you are logged in as the <em>Super Admin User</em>. The super admin user not only has level one
                user permissions (which will be explained in a minute), you are also the first user to be entered into the users table in the database which means
                a few things.</p>
                <ol>
                    <li>The user registration area is now cut off.</li>
                    <li>You are able to delete anything in the system.</li>
                    <li>You are able to see everything in the system.</li>
                </ol>
                <p>Literally nothing is cut off from you. You have no restrictions unlike all other levels of permission. You can even add other level one (Admin) users to the system.
                However, what serparates you from other level one users is that your user account can not be deleted whereas other level one users can. In the users table above,
                you'll notice that your account has no delete button. This is done on purpose so that you won't accidentally delete you account.</p>
                <hr>
                <h4>Level Two: Assistants</h4>
                <p>An assistant, level two user is able to see all areas of the system aside from the following:</p>
                <ol>
                    <li>Settings</li>
                    <li>Users</li>
                </ol>
                <p>The assistant role is meant to aid in the invoicing, system as well as manage customer and product information. Assistants are able to edit just about anything they have
                access to; however, they are not able to delete anything from the system.</p>
                <hr>
                <h4>Level Three: Managers</h4>
                <p>A manager, level three user is able to see all areas related to production which means the following areas are restricted:</p>
                <ol>
                    <li>Settings</li>
                    <li>Users</li>
                    <li>Customers</li>
                    <li>Invoices</li>
                    <li>Purchase Orders</li>
                </ol>
                <p>The managerial role is meant to run the production side of the system.</p>
                <hr>
                <h4>Level Four: Employees</h4>
                <p>The employee role in this system is only meant to serve one purpose: to update which department a given router is in. All employee users will be directed to one page where
                they will fill out a form to submit that their router is moving from one department to another. Employees are restricted from all other areas of the system.</p>
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

<template>
    <div>
        <modal v-bind:show="show">
            <div class="modal-block">
            <modal-header @close="onClose"> Email Confirmation </modal-header>
            <modal-body>
                <h2 class="text-center">Thank you for signing up. An email with an account activation link has been sent to your email address.</h2>
            </modal-body>
            <button class="btn btn-primary btn-block" @click="onClose">Ok</button>
            </div>
        </modal>
    </div>
</template>

<script>
   import Modal from '../../components/Modal/ModalLarge.vue';
   import ModalHeader from '../../components/Modal/ModalHeader.vue';
   import ModalBody from '../../components/Modal/ModalBody.vue';

    export default {
        name: 'modal-email',
        components: {
           Modal,
           ModalHeader,
           ModalBody
        },
       props: [
          'show'
       ],
        methods: {
            onClose() {
               // Redirect home.
               this.$router.push({name: 'dashboard'})
            }
        }
    }
</script>

<style scoped>
    h2 {
        padding-top: 15px;
    }
    .btn {
        margin: 0 auto;
    }
    .modal-block{
        text-align: center;
        padding-bottom: 20px;
        margin-top: 15px;
    }
</style>

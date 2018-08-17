<template>
    <div>
        <modal class="modal-error" v-bind:show="show">
            <div class="modal-block">
            <modal-header @close="onClose"></modal-header>
            <modal-body>
                <h2 class="text-center">Please try again later.</h2>
            </modal-body>
            <button class="btn btn-primary" @click="onClose">Ok</button>
            </div>
        </modal>
    </div>
</template>

<script>
   import Modal from '../../components/Modal/ModalLarge.vue';
   import ModalHeader from '../../components/Modal/ModalHeader.vue';
   import ModalBody from '../../components/Modal/ModalBody.vue';

    export default {
        name: 'modal-error',
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
                this.$emit('close');
            }
        }
    }
</script>

<style scoped>
    .btn {
        margin: 15px auto 0 auto;
    }
    .modal-block{
        text-align: center;
        padding-bottom: 25px;
    }
    .modal-error {
        padding-top: 50px;
    }
</style>

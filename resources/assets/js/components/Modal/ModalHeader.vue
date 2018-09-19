<template>
    <div class="modal-header">
        <h5 class="modal-title text-center" style="margin: 0 auto;"><slot/></h5>
        <button type="button" style="margin-left: 0px;" class="close" @click.prevent="closeModal" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
    export default {
      name: 'modal-header',
      props: ['modalShow'],
      methods: {
        closeModal () {
          this.$emit('close');
        }
      }
    }
</script>
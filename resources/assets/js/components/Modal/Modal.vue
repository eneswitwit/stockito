<template>
    <div class="modal fade" :class="{'show': show}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      name: 'modal',
      props: ['show']
    }
</script>

<style lang="scss">
    .fade.show {
        display: block;
        background-color: rgba(#000, 0.75);
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
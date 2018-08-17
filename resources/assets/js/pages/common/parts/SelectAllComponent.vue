<template>
    <input type="checkbox" v-model="selected" @change="emitSelection">
</template>

<script>
  export default {
    name: 'select-all-component',
    data: () => ({
      selected: false
    }),
    methods: {
      emitSelection: function(){
//        this.$emit
        window.eventBus.$emit(this.selected ? 'select-all' : 'deselect-all');
      }
    },
    created: function(){
      window.eventBus.$on('deselected', function (row) {
        this.selected = false;
      }.bind(this));

      window.eventBus.$on('all-selected', function (row) {
        this.selected = true;
      }.bind(this));
    }
  }
</script>
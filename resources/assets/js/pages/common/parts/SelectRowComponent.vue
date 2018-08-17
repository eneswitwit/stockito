<template>
    <input type="checkbox" v-model="selected" @change="emitSelected">
</template>

<script>
  export default {
    name: 'select-row-component',
    props: ['row'],
    data: () => ({
      selected: false
    }),
    methods: {
      emitSelected: function () {
        if(this.selected){
          window.eventBus.$emit('selected', this.row);
        }else{
          window.eventBus.$emit('deselected', this.row);
        }
      }
    },
    created: function () {
      window.eventBus.$on('select-all', function (selected) {
        this.selected = true;
        this.$emit('change');
      }.bind(this));

      window.eventBus.$on('deselect-all', function (selected) {
        this.selected = false;
        this.$emit('change');
      }.bind(this));
    }
  }
</script>
<template>
    <div v-if="row.id">
        <router-link class="btn btn-primary btn-sm" :to="{name: 'licenses.edit', params: { id: row.id }}">Edit</router-link>
    </div>
</template>

<script>
  export default {
    name: 'select-row-component',
    props: [
        'row',
        'name'
    ],
    mounted() {
    }
  }
</script>
<template>

    <div class="main-layout">
        <link rel="shortcut icon" href="../../static/favicon.png">
        <navbar/>
        <child/>
    </div>

</template>

<script>

    import Navbar from '~/components/Navbar'

    export default {
        name: 'MainLayout',

        components: {
            Navbar
        }
    }

</script>

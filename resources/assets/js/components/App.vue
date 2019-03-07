<template>

    <div id="app">
        <loading ref="loading"/>
        <transition name="page" mode="out-in">
            <component v-if="layout" :is="layout"></component>
        </transition>

    </div>

</template>

<script>
    import Loading from './Loading';
    import axios from 'axios';

    // Load layout components dynamically.
    const requireContext = require.context('~/layouts', false, /.*\.vue$/)

    const layouts = requireContext.keys()
        .map(file =>
            [file.replace(/(^.\/)|(\.vue$)/g, ''), requireContext(file)]
        )
        .reduce((components, [name, component]) => {
            components[name] = component
            return components
        }, {})

    export default {
        el: '#app',

        components: {
            Loading
        },

        data: () => ({
            layout: null,
            defaultLayout: 'default'
        }),

        metaInfo() {
            const {appName} = window.config

            return {
                title: appName,
                titleTemplate: `%s · ${appName}`
            }
        },

        async mounted() {
            this.$loading = this.$refs.loading
            //this.setSelectedBrand();
        },

        methods: {

            getSelectedBrandId() {
                var url = window.location.href;
                var page = "uploaded/";
                var index = url.indexOf(page);
                if(index === -1) {
                    page = "medias/";
                    index = url.indexOf(page)
                }
                var substring = url.substring(index + page.length, url.length);

                var selectedBrandId = null;
                if (substring !== '') {
                    selectedBrandId = parseInt(substring);
                } else {
                    selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                }
                return selectedBrandId;
            },


            setSelectedBrand() {
                /*var selectedBrandId = this.getSelectedBrandId();
                if(selectedBrandId) {
                    this.$store.dispatch('creative/setSelectedBrandId', {selectedBrandId});
                }*/
            },

            /**
             * Set the application layout.
             *
             * @param {String} layout
             */
            setLayout(layout) {
                if (!layout || !layouts[layout]) {
                    layout = this.defaultLayout
                }

                this.layout = layouts[layout]
            }
        }
    }
</script>

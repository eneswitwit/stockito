<template>
    <card v-if="processing && (processing.queuing || processing.processing)">
        <loader v-if="requestingProcessing"></loader>
        <template v-else>
            <p><span class="badge badge-primary">{{ processing.processing }}</span> files in processing</p>
            <p><span class="badge badge-primary">{{ processing.queuing }}</span> in queue</p>
            <!-- <p><button class="btn btn-link" @click="refresh">Refresh</button></p> -->
        </template>
    </card>
</template>

<script>
    import Card from '../../../components/Card';
    import Loader from './Loader';

    export default {
        name: 'processing-component',
        components: {Loader, Card},
        created() {
            this.getProcessing();
        },
        computed: {
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            processing() {
                return this.$store.getters['media/processing'];
            },
            requestingProcessing() {
                return this.$store.getters['media/requestingProcessing'];
            }
        },
        methods: {
            async refresh() {
                this.$emit('refreshing');
                await this.getProcessing();
                this.$emit('refreshed');
            },
            async getProcessing() {
                let selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                await this.$store.dispatch('media/getProcessing', {selectedBrandId: selectedBrandId});
            }
        }
    };
</script>

<style scoped>

</style>
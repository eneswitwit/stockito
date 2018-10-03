<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 m-auto text-center">
                <loader v-if="showLoader"></loader>
                <div v-if="!showLoader">
                    <div v-if="status">
                        <card>
                            <h2> You have successfully registered. You can login now.</h2>
                            <button class="btn btn-primary btn-block" @click="goToRedirect()"> Login </button>
                        </card>
                    </div>
                    <div v-if="!status">
                        <card>
                            <h2>This user does not exist</h2>
                        </card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Loader from '../../../pages/common/parts/Loader';

    export default {
        middleware: 'guest',
        components: {
            Loader
        },
        data: () => ({
            status: false,
            showLoader: true
        }),
        beforeMount() {
            this.confirmationEmail();
        },
        methods: {
            confirmationEmail: function () {
                let token = this.$route.params.confirmationToken;

                axios.post('/api/email/confirm', {confirmationToken: token})
                    .then(response => {
                        if (response.data === true) {
                            this.status = true;
                        }
                        this.showLoader = false;
                    })
            },
            goToRedirect: function () {
                // Redirect login.
                this.$router.push({name: 'login'});
            }
        }
    }
</script>

<style scoped>
    .btn {
        margin-top: 25px;
    }
</style>

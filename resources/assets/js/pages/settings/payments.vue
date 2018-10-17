<template>
    <div>
        <div class="card">
            <div class="card-header dashboard-card">
                Payment details
            </div>

            <div class="card-body">
                <form @submit.prevent="update" @keydown="form.onKeydown($event)">
                    <div class="row mb-3">
                        <div class="col-lg-4" style="font-weight: 500;">Credit Card</div>
                        <div class="col-lg-8">Master Card</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4" style="font-weight: 500;">Number</div>
                        <div class="col-lg-8">**** **** **** 1234</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4" style="font-weight: 500;">Expiration</div>
                        <div class="col-lg-8">12/34</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4" style="font-weight: 500;">CVV</div>
                        <div class="col-lg-8">***</div>
                    </div>
                </form>
            </div>
        </div>
        <button class="btn btn-primary btn-block" @click="editPayment">Edit</button>
    </div>
</template>

<script>
    import Form from 'vform'

    export default {
        name: 'payments',
        scrollToTop: false,

        metaInfo() {
            return {title: this.$t('settings')}
        },

        data: () => ({
            form: new Form({
                password: '',
                password_confirmation: ''
            })
        }),

        methods: {
            editPayment() {

            },
            async update() {
                await this.form.patch('/api/settings/password')

                this.form.reset()
            }
        }
    }
</script>

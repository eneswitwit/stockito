<template>
    <card :title="$t('payment_details')">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
            <div class="row mb-3">
                <div class="col-lg-4 text-bold">Credit Card</div>
                <div class="col-lg-8">Master Card</div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-4 text-bold">Number</div>
                <div class="col-lg-8">**** **** **** 1234</div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-4 text-bold">Expiration</div>
                <div class="col-lg-8">12/34</div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-4 text-bold">CVV</div>
                <div class="col-lg-8">***</div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-4">
                    <button class="btn btn-primary" @click="editPayment">Edit</button>
                </div>
            </div>
        </form>
    </card>
</template>

<script>
  import Form from 'vform'

  export default {
    name: 'payments',
    scrollToTop: false,

    metaInfo () {
      return { title: this.$t('settings') }
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
      async update () {
        await this.form.patch('/api/settings/password')

        this.form.reset()
      }
    }
  }
</script>

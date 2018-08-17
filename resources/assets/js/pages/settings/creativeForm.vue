<template>
	<form @submit.prevent="update" @keydown="form.onKeydown($event)">
		<alert-success :form="form" :message="$t('info_updated')"/>

		<!--First Name -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
			<div class="col-md-7">
				<input v-model="form.creative.first_name" type="text" name="first_name" class="form-control"
					   :class="{ 'is-invalid': form.errors.has('name') }">
				<has-error :form="form" field="creative.first_name"/>
			</div>
		</div>

		<!--Last Name -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
			<div class="col-md-7">
				<input v-model="form.creative.last_name" type="text" name="last_name" class="form-control"
					   :class="{ 'is-invalid': form.errors.has('name') }">
				<has-error :form="form" field="creative.last_name"/>
			</div>
		</div>

		<!--Company -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('company') }}</label>
			<div class="col-md-7">
				<input v-model="form.creative.company" type="text" name="company" class="form-control"
					   :class="{ 'is-invalid': form.errors.has('name') }">
				<has-error :form="form" field="creative.company"/>
			</div>
		</div>

		<!-- Email -->
		<div class="form-group row">
			<label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
			<div class="col-md-7">
				<input v-model="form.email" type="email" name="email" class="form-control"
					   :class="{ 'is-invalid': form.errors.has('email') }">
				<has-error :form="form" field="email"/>
			</div>
		</div>

		<!-- Submit Button -->
		<div class="form-group row">
			<div class="col-md-9 ml-md-auto">
				<v-button type="success" :loading="form.busy">{{ $t('update') }}</v-button>
				<button type="button" v-on:click="deleteCreative" class="btn btn-danger">{{ $t('delete') }}</button>
			</div>
		</div>
	</form>
</template>

<script>
    import Form from 'vform'
    import {mapGetters} from 'vuex'
    import swal from 'sweetalert2'

    export default {
        name: "creative-form",
        scrollToTop: false,

        metaInfo() {
            return {title: this.$t('settings')}
        },

        data: () => ({
            form: new Form({
                email: '',
                creative: ''
            })
        }),

        computed: mapGetters({
            user: 'auth/user'
        }),

        created() {
            this.form.keys().forEach(key => {
                this.form[key] = this.user[key]
            })
        },

        methods: {
            async update() {
                const {data} = await this.form.patch('/api/settings/profile/creative');

                this.$store.dispatch('auth/updateUser', {user: data})
            },
            async deleteCreative() {
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value === true) {
                        swal(
                            'Deleted!',
                            'Your profile has been deleted.',
                            'success'
                        );
                        this.$store.dispatch('auth/delete').then( () => {
                            this.$router.push({name: 'login'});
                        });
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
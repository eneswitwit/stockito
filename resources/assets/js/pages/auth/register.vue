<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
		<div class="row">
			<div class="col-lg-6">
				<card :title="$t('register_creative')">
					<router-link class="btn btn-lg btn-success" :to="{ name: 'register.creative' }">
						{{ $t('creative') }}
					</router-link>
				</card>
			</div>
			<div class="col-lg-6">
				<card :title="$t('register_brand')">
					<router-link class="btn btn-lg btn-success" :to="{ name: 'register.brand' }">
						{{ $t('brand') }}
					</router-link>
				</card>
			</div>
		</div>
    </div>
  </div>
</template>

<script>
    export default {
        middleware: 'guest',
        name: 'register',
    }
</script>

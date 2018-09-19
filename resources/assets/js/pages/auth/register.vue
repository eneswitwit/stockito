<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="row">
                    <div class="col-lg-6">
                        <card :title="$t('register_creative')">
                            <img :src="require('../../../images/creative.jpg')"/>
                            <ul>
                                <li>Access unlimited brands</li>
                                <li> Manage media files of brands</li>
                                <li>License Manager</li>
                                <li>Team Collaboration</li>
                            </ul>
                            <router-link class="btn btn-sm btn-primary btn-block" :to="{ name: 'register.creative' }">
                                {{ $t('register') }}
                            </router-link>
                        </card>
                    </div>
                    <div class="col-lg-6">
                        <card :title="$t('register_brand')">
                            <img :src="require('../../../images/brand.jpg')"/>
                            <ul>
                                <li> Storage for your media files</li>
                                <li> License Manager</li>
                                <li>Team Collaboration</li>
                                <li>Analytics &amp; Reports</li>
                            </ul>
                            <router-link class="btn btn-sm btn-primary btn-block" :to="{ name: 'register.brand' }">
                                {{ $t('register') }}
                            </router-link>
                        </card>
                    </div>
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

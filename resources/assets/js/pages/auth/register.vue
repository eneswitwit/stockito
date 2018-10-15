<template>
    <div class="container-fluid mt-4">

        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="row" style="font-family: 'Montserrat', sans-serif !important;">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                For Creatives/Agencies
                            </div>
                            <div class="card-body" style="padding: 0;">
                                <img style="height: 400px; width: 100%; border-radius: 0 0 10px 10px"
                                     :src="require('../../../images/creative.jpg')"/>
                            </div>
                        </div>
                        <ul class="signup mb-4">
                            <li>Access unlimited brands</li>
                            <li>Manage media files of brands</li>
                            <li>License Manager</li>
                            <li>Team Collaboration</li>
                        </ul>
                        <router-link class="btn btn-sm btn-primary btn-block" :to="{ name: 'register.creative' }">
                            {{ $t('register') }}
                            <span style="margin-left: 10px; text-transform: uppercase; color: black;"> Always free </span>
                        </router-link>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                For Brands/Enterprises
                            </div>
                            <div class="card-body" style="padding: 0;">
                                <img style="height: 400px; width: 100%; border-radius: 0 0 10px 10px"
                                     :src="require('../../../images/brand.jpg')"/>
                            </div>
                        </div>
                        <ul class="signup mb-4">
                            <li>Storage for your media files</li>
                            <li>License Manager</li>
                            <li>Team Collaboration</li>
                            <li>Analytics &amp; Reports</li>
                        </ul>
                        <router-link class="btn btn-sm btn-primary btn-block" :to="{ name: 'register.brand' }">
                            {{ $t('register') }}
                            <span style="margin-left: 10px; text-transform: uppercase; color: black;"> from 49 € / Year </span>
                        </router-link>
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

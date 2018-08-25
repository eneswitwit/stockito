<template>
    <div>
        <subscription-details-component></subscription-details-component>
        <div class="card">
            <div class="card-header"><h4 class="card-title">Invoices</h4></div>
            <table class="table">
                <thead>
                <tr>
                    <th>Number</th>
                    <th>Date</th>
                    <th>Paid</th>
                    <th>Invoice</th>
                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="invoice in invoices">
                    <td>{{ invoice.number }}</td>
                    <td>{{ invoice.date }}</td>
                    <td>
                        <span v-show="invoice.paid" class="badge badge-success">Paid</span>
                        <span v-show="!invoice.paid" class="badge badge-danger">Not Paid</span>
                    </td>
                    <td>{{ invoice.invoiceFileName }}</td>
                    <td>
                        <a :href="invoice.downloadPdfUrl" class="btn btn-primary btn-sm">Download</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import SubscriptionDetailsComponent from '../brand/parts/SubscriptionDetailsComponent.vue';
    import axios from 'axios';
    import Card from '../../components/Card.vue';

    export default {
        components: {
            Card,
            SubscriptionDetailsComponent
        },
        name: 'subscription-invoices',
        created() {
            this.getInvoices();
        },
        data: () => ({
            invoices: [],
        }),
        methods: {
            getInvoices() {
                axios.get('/api/brand/invoices').then(({data}) => {
                    this.invoices = data;
                })
            }
        }
    }
</script>
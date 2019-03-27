<template>

    <modal :show.sync="show" @close="$emit('update:show', false)">

        <modal-header @close="$emit('update:show', false)">
            Set License
        </modal-header>

        <modal-body>
            <form action="" @keydown="form.onKeydown($event)">
                <div v-show="form.type == 2" class="form-group">
                    <label for="form-usage">Usage</label>
                    <input id="form-usage" class="form-control" :class="{ 'is-invalid': form.errors.has('usage') }"
                           type="text"
                           name="usage" v-model="form.usage">
                    <has-error :form="form" field="usage"/>
                </div>
                <div v-show="[1,2].indexOf(parseInt(form.type)) != -1" class="form-group">
                    <label for="form-printrun">Printrun</label>
                    <select v-if="form.type != 2" id="form-printrun" class="form-control"
                            :class="{ 'is-invalid': form.errors.has('printrun') }"
                            name="printrun" v-model="form.printrun">
                        <option value="+ unlimited printrun (standard setting)">+ unlimited printrun (standard
                            setting)
                        </option>
                        <option value="+ 250.000 pcs">+ 250.000 pcs</option>
                        <option value="+ 500.000 pcs">+ 500.000 pcs</option>
                        <option value="+ other (check license)">+ other (check license)</option>
                    </select>
                    <div v-if="form.type == 2" class="input-group">
                        <input id="form-printrun" class="form-control" name="printrun"
                               :class="{ 'is-invalid': form.errors.has('printrun') }" type="text"
                               v-model="form.printrun">
                        <div class="input-group-append">
                            <span class="input-group-text">pcs</span>
                        </div>
                    </div>
                    <has-error :form="form" field="printrun"/>
                </div>
                <div v-show="form.type == 4" class="form-group">
                    <label for="form-any-limitations">Any Limitations</label>
                    <textarea id="form-any-limitations" class="form-control"
                              :class="{ 'is-invalid': form.errors.has('anyLimitations') }" name="anyLimitations"
                              v-model="form.anyLimitations"></textarea>
                    <has-error :form="form" field="anyLimitations"/>
                </div>
                <div v-show="form.type != 1" class="form-group">
                    <div class="form-row">
                        <div class="col">
                            <label for="form-start-date">Start Date</label>
                            <date-picker :class="{ 'is-invalid': form.errors.has('startDate') }" id="form-start-date"
                                         :date="date.startDate" :option="dateOptions"></date-picker>
                            <!--<input id="form-start-date" class="form-control" type="text" v-model="form.startDate">-->
                            <has-error :style="{display: 'block'}" :form="form" field="startDate"/>
                        </div>
                        <div class="col">
                            <label for="form-expire-date">Expiry Date</label>
                            <date-picker :class="{ 'is-invalid': form.errors.has('expireDate') }" id="form-expire-date"
                                         :date="date.expireDate" :option="dateOptions"></date-picker>
                            <!--<input id="form-expire-date" class="form-control" type="text" v-model="form.expireDate">-->
                            <has-error :style="{display: 'block'}" :form="form" field="expireDate"/>
                        </div>
                    </div>
                </div>
                <div v-show="form.type == 3" class="form-group">
                    <label for="form-territory">Territory</label>
                    <input id="form-territory" class="form-control"
                           :class="{ 'is-invalid': form.errors.has('territory') }"
                           type="text" v-model="form.territory">
                    <has-error :form="form" field="territory"/>
                </div>
                <div class="form-group">
                    <label for="form-invoice-number">Invoice number</label>
                    <div class="form-row">
                        <div class="col">
                            <input id="form-invoice-number" class="form-control"
                                   :class="{ 'is-invalid': form.errors.has('invoiceNumber') }" type="text"
                                   v-model="form.invoiceNumber">
                            <has-error :form="form" field="invoiceNumber"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-file">
                        <input id="form-bill-file" class="custom-file-input" name="billFile"
                               :class="{ 'is-invalid': form.errors.has('billFile') }" @change="setFile" type="file"
                               ref="fileInput">
                        <has-error :form="form" field="billFile"/>
                        <label id="fileName" class="custom-file-label" for="form-bill-file">
                            Upload license invoice
                        </label>
                    </div>
                </div>

                <div v-if="license && license.billFile && !form.removeBill" class="form-group">
                    <div class="input-group">
                        <a :href="license.url" class="btn btn-link">
                            Download bill file ({{ license.billFileOriginName }})
                        </a>
                        <button @click="form.removeBill = true" type="button" class="btn btn-danger btn-sm float-right">
                            x
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" v-if="!form.id" @click="submitCreateLicense" :disabled="form.busy"
                            class="btn btn-primary btn-block">{{ $t('save') }}
                    </button>
                    <button type="button" v-if="form.id" @click="submitEditLicense" :disabled="form.busy"
                            class="btn btn-primary btn-block">{{ $t('edit') }}
                    </button>
                </div>
            </form>
        </modal-body>

    </modal>
</template>

<script>

    import DatePicker from 'vue-datepicker';
    import Modal from '../Modal/ModalLarge.vue';
    import ModalHeader from '../Modal/ModalHeader.vue';
    import ModalBody from '../Modal/ModalBody.vue';
    import LicenseFormComponent from '../../pages/common/parts/forms/LicenseFormComponent.vue';
    import axios from 'axios';
    import moment from 'moment';
    import Card from '../Card.vue';
    import Vform from 'vform';
    import {mapGetters} from 'vuex';

    export default {

        name: 'set-usage-license-modal',

        components: {
            LicenseFormComponent,
            ModalBody,
            ModalHeader,
            Modal,
            DatePicker,
            Card
        },

        data: () => ({
            licenseTypes: [],

            form: new Vform({
                id: '',
                licenseId: '',
                type: '',
                usage: '',
                printrun: '',
                anyLimitations: '',
                startDate: '',
                expireDate: '',
                territory: '',
                invoiceNumber: '',
                invoiceNumberBy: '',
                billFile: null,
                removeBill: false,
            }),

            date: {
                startDate: {
                    time: moment().format('YYYY-MM-DD'),
                },
                expireDate: {
                    time: moment().format('YYYY-MM-DD'),
                },
            },

            dateOptions: {
                type: 'day',
                week: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
                month: [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'],
                format: 'YYYY-MM-DD',
                placeholder: '',
                inputStyle: {
                    'display': 'block',
                    'width': '100%',
                    'padding': '.375rem .75rem',
                    'font-size': '1rem',
                    'line-height': '1.5',
                    'color': '#495057',
                    'background-color': '#fff',
                    'background-clip': 'padding-box',
                    'border': '1px solid #ced4da',
                    'border-radius': '.25rem',
                    'transition': 'border-color .15s ease-in-out,box-shadow .15s ease-in-out',
                },

                color: {
                    header: '#ccc',
                    headerText: '#f00',
                },

                buttons: {
                    ok: 'Ok',
                    cancel: 'Cancel',
                },

                overlayOpacity: 0.5, // 0.5 as default
                dismissible: true // as true as default
            },
        }),

        props: [
            'show',
            'license',
            'parentLicense'
        ],

        created() {
            axios.get('/api/licenses/types-long').then(({data}) => {
                this.licenseTypes = data;
            });
        },

        mounted() {

            if(this.parentLicense) {
                this.form.type = this.parentLicense.license_type;
                this.form.licenseId = this.parentLicense.id;
            } else if (this.license) {
                this.form.type = this.license.license_type;
                this.form.licenseId = this.license.id;
            }


            if (this.license) {
                this.form.id = this.license.id;
                this.form.usage = this.license.usage;
                this.form.printrun = this.license.printrun;
                this.form.expireDate = this.license.expiredAt ? this.license.expiredAt.Ymd : '';
                this.form.startDate = this.license.startAt ? this.license.startAt.Ymd : '';
                this.form.invoiceNumber = this.license.invoiceNumber;
                this.form.invoiceNumberBy = this.license.invoiceNumberBy;
                this.form.territory = this.license.territory;
                this.form.anyLimitations = this.license.anyLimitations;
                this.date.startDate.time = this.license.startAt ? this.license.startAt.Ymd : '';
                this.date.expireDate.time = this.license.expiredAt ? this.license.expiredAt.Ymd : '';
            } else {
                this.form.id = null;
                this.form.usage = '';
                this.form.printrun = '';
                this.form.expireDate = '';
                this.form.startDate = '';
                this.form.invoiceNumber = '';
                this.form.invoiceNumberBy = '';
                this.form.territory = '';
                this.form.anyLimitations = '';
                this.date.startDate.time = '';
                this.date.expireDate.time = '';
            }

        },

        computed: mapGetters({
            selectedBrand: 'creative/selectedBrand',

        }),

        watch: {
            license() {


                if(this.parentLicense) {
                    this.form.type = this.parentLicense.license_type;
                    this.form.licenseId = this.parentLicense.id;
                } else if (this.license) {
                    this.form.type = this.license.license_type;
                    this.form.licenseId = this.license.id;
                }

                if (this.license) {
                    this.form.id = this.license.id;
                    this.form.usage = this.license.usage;
                    this.form.printrun = this.license.printrun;
                    this.form.expireDate = this.license.expiredAt ? this.license.expiredAt.Ymd : '';
                    this.form.startDate = this.license.startAt ? this.license.startAt.Ymd : '';
                    this.form.invoiceNumber = this.license.invoiceNumber;
                    this.form.invoiceNumberBy = this.license.invoiceNumberBy;
                    this.form.territory = this.license.territory;
                    this.form.anyLimitations = this.license.anyLimitations;
                    this.date.startDate.time = this.license.startAt ? this.license.startAt.Ymd : '';
                    this.date.expireDate.time = this.license.expiredAt ? this.license.expiredAt.Ymd : '';
                }
            }
        },


        methods: {

            submitCreateLicense() {
                this.form.licenseId = this.parentLicense.id;
                this.form.startDate = this.date.startDate.time;
                this.form.expireDate = this.date.expireDate.time;
                this.form.selectedBrand = this.selectedBrand ? this.selectedBrand.id : '';

                this.$store.dispatch('license/createUsageLicense', {form: this.form}).then(({data}) => {
                    delete this.form.billFile;
                    delete this.form.removeBill;
                    this.$swal('Successfully updated licenses', '', 'success');
                    this.$emit('saved', data);
                    this.$emit('update:show', false)
                    this.form.reset();
                });
            },

            submitEditLicense() {
                this.form.startDate = this.date.startDate.time;
                this.form.expireDate = this.date.expireDate.time;

                this.$store.dispatch('license/updateLicense', {form: this.form}).then(({data}) => {
                    delete this.form.billFile;
                    delete this.form.removeBill;
                    this.$swal('Successfully updated', '', 'success');
                    this.$emit('saved', data);
                    this.$emit('update:show', false)
                    this.form.reset();
                });
            },

            setFile(e) {
                this.form.billFile = e.target.files[0];
                document.getElementById('fileName').innerHTML = e.target.files[0]['name'];
            }
        }
    }
</script>
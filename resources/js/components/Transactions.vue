<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="row mb-5">
                <div class="col-sm-3">
                    <button disabled type="button" class="btn btn-primary opacity-100">Balance <span class="badge bg-secondary">{{ walletBalance }} {{ authUser?.currency }}</span></button>
                </div>

                <div class="col-sm-3 text-center">
                    <button disabled type="button" class="btn btn-warning opacity-100 text-dark">Sent Money <span class="badge bg-secondary">{{ totalSentMoney }} {{ authUser?.currency }}</span></button>
                </div>

                <div class="col-sm-3 text-center">
                    <button disabled type="button" class="btn btn-success opacity-100">Received Money <span class="badge bg-secondary">{{ totalReceivedMoney }} {{ authUser?.currency }}</span></button>
                </div>

                <div class="col-sm-3 text-end">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#send_money_modal" @click="resetSendMoney">Send Money</button>
                </div>
            </div>
            <select id="" class="form-select my-2" v-model="transaction_type">
                <option value="transactions">All Transactions</option>
                <option value="sent-money">Sent Money Transactions</option>
                <option value="received-money">Received Money Transactions</option>
            </select>

            <div v-for="(transaction, index) in transactions" :key="index">
                <div class="col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-sm-4 float-start">
                                    <div v-if="transaction?.sender_id == authUser?.id">
                                        <h6>To {{ transaction?.receiver.name }} ({{ transaction?.receiver.phone }})</h6>
                                        <div><small> <font-awesome-icon icon="fa-regular fa-clock" /> {{ transaction?.updated_at }}</small></div>
                                    </div>
                                    <div v-else>
                                        <h6>From {{ transaction?.sender.name }} ({{ transaction?.sender.phone }})</h6>
                                        <small> <font-awesome-icon icon="fa-regular fa-clock" /> {{ transaction?.updated_at }}</small>
                                    </div>
                                </div>

                                <div class="col-sm-4 text-center">
                                    <div v-if="transaction?.sender_id == authUser?.id">
                                        <div><small> {{ transaction?.sender.currency }} to {{ transaction?.receiver.currency }}</small></div>
                                        <div><small> {{ transaction?.sent_amount }} to {{ transaction?.received_amount }}</small></div>
                                    </div>
                                    <div v-else>
                                        <div><small> {{ transaction?.sender.currency }} to {{ transaction?.receiver.currency }}</small></div>
                                        <div><small> {{ transaction?.sent_amount }} to {{ transaction?.received_amount }}</small></div>
                                    </div>
                                </div>

                                <div class="col-sm-4 float-end text-end">
                                    <div v-if="transaction?.sender_id == authUser?.id">
                                        <h6 class="text-warning"> - {{ transaction?.sent_amount }} {{ transaction?.sender.currency }}</h6>
                                        <div><small> Rate: {{ transaction?.conversion_rate }} </small></div>
                                    </div>
                                    <div v-else>
                                        <h6 class="text-success">+ {{ transaction?.received_amount }} {{ transaction?.receiver.currency }}</h6>
                                        <div><small> Rate: {{ transaction?.conversion_rate }}</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="transactions?.length == 0" class="border rounded text-center text-warning">
                <h6 class="m-3">No records were found!</h6>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="send_money_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Send Money</h5>
                    <button type="button" id="send_money_modal_close" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="is_send_money_initiated" class="row">
                        <div class="col-sm-6 text-end">
                            <div>Receiver name</div>
                            <div>Receiver account</div>
                            <div>Send amount</div>
                            <div>converted Amount</div>
                            <div>Conversion</div>
                            <div>Conversion rate</div>
                        </div>
                        <div  class="col-sm-6 text-start">
                            <div>{{ send_money_init_data?.receiver_name }}</div>
                            <div>{{ send_money_init_data?.receiver_identity }}</div>
                            <div>{{ send_money_init_data?.sent_amount }} {{ send_money_init_data?.sent_currency }}</div>
                            <div>{{ send_money_init_data?.converted_amount }} {{ send_money_init_data?.converted_currency }}</div>
                            <div>{{ send_money_init_data?.sent_currency }} to {{ send_money_init_data?.converted_currency }}</div>
                            <div>{{ send_money_init_data?.conversion_rate }}</div>
                        </div>
                    </div>
                    <div v-else>
                        <form>
                            <div class="mb-3">
                                <label for="" class="form-label">Receiver Email / Phone</label>
                                <input type="text" class="form-control" :class="{'is-invalid' : send_money_errors?.receiver_identity?.[0]}" v-model="send_money.receiver_identity" required>
                                <div class="invalid-feedback"> {{ send_money_errors?.receiver_identity?.[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Amount</label>
                                <input type="number" class="form-control" :class="{'is-invalid' : send_money_errors?.amount?.[0]}" v-model="send_money.amount" required>
                                <div class="invalid-feedback"> {{ send_money_errors?.amount?.[0] }}</div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <div v-if="is_send_money_initiated">
                        <button type="button" class="btn btn-warning me-3" @click="completeSendMoney('cancel')">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="completeSendMoney('success')">Make Payment</button>
                    </div>
                    <div v-else>
                        <button type="button" class="btn btn-primary" @click="initiateSendMoney">Procced</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { ref } from 'vue';
    import { useAxios } from '@/composables/useAxios.js';

    export default {
        name: "Transactions",
        async setup(){
            let req_url = ref('/transactions');
            let req_config = ref({
                method: 'GET',
                data: {}
            });

            const { excecuteAxios } = useAxios();

            // return { req_url, req_config, excecuteAxios };

            const { axios_result, axios_errors, is_axios_finished } = await useAxios(req_url, req_config);

            return { req_url, req_config, axios_result, axios_errors, is_axios_finished, excecuteAxios };
        },
        data: () => ({
            transaction_type: 'transactions',
            walletBalance: 0,
            totalSentMoney: 0,
            totalReceivedMoney: 0,
            transactions: [],
            send_money: {
                receiver_identity: '',
                amount: ''
            },
            send_money_errors: {},
            is_send_money_initiated: false,
            send_money_init_data: {}
        }),
        created() {
            console.log('Transactions Page Created');
        },
        mounted() {
            //
        },
        watch:{
            axios_result: {
                handler(newValue, oldValue) {
                    this.walletBalance = newValue?.data ? newValue?.data?.walletBalance : 0;
                    this.totalSentMoney = newValue?.data ? newValue?.data?.totalSentMoney : 0;
                    this.totalReceivedMoney = newValue?.data ? newValue?.data?.totalReceivedMoney : 0;
                    this.transactions = newValue?.data ? newValue?.data?.transactions : [];

                    this.$emitter.emit('loadingStatus', false);
                },
                deep: true
            },
            axios_errors: {
                handler(newValue, oldValue) {
                    this.reqErrorMessage = newValue?.message;
                    this.$emitter.emit('loadingStatus', false);
                },
                deep: true
            },
            transaction_type: function(newValue, oldValue){
                // if(newValue != oldValue){
                    this.$emitter.emit('loadingStatus', true);

                    if(newValue === 'transactions'){
                        this.req_url = '/transactions';
                    } else if(newValue === 'sent-money'){
                        this.req_url = '/sent-money-transactions';
                    } else if(newValue === 'received-money'){
                        this.req_url = '/received-money-transactions';
                    }
                // }
            }
        },
        methods: {
            resetSendMoney: function(){
                this.send_money.receiver_identity = '';
                this.send_money.amount = '';

                this.send_money_errors = {};
                this.is_send_money_initiated = false;
                this.send_money_init_data = {};
            },
            async initiateSendMoney() {
                this.$emitter.emit('loadingStatus', true);
                let req_url = '/initiate-send-money';
                let req_config = {
                    method: 'POST',
                    data: this.send_money
                }

                const { result, errors, is_finished } = await this.excecuteAxios(req_url, req_config);

                if(is_finished){
                    this.$emitter.emit('loadingStatus', false);

                    if(!errors){
                        this.is_send_money_initiated = true;
                        this.send_money_init_data = result?.data;
                    } else if(errors.message === 'Validation failed!'){

                        this.send_money_errors = errors.errors;
                    }
                }
            },
            async completeSendMoney($status) {
                this.$emitter.emit('loadingStatus', true);
                let req_url = '/complete-send-money/'+this.send_money_init_data?.transaction_id;
                let req_config = {
                    method: 'PUT',
                    data: {status: $status}
                }

                const { result, errors, is_finished } = await this.excecuteAxios(req_url, req_config);

                if(is_finished){
                    this.$emitter.emit('loadingStatus', false);

                    if(!errors){
                        document.getElementById('send_money_modal_close').click();

                        this.resetSendMoney();

                        this.transaction_type = this.transaction_type == 'sent-money' ? 'transactions' : 'sent-money';
                    }
                }
            }
        }
    }
</script>

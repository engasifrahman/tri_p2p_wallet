<template>
    <div class="row">
        <div class="col-sm-4">
            <div class="card text-center">
                 <h5 class="card-header">Wallet Balance</h5>
                <div class="card-body">
                    {{ statistics?.walletBalance }} {{ authUser?.currency }}
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card text-center">
                 <h5 class="card-header">Total Sent Money</h5>
                <div class="card-body">
                    {{ statistics?.totalSentMoney }} {{ authUser?.currency }}
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card text-center">
                 <h5 class="card-header">Total Received Money</h5>
                <div class="card-body">
                    {{ statistics?.totalReceivedMoney }} {{ authUser?.currency }}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <div class="card text-center">
                 <h5 class="card-header">Top Sender of The System</h5>
                <div v-if="statistics?.topSender == 'N/A'" class="card-body">
                    {{ statistics?.topSender }}
                </div>
                <div v-else class="card-body">
                    {{ statistics?.topSender?.sender?.name }} ({{ statistics?.topSender?.total_sent_money }} {{ statistics?.topSender?.sender?.currency }})
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card text-center">
                 <h5 class="card-header">Third Highest Amount of Sent Money</h5>
                <div class="card-body">
                    {{ statistics?.thirdHighestTrxAmount }} {{ statistics?.thirdHighestTrxAmount !== 'N/A' ? authUser.currency : '' }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { ref } from 'vue';
    import { useAxios } from '@/composables/useAxios.js';

    export default {
        name: "Statistics",
        async setup(){
            let req_url = ref('/statistics');
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
            statistics: {}
        }),
        created() {
            console.log('Statistics Page Created');

            this.$emitter.emit('loadingStatus', true);
        },
        mounted() {
            //
        },
        watch:{
            axios_result: {
                handler(newValue, oldValue) {
                    this.statistics = newValue?.data ? newValue?.data : {};
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
            }
        },
    }
</script>

<template>
    <ul class="list-group">
        <li class="list-group-item">
            <RouterLink class="nav-link text-center" to="/dashboard/statistics">Statistics</RouterLink>
        </li>
        <li class="list-group-item">
            <RouterLink class="nav-link text-center" to="/dashboard/transactions">Transactions</RouterLink>
        </li>
    </ul>
</template>

<script>
    import { RouterLink } from 'vue-router';

    export default {
        name: "SideMenu",
        async setup(){
            //
        },
        data: () => ({
            //
        }),
        created() {
            console.log('Side Menu Created');
        }
    }
</script>

<style scoped>
    .router-link-exact-active{
        color: #0DCAF0 !important;
    }
</style>

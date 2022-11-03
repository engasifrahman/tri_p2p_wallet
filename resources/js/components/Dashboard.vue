<template>
    <div class="row mt-3">
        <div class="col-sm-2">
            <SideMenu />
        </div>
        <div class="col-sm-10">
            <RouterView />
        </div>
    </div>

</template>

<script>
    import { RouterView } from 'vue-router';
    import SideMenu from '@/components/SideMenu.vue';

    export default {
        name: "Dashboard",
        async setup(){
            //
        },
        components: {
            SideMenu
        },
        data: () => ({
            //
        }),
        created() {
            console.log('Dashboard Page Created');

            if(!this.authToken){
                this.$router.push({name: 'login'});
            }
        }
    }
</script>

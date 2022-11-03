<template>
    <div class="row text-secondary text-center vertically-center">
        <h1>Welcome to TRI P2P Wallet</h1>
    </div>

</template>

<script>
    export default {
        name: "Home",
        async setup(){
            //
        },
        data: () => ({
            //
        }),
        created() {
            console.log('Home Page Created');
        }
    }
</script>
<style scoped>
    .vertically-center{
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 100px);
    }
</style>

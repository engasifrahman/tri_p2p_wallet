<template>
  <div v-if="loadingStatus" id="preloader" class="overlay" role="status">
    <div class="spinner-grow" role="status"></div>
  </div>

  <div>
    <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <RouterLink class="navbar-brand" to="/">TRI P2P Wallet</RouterLink>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul v-if="authToken" class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <RouterLink class="nav-link" to="/dashboard">Dashboard</RouterLink>
                </li>
            </ul>

            <ul v-else class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <RouterLink class="nav-link" to="/signup">Signup</RouterLink>
                </li>
                <li class="nav-item">
                    <RouterLink class="nav-link" to="/login">Login</RouterLink>
                </li>
            </ul>
          </div>
          <div v-if="authToken" class="d-flex me-3">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ authUser?.name }} [{{ authUser?.currency }}]
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li>
                    <a class="dropdown-item" href="#" @click="logout">Logout</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <div class="container">
      <Suspense>
        <!-- main content -->
        <RouterView />

        <!-- loading state -->
        <template #fallback>
          Suspense Loader...
        </template>
      </Suspense>
    </div>
</div>

</template>

<script>
  import { ref } from 'vue';
  import { useAxios } from '@/composables/useAxios.js';
  import { RouterLink, RouterView } from 'vue-router';

  export default {
    name: "App",
    data: () => ({
      loadingStatus: false,
    }),
    setup(){
      let req_url = ref('/login');
      let req_config = ref({
          method: 'POST',
          data: {}
      });

      const { excecuteAxios } = useAxios();

      return { req_url, req_config, excecuteAxios };
    },
    created() {
      console.log('App Created');

      this.$emitter.on("loadingStatus", payload => {
        this.loadingStatus = payload;
      });

      this.$emitter.on("reloadAuthData", payload => {
        if(payload){
          this.reloadAuthData();
        }
      });
    },
    watch: {
        //
    },
    methods: {
      async logout () {
        this.loadingStatus = true;

        this.req_url = '/logout';
        this.req_config.method = 'GET';

        const { result, errors, is_finished } = await this.excecuteAxios(this.req_url, this.req_config);

        if(is_finished){
          this.loadingStatus = false;

        //   console.log('logout response :>> ', result);

          if(!errors){
            localStorage.removeItem("auth_user");
            localStorage.removeItem("auth_token");

            this.reloadAuthData();

            this.$router.push({name: 'login'});
          }
        }
      }
    }
  };
</script>

<style scoped>
   #preloader{
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
   }
   .overlay {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0,0,0,0.5);
      z-index: 999999;
      cursor: pointer;
   }
  .router-link-active,
  .router-link-exact-active{
    color: black;
  }
</style>

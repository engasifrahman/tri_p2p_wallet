import axios from "axios";
import { myToast } from '@/assets/myToast.js';
import { ref, isRef, unref, watchEffect } from 'vue';

export function useAxios(req_url = null, req_config = {}) {
  const is_axios_finished = ref(false);
  const axios_result = ref(null);
  const axios_errors = ref(null);
  let return_data = { axios_result, axios_errors, is_axios_finished };

  async function makeRequest() {
    // reset state before fetching..
    axios_result.value = null;
    axios_errors.value = null;
    is_axios_finished.value = false;

    // resolve the url value synchronously so it's tracked as a
    // dependency by watchEffect()
    const url = unref(req_url);
    const config = unref(req_config);
    const method = config?.method || 'GET';
    const data = config?.data || {};

    if(method && url){
      await axios({
        method: method,
        url: url,
        data: data
      }).then((res) => {
        // console.log('res :>> ', res);
        if(res.data.success){
          if(res.data?.message){
            myToast('success', res.data.message);
          }

          axios_result.value = Object.assign({}, res.data);
        } else{
          myToast('error', res.data?.message || 'Something went wrong!');

          axios_errors.value = Object.assign({}, res.data);
        }

      }).catch(($e) => {
        // console.log('$e :>> ', $e?.response?.data);
        myToast('error', $e?.response?.data?.message || 'Something went wrong!');

        axios_errors.value = $e?.response?.data || $e;
        is_axios_finished.value = true;
      });
    }
  }

  async function excecuteAxios(req_url, req_config = {}) {
    var result = null;
    var errors = null;
    var is_finished = false;

    const url = unref(req_url);
    const config = unref(req_config);
    const method = config?.method || 'GET';
    const data = config?.data || {};

    if(method && url){
      await axios({
        method: method,
        url: url,
        data: data
      }).then((res) => {
        // console.log('res :>> ', res);
        if(res.data.success){
          if(res.data?.message){
            myToast('success', res.data.message);
          }

          result= Object.assign({}, res.data);
        } else{
          myToast('error', res.data?.message || 'Something went wrong!');

          errors = Object.assign({}, res.data);
        }

        is_finished = true;
      }).catch(($e) => {
        // console.log('$e :>> ', $e?.response?.data);
        myToast('error', $e?.response?.data?.message || 'Something went wrong!');

        errors = $e?.response?.data || $e;

        is_finished = true;
      });
    }

    return { result, errors, is_finished };
  }

  if (req_url && isRef(req_url)) {

    // console.log('req_url is a ref');
    // setup reactive re-fetch if input method & URL are ref
    watchEffect(makeRequest);
  } else if(req_url){
    // console.log('req_url is not a ref');

    // otherwise, just fetch once
    makeRequest();
  } else{
    return_data = { excecuteAxios }
  }

  return return_data;
}

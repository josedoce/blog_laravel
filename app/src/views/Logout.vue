<template>
</template>
<script>
import store from '../store';
import router from '../router';
import { viewError } from '../utils';
export default {
  computed: {
    user_info: function(){
      return store.getters.getInfo;
    }
  },
  mounted: async function(){
    const { is_auth } = this.user_info;
    if(!is_auth) {
      router.push('/signin');
      return;
    }
    try {
      await axios.delete('/sign-out');
      store.dispatch('logout');
      router.push('/');
    }catch(err) {
      if(!err.response){
        console.log(err);
        return;
      }
      viewError(err, 'Não foi possivel fazer logout.');
    }
  }
}
</script>
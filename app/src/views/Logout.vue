<template>
  
</template>

<script>
import store from '../store';
import router from '../router';
export default {
  mounted: async function(){
    const { is_auth } = store.getters.getInfo;
    if(!is_auth) {
      router.push('/signin');
      return;
    }
    try {
      await axios.delete('/sign-out');
      store.dispatch('logout');
      router.push('/');
    }catch(error) {
      console.log(error.message);
    }
  }
}
</script>
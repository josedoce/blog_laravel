<template>
  <form @submit="signin">
    <div class="input">
      <label>Digite seu email(fake){{ meunome }}</label>
      <input class="input" type="email" name="email" v-model="email">
    </div>
    <div class="input">
      <label>Digite sua senha(fake)</label>
      <input type="password" name="password" v-model="password" autocomplete="false">
    </div>
    
    <button class="input__btn">fazer login</button>
    <pre><code>{{ user_info }}</code></pre>
  </form>

</template>
<script>
import { csrf } from '../service';
import store from '../store';
import router from '../router';
import '../styles/main.scss';

export default {
  data:()=>({
    email: 'lulu.conn@example.org',
    password: 'password'
  }),
  name: 'Signin',
  components: {
   
  },
  computed: {
    meunome: function(){
      return store.state.info.nome;
    },
    user_info: function(){
      return store.getters.getInfo;
    }
  },
  methods: {
    requestAccess: async function() {
      try {
        csrf();
        const response = await axios.post('/sign-in');
        store.dispatch('login',{access_token: response.data.access_token});
        router.push('/');
      } catch(err) {
        console.log(err)
      }
    },
    signin: async function(event) {
      event.preventDefault();
      event.stopPropagation();
      await this.requestAccess();
    }
  },
  mounted: function(){
    const { is_auth } = this.user_info;
    if(is_auth) {
      router.push('/');
    };
  }
};
</script>

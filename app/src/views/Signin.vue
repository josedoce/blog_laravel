<template>
  <section class="signin">
    <div class="signin__content">
      <form @submit="signin" class="signin__form">
        <h2 class="signin__title">Login</h2>
        <div class="input">
          <label>Digite seu email(fake)</label>
          <input class="input" type="email" name="email" v-model="email" placeholder="digite um email">
        </div>
        <div class="input">
          <label>Digite sua senha(fake)</label>
          <input type="password" name="password" v-model="password" autocomplete="false" placeholder="digite uma senha">
        </div>
        <div class="input">
         <button class="btn">fazer login</button>
        </div>
      </form>
    </div>
  </section>
</template>
<script>
import { csrf } from '../service';
import alertStore, {appAlert} from '../store/alert';
import store from '../store';
import router from '../router';
import { valide, viewError } from '../utils/index';
import '../styles/main.scss';

export default {
  data:()=>({
    email: '',
    password: ''
  }),
  name: 'Signin',
  computed: {
    user_info: function(){
      return store.getters.getInfo;
    }
  },
  methods: {
    requestAccess: async function() {
      try {
        await csrf();
        const response = await axios.post('/sign-in', {
          email: this.email,
          password: this.password
        });
        store.dispatch('login',{
          access_token: response.data.access_token
        });
        router.push('/');
      } catch(err) {
        if(!err.response) {
          console.log(err);
          return;
        }
        viewError(err, 'Erro ao tentar logar.');
      }
    },
    signin: async function(event) {
      event.preventDefault();
      event.stopPropagation();
      let validated = valide({
        email: {
          value: this.email
        },
        password: {
          value: this.password
        }
      });
      if(validated.is_valid){
        await this.requestAccess();
      }else{
        appAlert(alertStore, {
          title: 'Campos vazios.',
          text: validated.errors[0].message
        });
      }
    },
  },
  mounted: function(){
    const { is_auth } = this.user_info;
    if(is_auth) {
      router.push('/');
    };
  }
};
</script>

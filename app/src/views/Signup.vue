<template>

  <section class="signin">
    <div class="signin__content">
      <form @submit="signup" class="signin__form">
        <h2 class="signin__title">Registro</h2>
         <div class="input">
          <label>Digite seu nome(fake)</label>
          <input class="input" type="text" name="name" v-model="name" placeholder="digite seu nome">
        </div>
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
import store from '../store';
import alertStore, {appAlert} from '../store/alert';
import { valide, viewError } from '../utils'; 
import router from '../router';
import '../styles/main.scss';

export default {
  data:()=>({
    name: '',
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
        const response = await axios.post('/sign-up', {
          name: this.name,
          email: this.email,
          password: this.password
        });
        
        await store.dispatch('login',{
          access_token: response.data.access_token
        });
        await router.push('/');
      } catch(err) {
        if(!err.response) {
          console.log(err);
          return;
        }
        viewError(err, 'Não foi possivel logar, tente novamente.');
      }
    },
    signup: async function(event) {
      event.preventDefault();
      event.stopPropagation();

      let validated = valide({
        name: {
          value: this.name
        },
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
          title: 'Campo vazio.',
          text: validated.errors[0].message
        });
      }
      
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

<template>
  <header class="header">
    <div class="header__logo">
      <h2>Blog</h2>
    </div>
    <nav class="header__menu">
      <ul class="menu__items">

        <li v-for="(item, index) in menu_links" :key="index" class="menu__item">
          <router-link :to="item.path" class="item__link">
            <v-btn variant="text">{{ item.text }}</v-btn>
          </router-link>
        </li>

      </ul>
       <v-menu
          transition="slide-x-transition"
          bottom
          right
        >
      <template v-slot:activator="{ props }">
        <v-btn
          class="deep-orange"
          variant="text"
          dark
          v-bind="props"
        >
          conta
        </v-btn>
      </template>

      <v-list>
        <strong v-if="auth == 'auth'">
          de: {{ user_name }}
        </strong>
        <ul v-for="(item, i) in items" :key="i">
          <li v-if="item.context == auth">
            <router-link :to="item.path">
              <v-btn size="small">{{ item.text }}</v-btn>
            </router-link>
          </li>
        </ul>
        
      </v-list>
    </v-menu>  
    </nav>

  </header>	
</template>
<script>
  import '../styles/components/navbar.scss';
  import store from '../store';
	import { mdiDelete } from '@mdi/js'
  export default {
    data: () => ({
      mdiDelete,
      menu_links: [
        {
          text: 'home',
          path: '/'
        },
        {
          text: 'postagens',
          path: '/page'
        },
      ],
      items: [
        {
          context: 'auth',
          text: 'gerenciar posts',
          path: '/dashboard'
        },
        {
          context: 'no_auth',
          text: 'fazer login',
          path: '/signin'
        },
        {
          context: 'no_auth',
          text: 'fazer registro',
          path: '/signup'
        },
        {
          context: 'auth',
          text: 'fazer logout',
          path: '/logout'
        }
      ],
    }),
    computed: {
      user_name: function(){
        const user_name = store.getters.getInfo.user.name;
        return user_name.split(' ')[0];
      },
      auth: function(){
        return store.getters.getInfo.is_auth?'auth':'no_auth';
      }
    }
  }
</script>
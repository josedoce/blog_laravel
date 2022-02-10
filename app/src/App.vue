<template>
  <Navbar />
  <main>
    <router-view/>
  </main>
  <footer>
    <p>rodapé</p>
  </footer>
    
</template>

<script>
import './styles/main.scss';
import Navbar from './components/Navbar.vue';
import store from './store';

export default {
  name: 'App',
  components: {
    Navbar
  }
}
</script>

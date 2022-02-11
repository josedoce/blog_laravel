<template>
  <div class="app__content">
    <Alert />
    <Navbar />
    <main>
      <router-view/>
    </main>
    <footer>
      <p>rodapé</p>
    </footer>
  </div>
</template>

<script>
import './styles/main.scss';
import Navbar from './components/Navbar.vue';
import Alert from './components/Alert.vue';

export default {
  name: 'App',
  
  components: {
    Navbar,
    Alert
  },

}
</script>

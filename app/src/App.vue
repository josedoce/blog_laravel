<template>
  <div class="app__content">
    <AlertApp/>
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
import AlertApp from './components/AlertApp.vue';

export default {
  name: 'App',
  components: {
    Navbar,
    AlertApp
  },
}
</script>

<template>
  <div v-if="data_alert.open" class="modal">
    <div class="modal__alert">
      <header class="modal__header">
        <h2>{{ data_alert.title }}</h2>
      </header>
      <section class="modal__content">
        <p>{{ data_alert.text }}</p>
      </section>
      <footer class="modal__footer">
        <p>ações: </p>
        <div class="modal__actions">

          <button 
            v-for="(button,index) in data_alert.buttons" 
            :key="index" 
            class="btn" 
            @click="button.fun(alert)">
            {{ button.name }}
          </button>
          
        </div>
      </footer>
    </div>
  </div>
</template>
<script>
import '@/styles/components/alert.scss';
import alertStore from '../store/alert';
export default {
  computed: {
    data_alert: function(){
      return alertStore.getters.getAlert;
    },
    alert: function(){
      return {
        close: function(){
          alertStore.dispatch('close_alert');
        }
      }
    }
  },
}
</script>
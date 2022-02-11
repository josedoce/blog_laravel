<template>
  <div v-if="data_alert.is_modal_open" class="modal">
    <div class="modal__alert">
      <header class="modal__header">
        <h2>{{ data_alert.content.title }}</h2>
      </header>
      <section class="modal__content">
        <p>{{ data_alert.content.text }}</p>
      </section>
      <footer class="modal__footer">
        <p>ações: </p>
        <div class="modal__actions">
          <button v-if="data_alert.buttons.understand" class="btn understand" @click="onunderstand">
            entendi
          </button>
          <button v-if="data_alert.buttons.confirm" class="btn confirm" @click="onconfirm">
            confirmar
          </button>
          <button v-if="data_alert.buttons.cancel" class="btn cancel" @click="oncancel">
            cancelar
          </button>
        </div>
      </footer>
    </div>
  </div>
</template>
<script>
import '@/styles/components/alert.scss';
import store, { alertActionType } from '../store';
export default {
  computed: {
    data_alert: function(){
      return store.getters.getAlert;
    }
  },
  methods: {

    onunderstand: function(){
      store.dispatch('alert', {
        type: alertActionType.CLOSE_ALERT
      });
    },
    onconfirm: function(){
      store.dispatch('alert', {
        type: alertActionType.CONFIRM_CLOSE_ALERT
      });
    },
    oncancel: function(){
      store.dispatch('alert', {
        type: alertActionType.CLOSE_ALERT
      });
    },
  },
}
</script>
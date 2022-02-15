import { createStore } from 'vuex';

export default createStore({
  state: {
    alert: {
      open: false,
      title: '',
      text: '',
      buttons: [{
        name: 'ok',
        fun: function(alert){
          alert.close();
        }
      }]
    }
  },
  getters: {
    getAlert: function(state) {
      return state.alert;
    }
  },
  mutations: {
    alert_mutation: function(state, payload){
      state.alert = payload;
    } 
  },
  actions: {
    open_alert: function({commit, state}, payload) {
        state.alert.open = true;
        if(!payload){
            commit('alert_mutation', state.alert);
            return;
        }
      state.alert.title = payload.title;
      state.alert.text = payload.text;
      if(payload.buttons != undefined && payload.buttons.length > 0){
        state.alert.buttons = payload.buttons;
      }
      commit('alert_mutation', state.alert);
    },
    close_alert: function({commit, state}){
        state.alert.open = false;
        state.alert.text = '';
        state.alert.title = '';
        state.alert.buttons = [{
            name: 'ok',
            fun: function(alert){
              alert.close();
            }
        }];
        commit('alert_mutation', state.alert);
    }
  },
  modules: {
  },
});

export function appAlert(store, {title, text, buttons}){
    store.dispatch('open_alert', {
        title,
        text,
        buttons
    });
};


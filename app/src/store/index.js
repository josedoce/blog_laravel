import { createStore } from 'vuex'
import { authRequest } from '../service';
import createPersistedState from 'vuex-persistedstate';
export const alertActionType = {
  OPEN_ALERT: 1,
  CLOSE_ALERT: 2,
  CONFIRM_CLOSE_ALERT: 3
};

const defaultModal = {
  is_modal_open: false,
  content: {
    title: null,
    text: null,
  },
  buttons: {
    understand: true,
    confirm: false,
    cancel: false
  },
  action: {
    understand: false,
    confirm: false,
    cancel: false,
  }
};
export default createStore({
  state: {
    info: {
      is_auth: false,
      user: null,
      access_token: '',
      url: '',
    },
    modal: {
      is_modal_open: false,
      content: {
        title: null,
        text: null,
      },
      buttons: {
        understand: true,
        confirm: false,
        cancel: false
      },
      action: {
        understand: false,
        confirm: false,
        cancel: false,
      }
    }
  },
  getters: {
    getAlert: function(state) {
      return state.modal;
    },
    getInfo: function(state) {
      return state.info;
    }
  },
  mutations: {
    informe: function(state, payload) {
      state.info = payload;
    },
    alert: function(state, payload) {
      state.modal = payload;
    }
  },
  actions: {
    alert: function({dispatch, commit, state}, payload) {
      let modal = state.modal;

      switch (payload.type) {
        case alertActionType.OPEN_ALERT:
          commit('alert', defaultModal);
          modal.is_modal_open = true;

          if(payload.data.content) {
            modal.content = Object.keys(payload.data.content).length != 0
            ? {...modal.content, ...payload.data.content} 
            : defaultModal.content;
          }

          if(payload.data.buttons){
            modal.buttons = Object.keys(payload.data.buttons).length != 0
            ? {...modal.buttons, ...payload.data.buttons} 
            : defaultModal.buttons;
          }
          modal.action = {...defaultModal.action};
          commit('alert', modal);
          break;
        
        case alertActionType.CLOSE_ALERT:
          modal.is_modal_open = false;
          modal.action.cancel = true;
          modal.action.understand = true;
          commit('alert', modal);
          break;

        case alertActionType.CONFIRM_CLOSE_ALERT:
          modal.is_modal_open = false;
          modal.action.confirm = true;
          commit('alert', modal);
          break;
      }
    },
    login: function({dispatch, commit, state}, payload){
      if(!state.info.is_auth) {
        const access_token = payload.access_token;
        dispatch('getInfo', {access_token}).then((res)=>{
          const info = state.info;   
          if(!res){
            alert('nao foi possivel logar');
            info.is_auth = false;
            window.localStorage.removeItem('access_token');
            info.access_token = null;
          }else{
            alert('logou com sucesso.')
            window.localStorage.setItem('access_token', access_token);
            info.is_auth = true;
            info.access_token = access_token;
          }
          commit('informe', info);
        });
      }
    },
    logout: function({commit, state}){
      const info = state.info;
      info.is_auth = false;
      info.user = null;
      info.access_token = ''; 
      window.localStorage.removeItem('access_token');
      commit('informe', info);
    },
    getInfo: async function({commit, state}, payload){
      try {
        const response = await authRequest(payload.access_token).get('/is-auth');
        const info = state.info;
        info.user = response.data.user;
        commit('informe', info);
        return true;
      } catch (err) {
        console.log(err);
        return null;
      }
    },
    
    /** buscará todas as informações necessárias */
    setInfo: async function({dispatch, commit, state}){
      const info = state.info;
      info.url = process.env.VUE_APP_API;
      let access_token = window.localStorage.getItem('access_token');
      
      await dispatch('getInfo', {access_token}).then((res)=>{
        if(!res){
          info.is_auth = false;
          window.localStorage.removeItem('access_token');
          info.access_token = null;
          console.log('pode apagar o token')
          return;
        }
        info.is_auth = true;
        info.access_token = access_token;
      });
      
      commit('informe', info);
    }
  },
  modules: {
  },
  plugins: [createPersistedState()]
})

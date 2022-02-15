import { createStore } from 'vuex'
import { authRequest } from '../service';
import createPersistedState from 'vuex-persistedstate';

export default createStore({
  state: {
    info: {
      is_auth: false,
      user: null,
      access_token: '',
      url: process.env.VUE_APP_API,
    },
  },
  getters: {
    getInfo: function(state) {
      return state.info;
    },
  },
  mutations: {
    informe: function(state, payload) {
      state.info = payload;
    },
  },
  actions: {
    login: async function({commit, state}, payload){
      if(!state.info.is_auth) {
        try {
          const response = await authRequest(payload.access_token).get('/is-auth');
          state.info.access_token = payload.access_token;
          state.info.user = response.data.user;
          state.info.is_auth = true;
          alert('logou com sucesso.');
        } catch(err) {
          if(!err.response){
            console.log(err);
            return;
          }
          alert('não foi possivel fazer login.');
        } finally {
          commit('informe', state.info);
        } 
      }
    },
    logout: function({commit, state}){
      state.info.is_auth = false;
      state.info.user = null;
      state.info.access_token = ''; 
      commit('informe', state.info);
    }, 
    setInfo: async function({commit, state}){
      try {
        const response = await authRequest(state.info.access_token).get('/is-auth');
        state.info.user = response.data.user;
        state.info.is_auth = true;
      } catch(err) {
        if(!err.response){
          console.log(err);
          return;
        }
      } finally {
        commit('informe', state.info);
      }
    }
  },
  plugins: [createPersistedState()]
});

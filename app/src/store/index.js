import { createStore } from 'vuex'
import { authRequest } from '../service';
import createPersistedState from 'vuex-persistedstate';

export default createStore({
  state: {
    info: {
      is_auth: false,
      user: null,
      access_token: '',
      url: '',
    },
  },
  getters: {
    getInfo: function(state) {
      return state.info;
    }
  },
  mutations: {
    informe: function(state, payload) {

      state.info = payload;
    },
  },
  actions: {
    login: function({dispatch, commit, state}, payload){
      if(!state.info.is_auth) {
        const access_token = payload.access_token;
        dispatch('getInfo', {access_token}).then((res)=>{
          const info = state.info;   
          if(!res){
            alert('nao foi possivel logar')
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

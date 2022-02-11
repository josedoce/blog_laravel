<template>
  <section class="dashboard">
    <h2>Minha publicações</h2>
    <div v-if="data_posts.length != 0">
      <Card
        v-for="(data, index) in data_posts" :key="index"
        :data="data"
      />

       <Paginate 
        @pagination="paginate"
        :data="data_paginate"
      />
  </div> 
  </section>
</template>
<script>
import '@/styles/components/dashboard.scss';
import Card from '@/components/Card.vue';
import Paginate from '@/components/Paginate.vue';
import store, { alertActionType } from '../store';
export default {
  components: {
    Card,
    Paginate
  },
  data: function(){
    return {
      posts: [],
      data_paginate: [],
      limit: 5,
    }
  },
  computed: {
    data_posts: function(){
      return this.posts;
    }
  },
  methods: {
    paginate: async function(page=1) {
      const { access_token } = store.getters.getInfo;
      const user_id = access_token.split('|')[0];
      await axios.get(`/users/${user_id}/posts?page=${page}&limit=${this.limit}`).then((res)=>{
        this.data_paginate = res.data.paginate;
        this.posts = res.data.posts.map((e)=>{
          e = {
            id: e.id,
            title: e.title,
            user: { name: null},
            text: e.text
          };
          return e;
        });
      }).catch((err)=> {
        if(!err.response){
          return console.log(err)
        }
        store.dispatch('alert', {
            type: alertActionType.OPEN_ALERT,
            data: {
              content: {
                title: 'Erro na requisição',
                text: err.response.data.message
              },
            }
          });   
      });
    }
  },
  mounted: async function(){
    await this.paginate();
  }
}
</script>
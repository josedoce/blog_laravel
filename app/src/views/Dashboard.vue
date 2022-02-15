<template>
  <section class="dashboard">
    <div class="dashboard__content">
      <div>
        <h2>Minha publicações</h2>
      </div>
      <div class="dashboard__empty" v-if="data_posts.length == 0">
        <h5>você não possui publicações. 
          <router-link to="/page">criar publicação</router-link>
        </h5>
      </div>
      <div class="card__action" v-if="data_posts.length != 0">
        <CardAction
          
          v-for="(data, index) in data_posts" :key="index"
          :data="data"
        />

        <Paginate 
          @pagination="paginate"
          :data="data_paginate"
        />

    </div>
  </div> 
  </section>
</template>
<script>
import '@/styles/page/dashboard.scss';
import CardAction from '@/components/CardAction.vue';
import Paginate from '@/components/Paginate.vue';
import router from '../router';
import store from '../store';
import { viewError } from '../utils';

export default {
  components: {
    CardAction,
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
    },
    user_info: function(){
      return store.getters.getInfo;
    }
  },
  methods: {
    paginate: async function(page=1) {
      try {
        const { user } = this.user_info;
        const response = await axios.get(`/users/${user.id}/posts?page=${page}&limit=${this.limit}`);
        this.data_paginate = response.data.paginate;
       
        this.posts = response.data.posts.map((e)=>{
          e = { id: e.id, title: e.title, text: e.text };
          return e;
        });

      }catch(err){
        if(!err.response){
          return console.log(err);
        }
        viewError(err, 'Erro ao requisitar posts');
      }
    }
  },
  mounted: async function(){
    if(!this.user_info.is_auth){
      return router.push('/signin');
    }
    await this.paginate();
  }
}
</script>
<template>
  <section class="page">
    <h3>paginas</h3>
    <CardCreate v-if="is_auth" @creatednewpost="paginate"/>
    <div v-if="data_posts.length != 0">
        <Card
          v-for="(data, index) in data_posts" :key="index"
          :data="data"
        />
    </div> 
    <Paginate 
      @pagination="paginate"
      :data="data_paginate"
    />
  </section>
</template>
<script>
import Paginate from '@/components/Paginate.vue';
import Card from '@/components/Card.vue';
import CardCreate from '../components/CardCreate.vue';
import { viewError } from '../utils';
import store from '../store';

export default {
  components: {
    Paginate,
    Card,
    CardCreate
  },
  data: function(){
    return {
      posts: [],
      data_paginate: [],
      limit: 10,
    };
  },
  computed: {
    data_posts: function(){
      return this.posts;
    },
    is_auth: function(){
      return store.getters.getInfo.is_auth;
    }
  },
  methods: {
    paginate: async function(page=1) {
      try {
        const response = await axios.get(`/posts?page=${page}&limit=${this.limit}`);
        this.posts = response.data.posts;
        this.data_paginate = response.data.paginate;
      }catch(err){
        if(!err.response){
          console.log(err);
          return;
        }
        viewError(err, 'Erro ao requisitar posts');
      }
    }
  },
  mounted: function(){
    this.paginate();
  }
}
</script>
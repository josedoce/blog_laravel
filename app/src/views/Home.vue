<template>
    <section class="home">
      <h2>Adicionados recentemente</h2>
      <div v-if="data_posts.length != 0">
        <Card
          v-for="(data, index) in data_posts" :key="index"
          :data="data"
        />
      </div> 
    </section>
</template>

<script>
import Card from '@/components/Card.vue';
import { viewError } from '../utils';
export default {
  name: 'Home',
  data: function(){
    return {
      posts: [],
    }
  },
  components: {
    Card
  },
  computed: {
    data_posts: function(){
      return this.posts;
    },
  },
  mounted: async function(){
    try {
      const response = await axios.get(`/posts?page=1&limit=10`);
      this.posts = response.data.posts;
    }catch(err){
      if(!err.response){
        console.log(err);
        return;
      }
      viewError(err, 'Erro ao requisitar posts');
    }
  }
};
</script>

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
import HelloWorld from '../components/HelloWorld.vue';
import store from '../store';
export default {
  name: 'Home',
  data: function(){
    return {
      posts: [],
    }
  },
  components: {
    HelloWorld,
    Card
  },
  computed: {
    data_posts: function(){
      return this.posts;
    },
    user_info: function(){
      return store.getters.getInfo;
    }
  },
  mounted: async function(){
    await axios.get(`/posts?page=1&limit=10`).then((res)=>{
      this.posts = res.data.posts;
    }).catch((err)=> console.log(err));
  }
};
</script>

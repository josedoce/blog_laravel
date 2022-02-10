<template>
  <h3>paginas</h3>
  <form @submit="create">

    <button type="submit">criar</button>
  </form>
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
</template>
<script>
import Paginate from '@/components/Paginate.vue';
import Card from '@/components/Card.vue';

export default {
  components: {
    Paginate,
    Card
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
    }
  },
  methods: {
    create: function(event){
      const { url } = this.$store.state.info;
      event.preventDefault();
      event.stopPropagation();
      axios.post(`/posts`,{
        title: 'um titulo do frontend',
        text: 'um texto grande do frontend'
      }).then((res)=>{
        console.log(res.data);
      });
      console.log(url)
      
    },
    paginate: function(page=1) {
      axios.get(`/posts?page=${page}&limit=${this.limit}`).then((res)=>{
        this.posts = res.data.posts;
        this.data_paginate = res.data.paginate;
      }).catch((err)=> console.log(err));
    }
  },
  mounted: function(){
    console.log(this.$route.query);
    this.paginate();
  }
}
</script>
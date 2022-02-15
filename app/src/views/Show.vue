<template>
<section class="show">
  <h2>Publicação</h2>
  <Card
    v-if="post != undefined"
    :data="data_post"
    :btn="false"
    :about-link="true"
  />
</section>

</template>
<script>
import Card from '@/components/Card.vue';
import { viewError } from '../utils';
export default {
  components: {
    Card,
  },
  data: function(){
    return {
      post: undefined
    }
  },
  computed: {
    data_post: function(){
      const {content, user} = this.post
      return {...content, user};
    }
  },
  mounted: async function(){
    try {
      const response = await axios.get(`/posts/${this.$route.params.post_id}`);
      this.post = response.data.post;
    }catch(err){
      if(!err.response){
        console.log(err);
        return;
      }
      viewError(err, 'Não foi possivel encontrar o post');
    }
    
  }
}
</script>
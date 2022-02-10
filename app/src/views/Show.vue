<template>
<h2>Publicação</h2>
<Card
  v-if="post != undefined"
  :data="data_post"
  :btn="false"
  :about-link="true"
/>

</template>
<script>
import Card from '@/components/Card.vue';
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
  mounted: function(){
    axios.get(`/posts/${this.$route.params.post_id}`).then((res)=>{
      this.post = res.data.post;
    }).catch((err)=> console.log(err));
  }
}
</script>
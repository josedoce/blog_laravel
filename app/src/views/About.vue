<template>
  <div class="about">
    <h1>This is an about page</h1>
    <Card
      v-if="user != undefined"
      :data="data_user"
      :btn="false"
      :about-link="false"
    />
  </div>
</template>
<script>
import Card from '@/components/Card.vue';
export default {
  components: {
    Card,
  },
  data: function(){
    return {
      user: undefined
    }
  },
  computed: {
    data_user: function(){
      const { user} = this.user
      
      return {...{title: '', text: ''},user};
    }
  },
  mounted: function(){
    axios.get(`/users/${this.$route.params.user_id}`).then((res)=>{
      this.user = res.data;
    }).catch((err)=> console.log(err));
  }
}
</script>
<template>
  <section class="about"> 
    <div class="about__content">
      <div>
        <h1>Detalhes sobre o postador</h1>
      </div>
      <div>
        <Card
          v-if="user != undefined"
          :data="data_user"
          :btn="false"
          :about-link="false"
        />
      </div>
    </div>
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
      user: undefined
    }
  },
  computed: {
    data_user: function(){
      const { user} = this.user
      return {...{title: '', text: ''},user};
    }
  },
  mounted: async function(){
    try {
      const response = await axios.get(`/posts/${this.$route.params.post_id}/user`);
      this.user = response.data;
    }catch(err){
      if(!err.response){
        console.log(err);
        return;
      }
      viewError(err, `O post de indice '${this.$route.params.post_id}' não foi encontrado.`);
    }
  }
}
</script>
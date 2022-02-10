<template>
  <div class="text-center paginate">
    <button @click="paginate(data.current_page - 1)">voltar</button>
    <button class="index selected">{{ data.current_page }}</button>
    <button
        class="index" 
        v-for="(data, index) in data.friendly_link"
        :key="index"
        @click="paginate(data)">
      {{ data }}
    </button>
    <button @click="paginate(data.current_page + 1)">proximo</button>
  </div>
</template>
<script>
import '@/styles/components/paginate.scss';
  export default {
    props: {
      data: {
        type: Object,
        default: function(){
          return {
            friendly_link: [],
            current_page: 0,
          }
        }
      }
    },
    data () {
      return {
        page: 1,
      }
    },
    methods: {
      paginate: function(index){
        if(index > 0) {
          this.$emit('pagination', index);
        }
      }
    }
  }
</script>
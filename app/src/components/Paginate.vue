<template>
  <div class="text-center paginate">
    <button @click="paginate(data.current_page - 1)" :disabled="data.current_page != 1?false:true">voltar</button>
    <button class="index selected">{{ data.current_page }}</button>
    <button
        class="index" 
        v-for="(data, index) in data.friendly_link"
        :key="index"
        @click="paginate(data)">
      {{ data }}
    </button>
    <button @click="paginate(data.current_page + 1)" :disabled="!data.has_next">proximo</button>
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
            has_next: true
          }
        }
      }
    },
    methods: {
      paginate: function(index){
        if(index > 0) {
          this.$emit('pagination', index);
        }
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        })
      }
    }
  }
</script>
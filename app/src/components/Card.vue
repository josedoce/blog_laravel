<template>
  <v-card
    style="margin: 4px 0"
    class="mx-auto"
    max-width="50%"
    variant="outlined"
  >
    <v-card-header>
      <div>
        <div class="text-overline mb-1">
          {{ data.title }}
        </div>
        <div v-if="!aboutLink" class="text-h6 mb-1">
          {{ data.user.name }}
        </div>
        <div v-if="aboutLink" class="text-h6 mb-1">
          <router-link :to="'/about/'+data.user.id">
            {{ data.user.name }}
          </router-link>
        </div>
        <div class="text-caption">
          {{ data.text }}
        </div>
      </div>
    </v-card-header>

    <v-card-actions v-if="btn">
      <router-link :to="'/posts/'+data.id">
        <v-btn size="small" flat>
          mais
        </v-btn>
      </router-link>
    </v-card-actions>
  </v-card>
</template>
<script>
  export default {
    props: {
      aboutLink: {
        type: Boolean,
        default: false,
      },
      btn: {
        type: Boolean,
        default: true,
      },
      data: {
        type: Object,
        default: function() {
          return {
            id: 1,
            title: 'um titulo qualquer',
            user: { name: 'um autor qualquer'},
            text: 'um textinho qualquer',
          };
        }
      },
      
    },
  }
</script>
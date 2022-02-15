<template>
  <v-card
    style="margin: 4px 0"
    class="mx-auto cardaction"
    max-width="50%"
    variant="outlined"
    v-if="!deleted"
  >
    <v-card-header>
      <div class="cardaction__content">
        <div class="text-overline mb-1">
          <input v-if="editing" type="text" v-model="title">
          <h4 v-else>
            {{ data.title }}
          </h4>
        </div>
        <div class="text-caption">
          <textarea v-if="editing" v-model="text"  rows="6"></textarea>
          <p v-else>
            {{ data.text }}
          </p>
        </div>
      </div>
    </v-card-header>

    <v-card-actions>
      <v-btn v-if="editing" @click="confirmEdition" size="small" color="green" flat>
        <v-icon color="white">{{ check_icon }}</v-icon>
      </v-btn>

      <v-btn v-if="editing" @click="cancelEdition" size="small" color="red" flat>
        <v-icon color="white">{{ cancel_icon }}</v-icon>
      </v-btn>

      <v-btn v-if="!editing" @click="show" size="small" color="blue" flat>
        <v-icon color="white">{{ show_icon }}</v-icon>
      </v-btn>

      <v-btn v-if="!editing" @click="edit" size="small" color="green" flat>
        <v-icon color="white">{{ edit_icon }}</v-icon>
      </v-btn>

      <v-btn v-if="!editing" @click="exclude" size="small" color="red" flat>
        <v-icon color="white">{{ delete_icon }}</v-icon>
      </v-btn>

    </v-card-actions>
  </v-card>
</template>
<script>
import '@/styles/components/cardaction.scss';
import {
  mdiEye,
  mdiCheckBold, 
  mdiCloseThick,
  mdiDeleteEmpty, 
  mdiClipboardEdit 
} from '@mdi/js';
import router from '@/router';
import store from '../store';
import alertStore, { appAlert } from '../store/alert';
export default {
  props: {
    data: {
      type: Object,
      default: function() {
        return {
          id: 1,
          title: 'um titulo qualquer',
          text: 'um textinho qualquer',
        };
      }
    },
    
  },
  data: function(){
    return {
      show_icon: mdiEye,
      check_icon: mdiCheckBold,
      cancel_icon: mdiCloseThick,
      delete_icon: mdiDeleteEmpty,
      edit_icon: mdiClipboardEdit,
      editing: false,
      deleted: false,
      title: '',
      text: '',
    }
  },
  computed: {
    action_alert: function(){
      return store.getters.getAlert.action;
    }
  },
  methods: {
    show: async function(){
      router.push(`/post/${this.data.id}`);
    },
    edit: async function(){
      this.title = this.data.title;
      this.text = this.data.text;
      this.editing = true;
    },
    confirmEdition: async function(){
      try {
        const response = await axios.put(`/posts/${this.data.id}/update`, {
          title: this.title,
          text: this.text
        });
        this.data.title = this.title;
        this.data.text = this.text;
      }catch(err){

      }
      
      this.editing = false;     
    },
    cancelEdition: async function(){
      this.editing = false;
    },
    confirmDeletion: async function(alert) { 
      const post_id = this.data.id;
      try {
        const response = await axios.delete(`/posts/${post_id}/delete`);
        this.deleted = true;
        alert.close();
      } catch(err){
        if(!err.response){
          console.log(err);
          return;
        }
        alert('não foi possivel deletar');
        alert.close();
      }
    },
    exclude: async function(){
      
      appAlert(alertStore, {
        title: 'confirmar exclusão',
        text: 'deseja mesmo excluir o post ?',
        buttons: [
          {
            name: 'confirmar',
            fun: this.confirmDeletion
          },
          {
            name: 'cancelar',
            fun: function(alert){
              alert.close();
            }
          }
        ]
      });
    } 
  },
  mounted: function(){
  }
}
</script>
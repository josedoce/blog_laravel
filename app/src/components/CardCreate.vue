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
          <input v-if="creating" type="text" v-model="title" placeholder="escreva o titulo da publicação aqui...">
        </div>
        <div class="text-caption">
          <textarea v-if="creating" v-model="text" rows="6" placeholder="escreva o texto da publicação aqui..."></textarea>
        </div>
      </div>
    </v-card-header>

    <v-card-actions>
      <v-btn v-if="creating" @click="confirmCreation" size="small" color="green" flat>
        <v-icon color="white">{{ check_icon }}</v-icon>
      </v-btn>

      <v-btn v-if="creating" @click="create" size="small" color="red" flat>
        <v-icon color="white">{{ cancel_icon }}</v-icon>
      </v-btn>

      <v-btn v-if="!creating" @click="create" block size="small" color="green" flat>
          criar publicação
        <v-icon color="white">{{ edit_icon }}</v-icon>
      </v-btn>

    </v-card-actions>
  </v-card>
</template>
<script>
import {
  mdiEye,
  mdiCheckBold, 
  mdiCloseThick,
  mdiDeleteEmpty, 
  mdiClipboardEdit,
  mdiFolderPlus 
} from '@mdi/js';
import {valide} from '../utils';
import alertStore, { appAlert } from '../store/alert';
export default {
    data: function(){
        return {
            show_icon: mdiEye,
            check_icon: mdiCheckBold,
            cancel_icon: mdiCloseThick,
            delete_icon: mdiDeleteEmpty,
            edit_icon: mdiClipboardEdit,
            create_icon: mdiFolderPlus,
            creating: false,
            deleted: false,
            title: '',
            text: '',
        }
    },
    methods: {
        create: function(){
            this.creating = !this.creating;
            this.clearInputs();
        },
        clearInputs: function(){
            this.title = '';
            this.text = '';
        },
        valideFields: async function(alert){
            alert.close();
            let validated = valide({
                title: {
                    value: this.title
                },
                text: {
                    value: this.text
                }
            });
            if(validated.is_valid){
                await this.createRequest(alert);
            }else{
                appAlert(alertStore, {
                    title: 'Campos vazios, não foi possivel publicar.',
                    text: validated.errors[0].message
                });
            }
        },
        createRequest: async function(alert){
            try {
                const response = await axios.post(`/posts`,{
                    title: this.title,
                    text: this.text
                });
                this.$emit('creatednewpost');
                this.create();
                alert.close();

            }catch(err){
                if(!err.response){
                    console.log(err);
                    return;
                }
                alert.close();
                appAlert(alertStore, {
                    title: 'Aviso',
                    text: 'Não foi possivel publicar o post.',
                });
               
            }
            
        },
        confirmCreation: function(){
            appAlert(alertStore, {
                title: 'Aviso',
                text: 'Você deseja postar ?',
                buttons: [
                    {
                        name: 'sim',
                        fun: this.valideFields
                    },
                    {
                        name: 'não',
                        fun: function(alert){ alert.close() }
                    }
                ]
            });
        }
    }
}
</script>
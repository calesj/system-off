<template>
<div id="carroForm">
  <v-dialog max-width="800px"
  v-model="dialog">
    <v-card height="700px">
      <v-card-actions>
        <v-spacer></v-spacer>
        <!--atualizar o valor da props dialog para false
        caso o usuario clique nesse botao, será emitido um evento ao elemento pai dizendo que o método closeDialog
        foi acionado, dentro do elemento pai diz que caso esse método seja chamado, dialog será igual a false-->
        <v-btn @click="closeDialog()" icon>
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-actions>
      <v-card-subtitle>Cadastrar Carro</v-card-subtitle>
      <v-card-text>
      <form>
        <v-text-field
          v-model="nomeCarro"
          label="Digite o nome do veiculo">
        </v-text-field>

        <v-file-input
          v-model="imagemCarro"
          accept="image/png, image/jpeg, image/bmp"
          placeholder="Pick an avatar"
          prepend-icon="mdi-camera"
          label="Envie uma imagem do veiculo"
          @change="carregarImagem"
          @clearable="imagemCarro = null"
        ></v-file-input>
        <v-img class="mx-auto" max-height="400px" width="500px" v-if="imagemCarregada" :src="imagemCarregada"></v-img>
        <v-text-field label="Digite o Ano do veiculo"></v-text-field>
      </form>
      </v-card-text>
    </v-card>
  </v-dialog>
</div>
</template>

<script>

import props from "vuetify/src/components/VCalendar/util/props";
export default {
  name: 'carroForm',

  data () {
    return {
      nomeCarro: '',
      imagemCarro: '',
      imagemCarregada: ''
    }
  },

  // utilizamos props, para o componente pai poder manipular o valor dessa propriedade por fora
  props: {

    // dialog é a propriedade que representa o v-model do nosso componente, utilizamos props, para o componente pai
    // poder manipular o valor dessa propriedade por fora
    dialog: {
      type: Boolean,
      required: true
    }
  },

  methods: {

    carregarImagem() {
      // cria uma instância do método fileReader

      if(!this.imagemCarro) {
        this.imagemCarregada = null
        return 0
      }
      const reader = new FileReader()

      // Define a função que será executada quando a imagem for carregada
      reader.onload = (event) => {
        // Atualiza o valor da propriedade imagemCarregada
        this.imagemCarregada = event.target.result
      }

      // Lê o arquivo selecionado pelo usuário
      reader.readAsDataURL(this.imagemCarro)
    },

    // evento será emitido para o componente pai, e enviará o valor 'false' como parâmetro
    closeDialog() {
      this.$emit('close-dialog', false)
    }
  }
}
</script>


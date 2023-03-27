<template>
  <div id="infoMotoComponent">
    <v-dialog
      v-model="showDialog"
      width="500px">
      <v-card class="mx-auto">
        <div v-if="loading">
          <v-skeleton-loader
            class="auto"
            type="list-item-avatar-three-line, image, article"
          ></v-skeleton-loader>
        </div>
        <div v-show="!loading">
          <v-card-actions>
            <v-card-title>Informações da Moto</v-card-title>
            <v-spacer></v-spacer>
            <!--atualizar o valor da props dialog para false
            caso o usuario clique nesse botao, será emitido um evento ao elemento pai dizendo que o método closeDialog
            foi acionado, dentro do elemento pai diz que caso esse método seja chamado, dialog será igual a false-->
            <v-btn @click="closeDialog()" icon>
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-actions>
          <v-card-text>
            <b>Imagem:</b>
            <v-img :src="moto.file_path"></v-img>
            <b>Nome:</b> {{ moto.nome }} <br>
            <b>Ano de Fabricação:</b> {{ moto.ano }}
          </v-card-text>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'infoMotoComponent',

  data() {
    return {
      loading: true
    }
  },

  // utilizamos props, para o componente pai poder manipular o valor dessa propriedade por fora
  props: {

    // showDialog é a propriedade que representa o v-model do nosso componente, utilizamos props, para o componente pai
    // poder manipular o valor dessa propriedade por fora
    showDialog: {

      // o valor que vier do componente pai, deve ser booleano e o valor padrão é falso
      type: Boolean,
      default: false,
    },

    // moto é a propriedade que representa o objeto que será exibido dentro do nosso v-dialog, ele virá do componente pai
    // para ser exibido dentro desse componente
    moto: {
      type: Object,
      required: true
    }
  },

  mounted() {

    // função de callback, nós enviamos uma função como parâmetro que representa o código que queremos congelar
    // como segundo parâmetro enviamos a quantidade de tempo que queremos congelar o código em ms
    setTimeout(() => {
      this.loading = false
    }, 1000)
  },

  methods: {
    closeDialog() {
      this.$emit('close-dialog')
    }
  }
}

</script>

<template>
  <div id="infoCarComponent">
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
            <v-card-title>Informações do Carro</v-card-title>
            <v-spacer></v-spacer>
            <!--atualizar o valor da props showDialog para false
            caso o usuario clique nesse botao, será emitido um evento ao elemento pai dizendo que o método closeDialog
            foi acionado, dentro do elemento pai diz que caso esse método seja chamado, showDialog será igual a false-->
            <v-btn @click="closeDialog()" icon>
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-actions>
          <v-card-text>
            <b>Imagem:</b>
            <v-img :src="carro.file_path"></v-img>
            <b>Nome:</b> {{ carro.nome }} <br>
            <b>Ano de Fabricação:</b> {{ carro.ano }}
          </v-card-text>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: 'infoCarComponent',

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

      //o valor que vier do componente pai, deve ser booleano e o valor padrão é falso
      type: Boolean,
      default: false,
    },

    // carro é a propriedade que representa o objeto que será exibido dentro do nosso v-dialog, ele virá do componente pai
    // para ser exibido dentro desse componente
    carro: {
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

  // evento será emitido para o componente pai, caso usuario clique no botão fechar, componente pai irá saber
  methods: {
    closeDialog() {
      this.$emit('close-dialog')
    }
  }
}

</script>

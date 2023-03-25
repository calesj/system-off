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
          <v-card-title>Informações do Carro</v-card-title>
          <v-card-text><b>Imagem:</b>
            <v-img :src="carro.file_path"></v-img>
          </v-card-text>
          <v-card-text><b>Nome:</b> {{ carro.nome }}</v-card-text>
          <v-card-text><b>Ano de Fabricação:</b> {{ carro.ano }}</v-card-text>
        </div>
        <v-card-actions>
          <!--atualizar o valor da props showDialog para false
          caso o usuario clique no botao e emitindo esse valor para o componente pai se atualizar com o novo valor dado-->
          <v-btn color="primary" @click="fecharDialog()">
            Fechar
          </v-btn>
        </v-card-actions>
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

  //Definindo a props dinâmica que virá do componente pai
  props: {
    showDialog: {
      /*o valor que vier do componente pai, deve ser
        booleano e o valor padrão é falso*/
      type: Boolean,
      default: false,
    },

    carro: {
      type: Object,
      required: true
    }
  },

  mounted() {
    setTimeout(() => {
      this.loading = false
    }, 1000)
  },

  methods: {
    fecharDialog() {
      this.$emit('fechar-dialog')
    }
  }
}

</script>

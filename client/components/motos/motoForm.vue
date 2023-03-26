<template>
<div id="motoForm">
  <v-dialog max-width="800px"
  v-model="dialog">
    <v-card height="700px">
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="closeDialog()" icon>
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-actions>
      <v-card-subtitle>Cadastrar Moto</v-card-subtitle>
    </v-card>
  </v-dialog>
</div>
</template>

<script>

import props from "vuetify/src/components/VCalendar/util/props";
export default {
  name: 'motoForm',

  data () {
    return {}
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

    // evento será emitido para o componente pai, caso usuario clique no botão fechar, componente pai irá saber
    closeDialog() {
      this.$emit('close-dialog')
    }
  }
}
</script>


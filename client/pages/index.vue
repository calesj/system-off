<template>
  <v-col class="shrink">
    <v-app-bar app>
    <v-btn :elevation="0"
        @click="showCarros"
      >
        Carros
      </v-btn>
      <v-btn :elevation="0"
        @click="showMotos"
      >
        Motos
      </v-btn>
    </v-app-bar>
    <!-- colocando ref no componente para acessar as referencias dele utilizando o $refs em algum método -->
    <!-- passando o valor da variavel showDialog para a props showDialog do componente filho; -->
    <!-- o @update:show-dialog="updateDialog" está trazendo o valor que está no componente filho,
    e atualizando esse valor no elemento pai-->
    <infoCarComponent ref="infoCarComponent" :show-dialog="showDialog" @update:show-dialog="updateDialog"></infoCarComponent>  
      <v-scroll-x-transition>
        <v-data-table
        v-show="tableCarros"
        :headers="headersCarros"
        :items="itemsCarros"
        hide-default-footer
        >
        <template v-slot:item.info="{ item }">
      <v-chip
        :color="orange"
        dark
        @click="carDialog()"
      >
        <v-icon>mdi-camera-outline</v-icon>
      </v-chip>
    </template>
      </v-data-table>
      </v-scroll-x-transition>

      <v-scroll-x-reverse-transition>
        <v-data-table
        v-show="tableMotos"
        :headers="headersMotos"
        :items="itemMotos"
        hide-default-footer
        ></v-data-table>
      </v-scroll-x-reverse-transition>
  </v-col>
</template>
<script>
import infoCarComponent from '~/components/carros/infoCarComponent.vue'
  export default {
    components: {
      infoCarComponent
    },
    data () {
      return {
        headersCarros: 
        [{
          text: 'Carros',
          value: 'car'
        }, 
        {
          text: 'Informações',
          value: 'info'
        },
        {
          text: 'Ano',
          value: 'year'
        }
      ],

        headersMotos: [{
          text: 'Motos'
        }],
        
        itemsCarros: [{
          car: 'Opala 166',
          info: 'salve',
          year: 1996
      }],
        itemsMotos: [],
        tableCarros: true,
        tableMotos: false,
        showDialog: false
     }
    },

    methods: {
      async showCarros() {
        this.tableMotos = false
        await this.sleep(300)
        this.tableCarros = true
      },

     async showMotos(){
        this.tableCarros = false
        await this.sleep(300)
        this.tableMotos = true
      },

      carDialog() {
        /* quando o usuario clicar no icone da camera atualizando o valor atual da props
         showDialog do componente infoCarComponent será atualizado para true */
        this.$refs.infoCarComponent.$emit('update:show-dialog', true)
      },

      updateDialog(value) {
        this.showDialog = value
      },

     sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }
  }
  }
</script>

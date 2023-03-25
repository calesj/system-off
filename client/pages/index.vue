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
    <infoCarComponent ref="infoCarComponent" :show-dialog="showDialogCarro" @update:show-dialog="updateDialogCarro"></infoCarComponent>
    <infoMotoComponent ref="infoMotoComponent" :show-dialog="showDialogMoto" @update:show-dialog="updateDialogMoto"></infoMotoComponent>
      <v-scroll-x-transition>
        <v-data-table
        v-show="tableCarros"
        :headers="headersCarros"
        :items="itemsCarros"
        hide-default-footer
        >
        <template v-slot:item.info="{ item }">
      <v-chip
        color="orange"
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
        :items="itemsMotos"
        hide-default-footer
        ><template v-slot:item.info="{ item }">
        <v-chip color="orange"
        @click="motoDialog()">
          <v-icon>
            mdi-camera-outline
          </v-icon>
        </v-chip>
        </template>
        </v-data-table>
      </v-scroll-x-reverse-transition>
  </v-col>
</template>
<script>
import infoCarComponent from '~/components/carros/infoCarComponent.vue'
import infoMotoComponent from '~/components/motos/infoMotoComponent.vue'
  export default {
    components: {
      infoCarComponent,
      infoMotoComponent
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
        }],

        headersMotos: [{
          text: 'Motos',
          value: 'moto'
        },
        {
        text: 'Informações',
        value: 'info'
        },
        {
          text: 'Ano',
          value: 'year'
        }],
        
        itemsCarros: [{
          car: 'Opala 166',
          info: 'salve',
          year: 1996
      }],
        itemsMotos: [{
          moto: 'Honda Twist',
          info: 'salve',
          year: 2002
        }],
        tableCarros: true,
        tableMotos: false,
        showDialogCarro: false,
        showDialogMoto: false
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
        this.showDialogCarro = true
      },

      motoDialog() {
        this.showDialogMoto = true
      },

      updateDialogCarro(value) {
        this.showDialogCarro = value
      },

      updateDialogMoto(value) {
        this.showDialogMoto = value
      },

     sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }
  }
  }
</script>

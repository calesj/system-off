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
    <!-- passando o valor da variavel carroSelecionado para a props carro do componente filho -->
    <!-- só habilitará o componente, se existir um carroSelecionado -->
    <!-- se por acaso o metodo fechar-dialogo dentro do componente for chamado, a variavel showDialogCarro passará a ser false -->
    <infoCarComponent v-if="carroSelecionado" ref="infoCarComponent" :show-dialog="showDialogCarro" :carro="carroSelecionado" @fechar-dialog="showDialogCarro = false"></infoCarComponent>
    <infoMotoComponent v-if="motoSelecionada" ref="infoMotoComponent" :show-dialog="showDialogMoto" :moto="motoSelecionada" @fechar-dialog="showDialogMoto = false"></infoMotoComponent>
      <v-scroll-x-transition>
        <v-data-table
        v-show="tableCarros"
        :headers="headersCarros"
        :items="itemsCarros"
        hide-default-footer
        >
        <template v-slot:item.id="{ item }">
      <v-chip
        color="orange"
        @click="carDialog(item)"
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
        ><template v-slot:item.id="{ item }">
        <v-chip color="orange"
        @click="motoDialog(item)">
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
import axios from 'axios'
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
          value: 'nome'
        },
        {
          text: 'Informações',
          value: 'id'
        },
        {
          text: 'Ano',
          value: 'ano'
        }],

        headersMotos: [{
          text: 'Motos',
          value: 'nome'
        },
        {
        text: 'Informações',
        value: 'id'
        },
        {
          text: 'Ano',
          value: 'ano'
        }],

        itemsCarros: [],
        itemsMotos: [],
        tableCarros: true,
        tableMotos: false,
        showDialogCarro: false,
        showDialogMoto: false,
        carroSelecionado: null,
        motoSelecionada: null
     }
    },

    mounted() {
      this.carroData();
    },

    methods: {
      async carroData() {
        const response = await this.$axios.get('/api/carro')
        this.itemsCarros = response.data
      },

      async showCarros() {
        this.tableMotos = false
        await this.sleep(300)
        this.tableCarros = true
      },

      async motoData() {
        const response = await this.$axios.get('/api/moto')
        this.itemsMotos = response.data
      },

      async showMotos(){
        this.tableCarros = false
        await this.motoData()
        await this.sleep(300)
        this.tableMotos = true
      },

      carDialog(item) {
        this.carroSelecionado = item
        this.showDialogCarro = true
      },

      motoDialog(item) {
        this.motoSelecionada = item
        this.showDialogMoto = true
      },

     sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }
  }
  }
</script>

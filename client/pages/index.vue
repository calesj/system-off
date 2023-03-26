<template>
  <v-col class="shrink">
    <v-app-bar app>
      <v-btn :elevation="0"
      >
        Carros
      </v-btn>
      <v-btn :elevation="0"
      >
        Motos
      </v-btn>
    </v-app-bar>
    <template>
      <v-card>
        <v-sheet
        >
          <v-skeleton-loader
            v-if="loading"
            type="table-tbody"
            width="100%"
          ></v-skeleton-loader>
        </v-sheet>
      <div v-if="!loading">
        <v-tabs
          v-model="tab"
          centered
          background-color="transparent"
        >
          <v-tab
          >
            Carros
          </v-tab>
          <v-tab
          >
            Motos
          </v-tab>
          <v-tab-item>
            <v-data-table
              v-if="tableCarros"
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
          </v-tab-item>
          <v-tab-item>
            <v-data-table
              v-if="tableMotos"
              :headers="headersMotos"
              :items="itemsMotos"
              hide-default-footer
            >
              <template v-slot:item.id="{ item }">
                <v-chip color="orange"
                        @click="motoDialog(item)">
                  <v-icon>
                    mdi-camera-outline
                  </v-icon>
                </v-chip>
              </template>
            </v-data-table>
          </v-tab-item>
        </v-tabs>
      </div>
      </v-card>
    </template>
    <!-- colocando ref no componente para acessar as referencias dele utilizando o $refs em algum método -->
    <!-- passando o valor da variavel showDialog para a props showDialog do componente filho; -->
    <!-- passando o valor da variavel carro para a props carro do componente filho -->
    <!-- só habilitará o componente, se existir um carro -->
    <!-- se por acaso o metodo fechar-dialogo dentro do componente for chamado, a variavel showDialogCarro passará a ser false -->
    <infoCarComponent
      v-if="carro"
      ref="infoCarComponent"
      :show-dialog="showDialogCarro"
      :carro="carro"
      @fechar-dialog="showDialogCarro = false">
    </infoCarComponent>
    <infoMotoComponent
      v-if="moto"
      ref="infoMotoComponent"
      :show-dialog="showDialogMoto"
      :moto="moto"
      @fechar-dialog="showDialogMoto = false"></infoMotoComponent>
  </v-col>
</template>
<script>
//carregando os componentes
import infoCarComponent from '~/components/carros/infoCarComponent.vue'
import infoMotoComponent from '~/components/motos/infoMotoComponent.vue'
import axios from 'axios'

export default {
  components: {
    infoCarComponent,
    infoMotoComponent
  },
  data() {
    return {
      tab: null,
      loading: true,
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
      tableMotos: true,
      showDialogCarro: false,
      showDialogMoto: false,
      carro: null,
      moto: null
    }
  },

  mounted() {
    //carrega os dados de todos os carros na tela inicial assim que a tela é carregada
    this.carroData()
    this.motoData()
    setTimeout(() => {
      this.loading = false
    }, 500)
  },

  methods: {
    //acessando a API e trazendo todos os carros do banco
    async carroData() {
      const response = await this.$axios.get('/api/carro')
      this.itemsCarros = response.data
    },


    //acessando a API e trazendo todas as motos do banco
    async motoData() {
      const response = await this.$axios.get('/api/moto')
      this.itemsMotos = response.data
    },

    //passa o id do item selecionado para a variavel carro
    carDialog(item) {
      this.carro = item
      this.showDialogCarro = true
    },

    //passa o id do item selecionado para a variavel moto
    motoDialog(item) {
      this.moto = item
      this.showDialogMoto = true
    },
  }
}
</script>

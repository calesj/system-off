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
      <v-card class="mx-auto" max-width="800">
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
              <v-toolbar flat>
                <v-toolbar-title>
                  Carros
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="carroForm = true">
                  Novo Carro
                </v-btn>
              </v-toolbar>
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
                    <v-icon>
                      mdi-camera-outline
                    </v-icon>
                  </v-chip>
                </template>
              </v-data-table>
            </v-tab-item>
            <v-tab-item>
              <v-toolbar flat>
                <v-toolbar-title>
                  Motos
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="motoForm = true">
                  Nova Moto
                </v-btn>
              </v-toolbar>
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
    <!--------------------------------------------------- COMPONENTES ------------------------------------------------>

    <!-- colocando ref no componente para acessar as referencias dele utilizando o $refs em algum método -->
    <!-- passando o valor da variavel showDialog para a props showDialog do componente filho; -->
    <!-- passando o valor da variavel carro para a props carro do componente filho -->
    <!-- só habilitará o componente, se existir um carro -->
    <!-- se por acaso o metodo fechar-dialogo dentro do componente for chamado, a variavel showDialogCarro passará a ser false -->
    <infoCarComponent
      v-if="carro"
      :show-dialog="showDialogCarro"
      :carro="carro"
      @close-dialog="showDialogCarro = false">
    </infoCarComponent>
    <infoMotoComponent
      v-if="moto"
      :show-dialog="showDialogMoto"
      :moto="moto"
      @close-dialog="showDialogMoto = false">
    </infoMotoComponent>

    <!--------------------------------------- COMPONENTES DE FORMULARIO ----------------------------------------------->
    <!-- passando a variavel carroForm como parâmetro para a props 'dialog' -->
    <!-- aqui está dizendo que se o método 'close-dialog' que está dentro do componente for acionado, ele chamará o
      método 'fecharDialog' aqui do index -->
    <carro-form
      :dialog="carroForm"
      @close-dialog="fecharDialog">
    </carro-form>
  <moto-form
    :dialog="motoForm"
    @close-dialog="motoForm = false">
  </moto-form>
  </v-col>
</template>
<script>
//carregando os componentes
import infoCarComponent from '~/components/carros/infoCarComponent.vue'
import infoMotoComponent from '~/components/motos/infoMotoComponent.vue'
import carroForm from '~/components/carros/carroForm.vue'
import motoForm from '~/components/motos/motoForm.vue';
import axios from 'axios'

export default {
  components: {
    infoCarComponent,
    infoMotoComponent,
    carroForm,
    motoForm
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
      moto: null,
      carroForm: false,
      motoForm: false
    }
  },

  mounted() {
    //carrega os dados de todos os carros na tela inicial assim que a tela é carregada
    this.carroData()
    this.motoData()

    // função de callback, nós enviamos uma função como parâmetro que representa o código que queremos congelar
    // como segundo parâmetro enviamos a quantidade de tempo que queremos congelar o código em ms
    setTimeout(() => {
      this.loading = false
    }, 500)
  },

  methods: {
    //esse método será acionado, caso o evento do componente carroForm for acionado
    //método utilizando para passar o valor 'false' para o carroForm, caso o usuario clique no botão de fechar
    //fiz dessa forma pra entender melhor como funciona a dinâmica de evento pai e filho
    fecharDialog(e) {
      this.carroForm = e
    },

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

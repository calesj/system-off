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
      <v-dialog v-model="dialogDelete" max-width="500px">
        <v-card>
          <v-card-title class="text-h5">Você deseja deletar esse item?</v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialogDelete = false">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="deleteItem()">OK</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
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
                <!-- envia o parametro 'carro' para a props 'typeVehicle' do formGeral -->
                <v-btn color="primary" @click="formGeral = true; tipoVeiculo = 'carro'">
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
                <template v-slot:item.actions="{ item }">
                  <v-icon
                    small
                    class="mr-2"
                    @click="editMethod(item, 'carro')"
                  >
                    mdi-pencil
                  </v-icon>
                  <v-icon
                    small
                    @click="dialogDeleteMethod(item, 'carro')"
                  >
                    mdi-delete
                  </v-icon>
                </template>
              </v-data-table>
            </v-tab-item>
            <v-tab-item>
              <v-toolbar flat>
                <v-toolbar-title>
                  Motos
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <!-- envia o parametro 'moto' para a props 'typeVehicle' do formGeral -->
                <v-btn color="primary" @click="formGeral = true; tipoVeiculo = 'moto' ">
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
                <template v-slot:item.actions="{ item }">
                  <v-icon
                    small
                    class="mr-2"
                    @click="editMethod(item, 'moto')"
                  >
                    mdi-pencil
                  </v-icon>
                  <v-icon
                    small
                    @click="dialogDeleteMethod(item, 'moto')"
                  >
                    mdi-delete
                  </v-icon>
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
    <!-- passando a variavel formGeral como parâmetro para a props 'dialog' -->
    <!-- aqui está dizendo que se o método 'close-dialog' que está dentro do componente for acionado, ele chamará o
      método 'fecharDialog' aqui do index -->
    <form-geral
      v-if="formGeral"
      :dialog="formGeral"
      :id-veiculo="id"
      :vehicle-type="tipoVeiculo"
      @close-dialog="fecharDialog"
      @novo-item-adicionado="atualizarItens">
    </form-geral>
  </v-col>
</template>
<script>
//carregando os componentes
import infoCarComponent from '~/components/carros/infoCarComponent.vue'
import infoMotoComponent from '~/components/motos/infoMotoComponent.vue'
import formGeral from "~/components/form/formGeral.vue";
import axios from 'axios'

export default {
  components: {
    infoCarComponent,
    infoMotoComponent,
    formGeral,
  },

  head() {
    return {
      title: 'Home'
    }
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
          },
          {
            text: 'Ações',
            value: 'actions',
            sortable: false
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
        },
        {
          text: 'Ações',
          value: 'actions',
          sortable: false
        }],
      itemsCarros: [],
      itemsMotos: [],
      tableCarros: true,
      tableMotos: true,
      showDialogCarro: false,
      showDialogMoto: false,
      editedIndex: -1,
      carro: null,
      moto: null,
      formGeral: false,
      tipoVeiculo: '',
      dialogDelete: false,
      id: 0,
      veiculo: ''
    }
  },

  mounted() {
    //carrega os dados de todos os carros na tela inicial assim que a tela é carregada
    this.carroData()
    this.motoData()

    this.$on('novo-item-adicionado', () => {
      console.log('novo item adicionado')
    })

    // função de callback, nós enviamos uma função como parâmetro que representa o código que queremos congelar
    // como segundo parâmetro enviamos a quantidade de tempo que queremos congelar o código em ms
    setTimeout(() => {
      this.loading = false
    }, 500)
  },

  methods: {
    //esse método será acionado, caso o evento do componente formGeral for acionado
    //método utilizando para passar o valor 'false' para o formGeral, caso o usuario clique no botão de fechar
    //fiz dessa forma pra entender melhor como funciona a dinâmica de evento pai e filho
    fecharDialog(e) {
      //assim que o dialog fechar, os valores serão resetados
      this.id = 0
      this.tipoVeiculo = null
      this.formGeral = false
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

    //método que abre o dialogDelete e passa as informações do item para as variveis this.id e this.veiculo
    dialogDeleteMethod(item, veiculo) {
      this.id = item.id
      this.veiculo = veiculo
      //abre o dialogDelete
      this.dialogDelete = true
      },

    //método que deleta o veiculo que está na variavel this.veiculo e this.id
    async deleteItem() {
      try {

        const response = await this.$axios.delete(`/api/${this.veiculo}/${this.id}`)

        //verifica se o  datatable que o usuario está a fazer a exclusão do item é o datatable de moto
        if (this.veiculo === 'moto') {

          //removendo a linha do datatable aonde estava o item que o usuario apagou
          this.itemsMotos.splice(this.editedIndex, 1)

          //fechando o dialogDelete
          this.dialogDelete = false

          //definindo os parâmetros pra 0 novamente
          this.id = 0
          this.veiculo = null
          return response.data
        }

        //verifica se o  datatable que o usuario está a fazer a exclusão do item é o datatable de carro
        if (this.veiculo === 'carro') {

          //removendo a linha do datatable aonde estava o item que o usuario apagou
          this.itemsCarros.splice(this.editedIndex, 1)

          //fechando o dialogDelete
          this.dialogDelete = false

          //definindo os parâmetros pra 0 novamente
          this.id = 0
          this.veiculo = null
          return response.data
        }
      } catch (e) {
        return alert('Algo deu errado')
      }
    },

    // passa as informações do tipo de veiculo e qual o id do veiculo para as variaveis
    // essas variaveis serão mandadas como valor para suas respectivas props do formGeral
    editMethod(item, veiculo) {
      this.id = item.id
      this.tipoVeiculo = veiculo
      this.formGeral = true
    },

    //atualiza os itens das tabelas depois que um novo é adicionado ou editado
    async atualizarItens() {
      const responseCarros = await this.$axios.get('/api/carro')
      const responseMoto = await this.$axios.get('/api/moto')
      this.itemsCarros = responseCarros.data
      this.itemsMotos = responseMoto.data
    }
  }
}
</script>

<template>
  <div id="formGeral">
    <v-snackbar
      top
      width="200px"
      height="70px"
      class="pa-16"
      v-model="snackbarSuccess"
      timeout="1500"
      color="success">
      <h2>{{ message}}</h2>
    </v-snackbar>
    <v-dialog
      max-width="600"
      v-model="dialog">
      <v-card
        :elevation="24"
        class="mx-auto"
        :disabled="loading"
        color="blue-grey darken-3">
        <div v-if="loading">
          <v-progress-linear
            :active="loading"
            :indeterminate="loading">
          </v-progress-linear>
        </div>
        <div>
          <v-card-actions>
            <v-card-title>Cadastrar Carro</v-card-title>
            <v-spacer></v-spacer>
            <!--atualizar o valor da props dialog para false
            caso o usuario clique nesse botao, será emitido um evento ao elemento pai dizendo que o método closeDialog
            foi acionado, dentro do elemento pai diz que caso esse método seja chamado, dialog será igual a false-->
            <v-btn @click="closeDialog()" icon>
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-actions>
          <v-card-text>
            <form>
              <v-text-field
                v-model="item.nome"
                label="Digite o nome do veiculo"
                :error-messages="errors.nome">
              </v-text-field>

              <v-file-input
                v-model="item.file_path"
                ref="fileInput"
                accept="image/png, image/jpeg, image/bmp"
                placeholder="Pick an avatar"
                prepend-icon="mdi-camera"
                label="Envie uma imagem do veiculo"
                :error-messages="errors.file_path"
                @change="exibirImagem"
                @clearable="item.file_path = null"
              ></v-file-input>
              <v-img class="mx-auto" max-height="400px" width="500px" v-if="imagemCarregada"
                     :src="imagemCarregada"></v-img>
              <v-text-field
                v-model="item.ano"
                label="Digite o Ano do veiculo"
                :error-messages="errors.ano">
                >
              </v-text-field>
              <v-card-actions>
                <v-btn color="green" @click="save()">Salvar
                </v-btn>
              </v-card-actions>
            </form>
          </v-card-text>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>

export default {
  name: 'formGeral',

  data() {
    return {
      item: {
        nome: '',
        file_path: '',
        ano: '',
      },
      errors: [],
      imagemCarregada: '',
      loading: false,
      message: '',
      snackbarSuccess: false,
    }
  },

  // utilizamos props, para o componente pai poder manipular o valor dessa propriedade por fora
  props: {
    idVeiculo: {
      type: Number,
      required: false
    },

    vehicleType: {
      type: String,
      required: true
    },
    // dialog é a propriedade que representa o v-model do nosso componente, utilizamos props, para o componente pai
    // poder manipular o valor dessa propriedade por fora
    dialog: {
      type: Boolean,
      required: true
    }
  },

  async mounted() {
    if(this.idVeiculo !== 0) {
      await this.fillForm()
    }
  },

  methods: {
    async fillForm() {
      const response = await this.$axios.get(`/api/${this.vehicleType}/${this.idVeiculo}`)
      this.item = response.data
    },

    exibirImagem() {
      // cria uma instância do método fileReader

      if (!this.item.file_path) {
        this.imagemCarregada = null
        return 0
      }
      const reader = new FileReader()

        // Define a função que será executada quando a imagem for carregada
        reader.onload = (event) => {
          // Atualiza o valor da propriedade imagemCarregada
          this.imagemCarregada = event.target.result
      }

      // Lê o arquivo selecionado pelo usuário
      reader.readAsDataURL(this.item.file_path)
    },

    async save() {
      this.loading = true
      try {
        const formData = new FormData()

        // percorrendo o this.item e adicionando as chaves e seus respectivos valores dentro do formData
        for (const key in this.item) {
          formData.append(key, this.item[key])
        }

        // verifica qual tabela está querendo criar um campo novo
        const response = (this.idVeiculo === 0) ? await this.$axios.post(`/api/${this.vehicleType}/`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data' //define o cabeçalho correto para o envio de um formData
          }
        }) : await this.$axios.post(`/api/${this.vehicleType}/${this.idVeiculo}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data' //define o cabeçalho correto para o envio de um formData
          }
        })
        this.loading = false
        this.closeDialog()
        this.message = response.data.message
        this.snackbarSuccess = true
        this.$emit('novo-item-adicionado')
        return response.data
      } catch (e) {
        this.loading = false
        this.errors = e.response.data.errors
      }
    },

    // evento será emitido para o componente pai, e enviará o valor 'false' como parâmetro
    closeDialog() {
      this.$emit('close-dialog')
    },
  }
}
</script>


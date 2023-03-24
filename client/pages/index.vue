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
      <v-scroll-x-transition>
        <v-data-table
        v-show="tableCarros"
        :headers="headersCarros"
        :items="itemsCarros"
        hide-default-footer
        ></v-data-table>
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
  export default {
    data () {
      return {
        headersCarros: [{
          text: 'Carros',
          value: 'car'
        }],

        headersMotos: [{
          text: 'Motos'
        }],
        
        itemsCarros: [{
          car: 'Opala 166'
      }],
        itemsMotos: [],
        tableCarros: true,
        tableMotos: false
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

     sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
      }
  }
  }
</script>
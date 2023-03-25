<template>
    <div id="infoCarComponent">
        <v-dialog
            v-model="showDialog"
            width="500">
            <v-card>
                <v-card-title>Informações do Carro</v-card-title>
                <v-card-text>Imagem: <v-img></v-img></v-card-text>
                <v-card-text>Nome:</v-card-text>
                <v-card-text>Ano de Fabricação:</v-card-text>
                <v-card-actions>

                    <!--atualizar o valor da props showDialog para false
                    caso o usuario clique no botao e emitindo esse valor para o componente pai se atualizar com o novo valor dado-->
                    <v-btn color="primary" @click="$emit('update:show-dialog', false)">
                        Fechar
                    </v-btn>
                    </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default{
    name: 'infoCarComponent',

    //Definindo a props dinâmica que virá do componente pai 
    props: {
        showDialog: {
            /*o valor que vier do componente pai, deve ser
            booleano e o valor padrão é falso*/
            type: Boolean,
            default: false,
        }
    },
}

</script>
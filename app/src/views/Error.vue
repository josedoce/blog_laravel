<template>
    <section class="error">
        <div>
            <h2>Erro {{code}}</h2>
            <h4>{{title}}</h4>
            <p>{{message}}</p>
            <router-link to="/">voltar para a home</router-link>
        </div>
    </section>
</template>
<script>
import router from '../router'

export default {
    props: {
        code: {
            type: String,
            default: '500'
        },
        title: {
            type: String,
            default: 'Erro no servidor'
        },
        message: {
            type: String,
            default: 'Desculpe, estamo atarefados, faça o favor de tentar novamente.'
        },
    },
}
</script>
<template>
    <section class="about">
        <div class="about__content">
            <div class="content__info">
                <h2>Sobre</h2>
                <div class="info">
                    author: <a href="http://github.com/josedoce" target="_blank" rel="noopener noreferrer">josedoce</a>
                   
                    <h4>libs/framwork frontend usadas no projeto:</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>nome</th>
                                <th>motivo</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>vuejs</td>
                                <td>simplicidade</td>
                            </tr>
                            <tr>
                                <td>vuex</td>
                                <td>para usar e modificar dados em outros components</td>
                            </tr>
                            <tr>
                                <td>vuerouter</td>
                                <td>para lidar com rotas</td>
                            </tr>
                            <tr>
                                <td>axios</td>
                                <td>para fazer requisições ajax a api</td>
                            </tr>
                            <tr>
                                <td>material design icons</td>
                                <td>para usar icones externos na aplicação</td>
                            </tr>
                            <tr>
                                <td>vuetify</td>
                                <td>para utilizar alguns estilos preprontos</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>libs/framwork backend usadas no projeto:</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>nome</th>
                                <th>motivo</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>laravel 8</td>
                                <td>aprendizado</td>
                            </tr>
                            <tr>
                                <td>sanctum</td>
                                <td>para lidar com autenticação e autorização do cliente</td>
                            </tr>
                        </tbody>
                    </table>
                    <h4>linguagens usadas no projeto:</h4>
                     <table>
                        <thead>
                            <tr>
                                <th>nome</th>
                                <th>motivo</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>php 8</td>
                                <td>laravel</td>
                            </tr>
                            <tr>
                                <td>javascript</td>
                                <td>vuejs</td>
                            </tr>
                            <tr>
                                <td>css</td>
                                <td>estilos</td>
                            </tr>
                            <tr>
                                <td>html</td>
                                <td>frontend da aplicação.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    
}
</script>

<style lang="scss" scoped>
@import '@/styles/page/about.scss';
</style>
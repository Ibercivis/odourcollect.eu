
<template>

    <div>
        <!--<div class="work">
    <img src="https://odourcollect.eu/img/general/nav-logo.svg" class="center" alt="">
    <h1>Estamos migrando de servidor</h1>
    <p>Pedimos disculpas</p>
    <p>Pronto reanudamos el servicio</p>
</div>!-->
    <oddour-map :singleMarker="singleMarker"></oddour-map>
    </div>
</template>

<script>
    import OddourMap from '../components/OddourMap.vue';
    export default{
        components: {OddourMap},
        data(){
            return {
                singleMarker: false,
            }
        },
        mounted() {
            this.$store.commit('goHome')
        }
    }
</script>

<!--<style scoped>
.work{
    width: 100%;
    height: 100vh;
    position: fixed;
    z-index: 999999;
    background: #fff;
    top: 0px;
    padding: 100px;
    padding-top: 17%;
}
.work h1{
    text-align: center;
    margin-bottom: 20px;
}
.work p{
    text-align: center;
    margin-bottom: 16px;
    font-size: 20px;
}
.work img{
    width: 160px;
    position: relative;
    margin: 0 auto;
    display: block;
    margin-bottom: 20px;
}
</style>!-->
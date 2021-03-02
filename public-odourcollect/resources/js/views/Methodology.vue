<template>
    <v-container>
        <div id="container" v-html="$t('METHODOLOGY.TEXT')" class="font-weight-regular subheading"></div>
    </v-container>
</template>

<script>

    export default{

        data(){
            return {
            }
        },
        mounted() {
            //Scroll to top the page
            var element = document.getElementById("container");
            var top = element.offsetTop;
            window.scrollTo(0, top - 20);
        }
    }
</script>

<style scoped lang="scss">
    @import '../../sass/app.scss';

    *{
        color: $font-color-dark!important;
    }
    .container {
        padding:50px 20px 40px;
        margin-top: -64px;
        min-height:95vh;
    }
    h1{
        margin: 10px 0 20px;
        font-size: 30px !important;
        font-weight: 600 !important;
    }
    h2 {
        margin: 30px 0 20px;
        font-weight: 600 !important;
        font-size: 22px !important;
    }
    p{
        margin: 20px 0;
    }
</style>
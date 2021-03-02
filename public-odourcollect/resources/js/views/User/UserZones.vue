<template>
    <div>
        <ul class="container">

            <div  v-show="cargador" class="loading-container align">
                <div class="loading-icon"><img :src="loading_icon"></div>
            </div>

            <div v-show="!cargador" class="loading-container align" v-if="zones.length == 0">
                <div class="loading-icon">
                    <img :src="noodour_icon">
                    <p>{{$t('MENU.NO_PRIVATE_MAP')}}</p>
                </div>
            </div>

            <div v-show="!cargador" v-else>
                <li v-for="zone in zones">
                    <a :id="'zone-' + zone.id">
                        <div @click="show_zone(zone.id)">
                            <p class="font-weight-medium title">{{zone.name}}</p>
                            <img class="arrow flip" :src="arrow_icon">
                        </div>
                    </a>
                    <v-divider></v-divider>
                </li>
            </div>

        </ul>
    </div>
</template>

<script>
export default {
    data(){
        return {
            isLoggedIn : null,
            name : null,
            zones: [],
            arrow_icon: '../../../img/general/nav-back.svg',
            loading_icon: '../../../img/general/loading.svg',
            noodour_icon: '../../../img/general/no-odour.png',
            cargador: true
        }
    },
    methods:{
        //Get the zone id to be shown
        show_zone(zone){
            this.$emit('clicked', ['zone', zone])
        }
    },
    mounted(){

        var vm = this;
        //Looks if the user is logged in
        if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
        if( this.isLoggedIn ){
            //If logged in save the user name in the data
            var user = JSON.parse( localStorage.getItem('user') )
            this.name = user.name + ' ' + user.surname
            var token = localStorage.getItem('auth-token');

        }

        //Get user zones list
        axios.post('../api/user/' + user.id + '/zones', {
            token: token
        }).then(response => {
            var data = response.data.object;

            if (data !== null){
                //Cargar la informacion
                data.forEach(function (point){
                    vm.zones.push(point);
                });
            }
            vm.cargador = false;

        }).catch(error => {
        });
    },
    beforeRouteEnter (to, from, next) {
        //Looks if the user is logged in, if not redirects to the login page
        if ( ! localStorage.getItem('auth-token') || localStorage.getItem('auth-token') == null ) {
            return next('login')
        }
        next()
    }
}
</script>

<style scoped lang="scss">
    @import '../../../sass/app.scss';

    *{
        color: $font-color-dark;
    }
    h1{
        margin: 30px 0px 20px 30px;
    }
    .v-btn{
        text-transform: uppercase;
        border-radius: 20px;
        margin-top: 20px;
        width: 70%;
    }
    .align{
        text-align: center;
    }
    li{
        list-style: none;
    }
    p{
        margin-bottom: 0;
    }
    .arrow{
        float: right;
        margin-top: -18px;
        margin-right: 5px;
    }
    .flip{
        transform: scaleX(-1);
    }
    .msg{
        margin-top:20px;
        font-size: 16px;
    }
    .title{
        font-size:  16px!important;
    }
    .msg{
        margin-top:20px;
        font-size: 16px;
    }
    .loading-container {
        height:85vh;
        width:100%;
        display:table;
    }
    .loading-container .loading-icon {
        display:table-cell;
        vertical-align:middle;
    }
    .loading-container .loading-icon img{
        max-width:150px;
        margin:0;
    }
    .loading-container .loading-icon p {
        font-size:16px;
        margin-top:20px;
    }
</style>
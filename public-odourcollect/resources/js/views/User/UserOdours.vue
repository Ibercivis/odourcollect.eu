<template>
    <div id="scroll">
        <ul>
            <div  v-show="cargador" class="loading-container align">
                <div class="loading-icon"><img :src="loading_icon"></div>
            </div>

            <div v-show="!cargador" class="loading-container align" v-if="oddours.length == 0">
                <div class="loading-icon">
                    <img :src="noodour_icon">
                    <p>{{$t('MENU.NO_ODOURS')}}</p>
                </div>
            </div>

            <div v-show="!cargador" v-else>
                <li v-for="oddour in oddours">
                    <a :id="'oddour-' + oddour.id">
                        <div @click="show_marker(oddour.id)">
                            <div>
                                <p class="font-weight-medium date">{{oddour.created_at}}</p>
                                <p class="font-weight-regular place"><img :src="loc_icon">{{oddour.location.place}}</p>
                            </div>
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
            oddours: [],
            token: '',
            arrow_icon: '../../../img/general/nav-back.svg',
            loc_icon: '../../../img/general/info-spot.svg',
            loading_icon: '../../../img/general/loading.svg',
            noodour_icon: '../../../img/general/no-odour.png',
            back_icon:  '../../../img/general/nav-back.svg',
            cargador: true,
        }
    },
    methods:{
      //Return the selected odour to be shown
      show_marker(oddour){
            this.$emit('clicked', ['odour', oddour])
        }
    },
    mounted(){
        var vm = this;

        var element = document.getElementById("scroll");
        var top = element.offsetTop;
        window.scrollTo(0, top - 20);

        //Looks if the user is logged in
        if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
        if( this.isLoggedIn ){
            //If logged in save the user name in the data
            var user = JSON.parse( localStorage.getItem('user') );
            this.name = user.name + ' ' + user.surname;
            vm.token = localStorage.getItem('auth-token');
        }

        var a = localStorage.language;

        //Get user odour list
        axios.post('../api/user/' + user.id + '/odours', {
            token: vm.token
            }).then(response => {
                var points = response.data.object;

                //Cargar la informacion
                points.forEach(function (point){
                    var options = {
                        year: "numeric",
                        month: "long",
                        day: "numeric"
                    };

                    var now = new Date(point.created_at.replace(/ /g,"T"));
                    var nowUtc = new Date( now.getTime() + (-(now.getTimezoneOffset()) * 60000));

                    point.created_at = nowUtc.toLocaleDateString(a,options);
                    vm.oddours.push(point);
                });

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
        margin: 10px 0 20px;
        font-size: 30px !important;
        font-weight: 600 !important;
    }
    .v-btn{
        text-transform: uppercase;
        border-radius: 20px;
        margin-top: 10px;
        width: 100%;
        font-size:15px !important;
        font-weight:600 !important;
        box-shadow: none !important;
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
        margin-top: -28px;
        margin-right: 5px;
    }
    .flip{
        transform: scaleX(-1);
    }
    img{
        margin-right: 10px;
    }
    .date{
        font-size:  16px!important;
        font-weight:600 !important;
    }
    .place{
        font-size: 12px!important;
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

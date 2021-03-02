<template>
    <v-card class="bottom-nav" flat>

        <div class="scroll">
            <div  v-show="cargador" class=" loading-container align">
                <div class="loading-icon"><img :src="loading_icon"></div>
            </div>

            <div  v-show="!cargador"  class="loading-container align" v-if="comments.length == 0">
                <div class="loading-icon">
                    <p>{{$t('DETAIL_ODOUR.NO_COMMENTS')}}</p>
                </div>
            </div>

            <div v-show="!cargador" v-else>
                <ul style="position: relative">
                    <li v-for="c in comments">
                        <a :id="'zone-' + c.id">
                            <p class="font-weight-bold title"><img :src="user_icon">{{$t('MENU.USER')}}{{c.user.id}}</p>
                            <p class="font-weight-regular description">{{c.comment}}</p>
                            <p class="font-weight-light date">{{c.created_at}}</p>
                            <p class="comment-delete" @click="openDialog = true; selected = c.id" v-if="id_user === c.id_user"><img :src="close_icon"></p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="openDialog" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title-box">{{$t('UI.INFO.DELETE')}}
                            <div class="info-close" @click="openDialog = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply delete-btn" @click="commentDelete"> {{$t('UI.INFO.YES')}}</h2>
                            <h2 class="apply delete-btn" @click="openDialog = false"> {{$t('UI.INFO.NO')}}</h2>
                        </div>

                        <div style="flex: 1 1 auto;"></div>
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>

        <v-layout row justify-center>
            <v-dialog v-model="error" transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <h2 color="primary" class="subheading font-weight-medium title-box">{{$t('UI.INFO.ERROR')}}
                        <div class="info-close" @click="error = false"><img :src="close_icon"></div>
                    </h2>

                    <div class="separator"></div>

                    <v-card-text>
                        {{$t('UPDATE_PROFILE.KO')}}
                    </v-card-text>

                    <div class="apply-btn">
                        <v-divider style="margin-top: 0px;"></v-divider>
                        <h2 class="apply" @click="error = false">{{$t('UI.INFO.AGREE')}}</h2>
                    </div>
                    <div style="flex: 1 1 auto;"></div>
                </v-card>
            </v-dialog>
        </v-layout>

        <div v-if="isLoggedIn" class="comment-div">
            <div class="comment-field">
                <v-text-field color="#00b187" required v-model="input_comment"></v-text-field>
            </div>
            <p class="font-weight-regular subheading description comment" @click="submit">{{$t('DETAIL_ODOUR.TO_COMMENT')}}</p>
        </div>

    </v-card>
</template>

<script>
    export default {
        props: {
            oddour: { type: Number },
            author: { type: Boolean }
        },
        data(){
            return {
                isLoggedIn : null,
                name : null,
                comments : [],
                input_comment: '',
                id_odor: '',
                id_user: '',
                token: '',
                cargador: true,
                user_icon: '../../../img/general/user-mini.svg',
                loading_icon: '../../../img/general/loading.svg',
                close_icon: '../../../img/general/close-mini.svg',
                openDialog: false,
                selected: null,
                error: false
            }
        },
        mounted(){
            var vm = this;

            if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ) {
                var user = JSON.parse(localStorage.getItem('user'))
                this.name = user.name;
                vm.token = localStorage.getItem('auth-token');
                vm.id_user = user.id;
            }

            vm.id_odor = vm.oddour;
            vm.chargeComments();
        },
        methods:{
            //Save comment in db
            submit (){
                var vm = this;

                if(vm.input_comment) {
                    axios.post('/api/comment/store', {
                        token: vm.token,
                        id_odor: vm.id_odor,
                        id_user: vm.id_user,
                        comment: vm.input_comment
                    }).then(response => {
                        vm.input_comment = '';
                        vm.chargeComments();
                    }).catch(error => {
                    });
                }
            },

            //Charge all comments from one odour
            chargeComments(){
                var vm = this;

                vm.comments = [] ;

                axios.post('/api/odor/' + vm.id_odor + '/comments', {
                }).then(response => {
                    var data = response.data.object;

                    data.forEach(function (point) {
                        var now = new Date(point.created_at.replace(/ /g,"T"));
                        var userAgent = window.navigator.userAgent;

                        if (userAgent.match(/iPad/i) || userAgent.match(/iPhone/i)) {
                            var nowUtc = new Date( now.getTime() + (-(0) * 60000));
                        }
                        else {
                            var nowUtc = new Date( now.getTime() + (-(now.getTimezoneOffset()) * 60000));
                        }
                        point.created_at = nowUtc.toLocaleString("en-GB");
                        vm.comments.push(point);
                    })
                    vm.cargador = false;

                }).catch(error => {
                });
            },
            commentDelete(){
                this.openDialog = false;

                axios.post('/api/comment/' + this.selected + '/hide', {
                    token: this.token,
                }).then(response => {
                    this.chargeComments();

                }).catch(error => {
                    this.error = true;
                });
            }
        },
    }
</script>

<style scoped lang="scss">
    @import './../../sass/app.scss';

    *{
        color: $font-color-dark;
    }
    .bottom-nav {
        position: fixed;
        bottom: 0px;
        width: 100%;
        right: 0;
        height: 100%;
        z-index: 3;
    }
    h1{
        margin: 10px 0 20px;
        font-size: 30px !important;
        font-weight: 600 !important;
    }
    li{
        list-style: none;
        margin-bottom: 25px;
    }
    p{
        margin: 0;
    }
    .title{
        margin-top: 5px;
        margin-bottom: 5px;
        font-size: 12px!important;
    }
    .date{
        margin-bottom: 5px;
        color: $light-gray;
        font-size: 11px!important;
    }
    .description{
        margin-bottom: 5px;
        font-size: 15px!important;
        line-height: 130%;
    }
    ul{
        padding: 10px 20px;
        margin-top: 30px;
    }
    .scroll{
        overflow-y: scroll;
        padding-bottom: 70px;
    }
    .comment-div{
        position: fixed;
        bottom: 0;
        background-color: white;
        width: 100%;
        padding: 20px;
    }
    .comment-field{
        width: 70%;
        float: left;
    }
    .comment{
        width: 25%;
        float: right;
        color: $primary-font-color;
        margin-top: 22px;
        font-size: 15px!important;
        font-weight: 600 !important;
        cursor: pointer;
    }
    .align{
        text-align: center;
    }
    .msg{
        margin-top:20px;
        font-size: 16px;
    }
    img{
        margin-right: 10px;
    }
    .loading-container {
        height:80vh;
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
    .comment-delete{
        position: absolute;
        right: 23px;
        margin-top: -73px;
    }
    .apply{
        color: $primary-font-color;
        text-transform: uppercase;
        text-align: center;
        padding-bottom: 10px;
        font-size: 15px!important;
        font-weight: 600;
        cursor: pointer;
    }
    .apply-btn{
        text-align: center;
        bottom: -20px;
        width: 100%;
        left: 0;
        background-color: white;
    }
    .apply-btn.map{
        margin-bottom: 20px;
    }
    .info-close{
        position: absolute;
        right: 14px;
        top: 4px;
        padding: 10px;
        cursor: pointer;
    }
    .separator {
        border-bottom: 1px solid #dadada;
        margin-top: 5px;
    }
    .title-box{
        text-align: center;
        font-size: 14px!important;
        margin-top: 16px;
        font-weight: 600!important;
    }
    .delete-btn{
        float: right;
        width: 50%;
    }

</style>

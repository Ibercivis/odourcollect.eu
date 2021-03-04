<template>
    <v-card class="bottom-nav" flat>

        <v-card-text>
            <span class="font-weight-bold title"><img :src="user_icon">{{oddour.user}}</span><br>
            <div class="sub-container">
                <div class="type three-columns column-text">{{$t('DETAIL_ODOUR.TYPE')}}<div class="info_type" @click="info1 = true"><img :src="info_icon"></div>
                        
                    <br><div class="odor_type" v-if="oddour.parent_type != 8 && oddour.parent_type != 9"><span>{{oddour.parent_type_name}}:</span><br>{{oddour.type_name}}</div>

                    <div class="odor_type" v-if="oddour.parent_type == 8"><span>{{oddour.type_name}}:</span><br>{{oddour.other}}</div>
                    <div class="odor_type" v-if="oddour.parent_type == 9">{{$t('NOODOR.TEXT')}}</div>
                    </div>
                <div class="type three-columns column-chart chart">{{$t('DETAIL_ODOUR.INTENSITY')}} <br>
                    <v-progress-circular class="chart-graph" :rotate="90" :size="80" :color="intensity_fill" :width="10" :value="(oddour.intensity - 1)*16.6666667">{{oddour.intensity - 1}}</v-progress-circular>
                </div>
                <div class="type three-columns column-chart chart">{{$t('DETAIL_ODOUR.ANNOYANCE')}} <br>
                    <v-progress-circular class="chart-graph" :rotate="90" :size="80" :color="annoy_fill" :width="10" :value="((oddour.annoy - 1)*12.5)">{{oddour.annoy - 5}}</v-progress-circular>
                </div>
                <div style="clear: both;"></div>
            </div>

            <div class="two-column">
                <div class="font-weight-bold where"><img :src="loc_icon">{{oddour.loc}}</div>
                <div class="font-weight-regular date">{{oddour.date}}</div>
            </div>

            <div class="like"><img :src="like_icon">{{$t('DETAIL_ODOUR.NUM_CONFIRMATIONS',{confirmations: oddour.confirmations})}}</div>

            <div class="font-weight-regular description">{{oddour.description}}</div>

            <div v-if="oddour.comments != 0" class="font-weight-regular body-2 comments">
                <div @click="showComments"><img :src="bubble_icon">{{$t('DETAIL_ODOUR.NUM_COMMENTS',{comments: oddour.comments})}}</div>
            </div>

            <div class="font-weight-regular comments" v-else>
                <div @click="showComments"><img :src="bubble_icon">{{$t('DETAIL_ODOUR.ADD_COMMENT')}}</div>
            </div>

            <v-card-actions class="icon-container" style="width: 100%">
                <v-btn color="secondary" class="large-button body-2 font-weight-regular btn-delete" v-if="myauthor && login" @click="deleteOdour = true">{{$t('INFORMATION.MAP.DELETE')}}</v-btn>

                <div class="right-btn">
                    <img v-if="like && login" :src="load_icon">
                    <img class="pointer" v-if="!isConfirmed && !author && !like && login" @click="getOddourStatus" :src="thumb_up_icon">
                    <img class="pointer" v-if="isConfirmed && !author && !like && login" @click="getOddourStatus" :src="thumb_on_icon">
                    <div class="pointer" @click="showComments"><img :src="comment_icon"></div>
                </div>
                <!--<img class="share-icon" :src="share_icon">-->
            </v-card-actions>

        </v-card-text>


        <v-layout row justify-center>
            <v-dialog v-model="info1" transition="dialog-bottom-transition" scrollable>
                <v-card flat>

                    <h2 color="primary" class="subheading font-weight-medium title">{{$t('DETAIL_ODOUR.INFO.TITLE')}}
                        <div class="info-close" @click="info1 = false"><img :src="close_icon"></div>
                    </h2>

                    <div class="separator"></div>

                    <v-card-text v-html="$t('DETAIL_ODOUR.INFO.CONTENT')"></v-card-text>

                    <div class="apply-btn">
                        <v-divider style="margin-top: 0px;"></v-divider>
                        <h2 class="apply" @click="info1 = false">{{$t('DETAIL_ODOUR.INFO.ACTION')}}</h2>
                    </div>

                    <div style="flex: 1 1 auto;"></div>
                </v-card>
            </v-dialog>
        </v-layout>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="deleteOdour" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title-box">{{$t('UI.INFO.ODOUR_DELETE.TITLE')}}
                            <div class="info-close" @click="deleteOdour = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="separator"></div>

                        <v-card-text class="center delete-msg" v-html="$t('UI.INFO.ODOUR_DELETE.CONTENT')"></v-card-text>

                        <div class="apply-btn">
                            <h2 class="pointer apply delete-btn" @click="odourDelete"> {{$t('UI.INFO.YES')}}</h2>
                            <h2 class="pointer apply delete-btn" @click="deleteOdour = false"> {{$t('UI.INFO.NO')}}</h2>
                        </div>

                        <div style="flex: 1 1 auto;"></div>
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>

    </v-card>
</template>

<script>

    export default {

        props:{
            oddour: { type: Object },
            display_div: { type: String },
            confirmed: { type: Boolean },
            author: { type: Boolean }
        },

        data(){
            return {
                isLoggedIn : null,
                user_id: '',
                token: '',
                isConfirmed: false,
                intensity_fill : '#58B5C7',
                annoy_fill: '#8C86D3',
                login: false,
                loc_icon: '../../../img/general/info-spot.svg',
                thumb_up_icon: '../../../img/general/info-like-button.svg',
                thumb_on_icon:  '../../../img/general/info-like-button-on.svg',
                like_icon: '../../../img/general/info-like.svg',
                bubble_icon: '../../../img/general/info-comments.svg',
                share_icon: '../../../img/general/info-share.svg',
                comment_icon: '../../../img/general/info-comments-button.svg',
                user_icon: '../../../img/general/user-mini.svg',
                info_icon: '../../../img/general/moreinfo-button-create.svg',
                close_icon: '../../../img/general/close-mini.svg',
                load_icon:'../../../img/general/ajax-loader.gif',
                info1: false,
                show_comments: false,
                like: false,
                deleteOdour: false,
                myauthor: false
            }
        },

        watch: {
            //Detects the change in the variable
            confirmed: function (val) {
                return this.isConfirmed = this.confirmed;
            },
            author:function (val) {
                return this.myauthor = this.author;
            },
        },

        mounted(){
            var vm = this;


            if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ){
                //If logged in save the user name in the data
                var user = JSON.parse( localStorage.getItem('user') );
                vm.token = localStorage.getItem('auth-token');
                vm.user_id = user.id;
                vm.login = true;
            } else {
                vm.login = false;
            }
            vm.isConfirmed = vm.confirmed;
            vm.myauthor = vm.author;
        },

        methods:{
            //Get if the odour is confirmed or not by the user
            getOddourStatus(){
                var vm = this;
                vm.like = true;

                axios.post('../../api/odor/' + vm.oddour.id + '/is-confirmed/' + vm.user_id, {
                    token: vm.token,
                }).then(response => {
                    if(!response.data.confirmed){
                        this.confirmOdor();
                    } else{
                        this.unconfirmOdor();
                    }
                }).catch(error => {
                    this.alert = true
                });


            },

            //Show odour's comment
            showComments(event){
                var vm = this;
                vm.show_comments = true;
                this.$emit('clicked', 'comments')
            },

            //Confirm the odour
            confirmOdor(){
                var vm = this;

                axios.post('../../api/odor/confirm', {
                    token: vm.token,
                    id_odor: vm.oddour.id,
                    id_user: vm.user_id,
                }).then(response => {
                    vm.oddour.confirmations =  vm.oddour.confirmations + 1;
                    vm.isConfirmed = true;
                    vm.like = false;
                }).catch(error => {
                    //If response code is 401 / 403 / 500 show alert of bad login or login problems
                    this.alert = true
                });
            },

            //Unconfirm the odour
            unconfirmOdor(){
                var vm = this;

                axios.post('../../api/odor/' + vm.oddour.id + '/unconfirm/' + vm.user_id, {
                    token: vm.token,
                }).then(response => {
                    vm.oddour.confirmations =  vm.oddour.confirmations - 1;
                    vm.isConfirmed = false;
                    vm.like = false;

                }).catch(error => {
                    this.alert = true
                });
            },

            odourDelete(){
                var vm = this;

                vm.deleteOdour = false;
                axios.post('../../api/odor/' + vm.oddour.id + '/delete', {
                    token: vm.token,
                }).then(response => {
                    this.$emit('clicked', 'delete')
                }).catch(error => {
                    this.alert = true
                });
            },

            progress(event,progress,stepValue){},

            progress_end(event){}
        }
    }
</script>

<style lang="scss">
    .v-progress-circular__info {
        font-size: 20px !important;
        color: #535353;
    }
    .v-progress-circular__underlay {
        opacity:0;
    }
</style>
<style scoped lang="scss">
    @import './../../sass/app.scss';

    *, a{
        color: $font-color-dark;
    }
    .center{
        text-align: center;
    }
    .delete-msg{
        margin-bottom: 20px;
    }
    .bottom-nav {
        position: fixed;
        bottom: 0px;
        width: 100%;
        right: 0;
        height: 350px;
    }
    .v-btn{
        text-transform: uppercase;
        border-radius: 20px;
        margin-top: 20px;
        width: 50%;
    }
    .thumb-icon{
        margin-right: 10px;
    }
    .share-icon{
        /*margin-top: 20px;*/
        /*margin-left: auto;*/
        /*margin-right: 30px;*/
    }
    .sub-container{
        background-color: $secondary-bg;
        color: $font-color-dark;
        padding:15px 10px;
        box-sizing: border-box;
        margin-bottom: 10px;
        margin-top: 10px;
        border-radius: 13px;
    }
    .apply{
        color: $primary-font-color;
        text-transform: uppercase;
        text-align: center;
        padding-bottom: 10px;
        font-size: 15px!important;
        font-weight: 600;
    }
    .apply-btn{
        text-align: center;
        bottom: -20px;
        width: 100%;
        left: 0;
        background-color: white;
    }
    .date{
        margin-left: 23px;
        font-size: 10px;
        font-weight: 600;
    }
    .title{
        font-size: 12px!important;
    }
    .where{
        font-size: 12px;
    }
    .type{
        font-size: 11px;
        font-weight: 600;
    }
    .odor_type{
        font-size: 16px;
        line-height: 110%;
        text-transform: uppercase;
    }
    .odor_type span {
        display:inline-block;
        margin:5px 0 0;
        font-size:12px;
        line-height: 110%;
    }
    .description{
        margin-top: 30px;
        font-size: 14px;
        line-height: 130%;
        max-height: 55px;
        overflow: scroll;
    }
    .comments{
        margin-top: 10px;
        font-size: 13px !important;
        cursor: pointer;
    }
    .two-column{
        width: 50%;
        float: left;
        margin-right: 20px;
    }
    .like {
        text-align:right;
    }

    .v-card__actions{
        padding-bottom: 0px;
    }
    .v-card__title{
        position: fixed;
        background-color: white;
        width: 100%;
        z-index: 1;
        padding: 11px 16px!important;
    }
    .v-card__text{
        padding: 10px 20px!important;
        height: 98%;
    }
    .three-columns{
        float: left;
    }
    .column-text {
        width:50%;
        position:relative;
    }
    .column-chart {
        width:25%;
    }
    .chart{
        text-align: center;
    }
    .chart-graph{
        margin-top: 5px;
        background-color: white;
        border-radius: 50%;
        border: 6px solid white;
    }
    img{
        margin-right: 10px;
    }
    .icon-container{
        position: absolute;
        bottom: 15px;
        right: 5px;
    }
    .icon-container img{
        margin-left: 10px;
    }
    .title{
        text-align: center;
        margin-top: 16px;
        font-weight: 600!important;
    }
    .info-close{
        position: absolute;
        right: 14px;
        top: 4px;
        padding: 10px;
        cursor: pointer;
    }
    .info_type{
        position:absolute;
        top:-8px;
        right:0;
        padding: 5px;
        cursor: pointer;
    }
    .info_type img{ 
        margin:0;
    }
    .separator {
        border-bottom: 1px solid #dadada;
        margin-top: 5px;
    }
    .v-dialog__content {
        align-items: flex-end;
        display: flex;
        height: 100%;
        justify-content: center;
        left: 0;
        pointer-events: none;
        position: fixed;
        transition: .2s cubic-bezier(.25,.8,.25,1);
        width: 100%;
        z-index: 6;
        outline: none;
        top: 0;
    }
    .v-dialog__content--active .v-dialog {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .v-dialog__content--active .v-dialog--active {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .v-dialog__content .v-dialog {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .v-dialog {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .v-dialog--active {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .v-dialog--scrollable {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .pointer{
        cursor: pointer;
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
    .large-button{
        width: 124px;
        height: 40px;
        margin-top:0;
        margin-left:0;
    }
    .btn-delete{
        position: relative;
        margin-left: 10px;
    }
    .right-btn{
        position: relative;
        margin-left: auto;
        display: flex;
    }
    @media only screen and (max-width: 400px) {
        .column-text {
            width: 40%;
        }
        .column-chart {
            width: 30%;
        }
        .odor_type {
            font-size: 15px;
        }
        .odor_type span {
            font-size: 11px;
        }
    }
</style>

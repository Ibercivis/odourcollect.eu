<template>
    <div>
        <v-navigation-drawer color="#000" v-model="drawerRight" fixed right clipped app>
            <v-list>

                <div v-if="isLoggedIn">
                   
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">Id: {{user_id}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                     </v-list-tile>

                    <v-divider></v-divider>
                    
                    <v-list-tile @click="goProfile" class="menu">
                        <v-list-tile-action>
                            <img :src="user_icon">
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">{{$t('MENU.USER_PROFILE')}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider></v-divider>

                    <v-list-tile @click="goPassword" class="menu">
                        <v-list-tile-action>
                            <img :src="password_icon">
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">{{$t('MENU.USER_PASSWORD')}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider></v-divider>

                    <v-list-tile @click="goOdours" class="menu">
                        <v-list-tile-action>
                            <img :src="odour_icon">
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">{{$t('MENU.USER_ODOURS')}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider></v-divider>

                    <v-list-tile @click="goZones" class="menu">
                        <v-list-tile-action>
                            <img :src="zones_icon">
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">{{$t('MENU.USER_ZONES')}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider></v-divider>
                </div>

                <div class="unlogged-menu" v-else>
                    <v-divider></v-divider>

                    <v-list-tile @click="goLogin" class="menu">
                        <v-list-tile-action>
                            <img :src="user_icon">
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">{{$t('MENU.LOGIN')}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider></v-divider>

                    <v-list-tile @click="drawerRight = false; register = true;" class="menu">
                        <v-list-tile-action>
                            <img :src="register_icon">
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <p class="font-weight-light title">{{$t('MENU.REGISTER')}}</p>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-divider></v-divider>
                </div>

                <div class="bottom-options">

                    <select @change="changeLanguage($event)">
                        <option :selected="locale === language.value" v-for="language in languages" v-bind:value="language.value">
                            {{$t('MENU.LANGUAGE')}}: {{ language.text }}
                        </option>
                    </select>

                    <v-list-tile @click="goContact">
                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-bold bottom-title">{{$t('MENU.CONTACT')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile @click="goAbout">
                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-light bottom-title">{{$t('MENU.ABOUT')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile @click="goMethodology">
                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-light bottom-title">{{$t('MENU.METHODOLOGY')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile @click="goLegal">
                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-light bottom-title">{{$t('MENU.LEGAL')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile @click="goEthics">
                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-light bottom-title">{{$t('MENU.ETHICS')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile @click="logout" v-if="isLoggedIn">
                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-light bottom-title">{{$t('MENU.LOGOUT')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </div>
            </v-list>
        </v-navigation-drawer>

        <v-toolbar class="fixed_top" fixed app clipped-right>

            <v-toolbar-side-icon v-if="!create_odours" @click="goCreateOdour"><img :src="add_icon_top"></v-toolbar-side-icon>
            <v-toolbar-side-icon v-if="create_odours" @click="cancelCreateOdour">
                <p class="left-button">{{$t('UI.BOTTOM_BAR.BUTTONS.CANCEL')}}</p>
            </v-toolbar-side-icon>


            <v-toolbar-title class="text-md-center">
                <div @click="resetAll" class="text-md-center font-weight-regular logo"><img class="logo pointer" :src="logo_icon"></div>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-side-icon @click.stop="drawerRight = !drawerRight"><img :src="menu_icon"></v-toolbar-side-icon>
        </v-toolbar>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="popup" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title-popup">{{$t('UI.LOG_MSG.TITLE')}}
                            <div class="info-close" @click="popup = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="separator"></div>

                        <v-card-text style="padding-top: 60px;padding-bottom: 100px;">
                            <div class="apply-btn">
                                <div class="apply-btn map">
                                    <p>{{$t('UI.LOG_MSG.CONTENT')}}</p>
                                    <p class="map-btn pointer" @click="goLogin">{{$t('UI.LOG_MSG.ACTION')}}</p>
                                </div>

                                <div class="apply-btn map">
                                    <p class="map-btn pointer" @click="goRegister">{{$t('UI.LOG_MSG.ACTION_REGISTER')}}</p>
                                </div>
                            </div>
                        </v-card-text>

                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="popup = false">{{$t('UI.BOTTOM_BAR.BUTTONS.CANCEL')}}</h2>
                        </div>

                        <div style="flex: 1 1 auto;"></div>
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>


        <v-card v-if="show_bottom" class="bottom-nav" flat>

            <v-bottom-nav :active.sync="bottomNav" :value="true" absolute color="transparent">
                <v-btn color="secondary" flat value="olores" @click="goOdourList">
                    <span>{{$t('UI.BOTTOM_BAR.BUTTONS.ODOURS')}}</span>
                    <img :src="myodours_icon">
                </v-btn>

                <v-btn color="secondary" flat value="newOdour" @click="goCreateOdour">
                    <span>{{$t('UI.BOTTOM_BAR.BUTTONS.NEW')}}</span>
                    <img class="add-btn" :src="add_icon">
                </v-btn>

                <v-btn color="secondary" flat value="filter" @click="filtersApply">
                    <span>{{$t('UI.BOTTOM_BAR.BUTTONS.FILTERS')}}</span>
                    <img :src="filter_icon">
                </v-btn>

            </v-bottom-nav>

        </v-card>

        <div v-if="create_odours">
            <create-odour @clicked="new_odour" class="content_create"></create-odour>
        </div>

        <div v-if="user_profile">
            <div class="back_toolbar">
                <img class="pointer" @click="user_profile = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.USER_PROFILE')}}</p>
            </div>
            <user-profile class="content"></user-profile>
        </div>

        <div v-if="user_password">
            <div class="back_toolbar">
                <img  class="pointer" @click="user_password = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.USER_PASSWORD')}}</p>
            </div>
            <user-password class="content"></user-password>
        </div>

        <div v-if="user_odours">
            <div class="back_toolbar">
                <img class="pointer" @click="resetAll" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.USER_ODOURS')}}</p>
            </div>
            <user-odours @clicked="select_odour" class="content content-light"></user-odours>
        </div>

        <div v-if="user_odours_bar">
            <div class="back_toolbar">
                <img  class="pointer" @click="select_odour('close_details'); user_odours_bar = false; user_odours = true" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.USER_ODOURS')}}</p>
            </div>
        </div>

        <div v-if="user_zones">
            <div class="back_toolbar">
                <img class="pointer" @click="user_zones = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.USER_ZONES')}}</p>
            </div>
            <user-zones @clicked="select_zone" class="content"></user-zones>
        </div>

        <div v-if="about">
            <div class="back_toolbar">
                <img  class="pointer" @click="about = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.ABOUT')}}</p>
            </div>
            <about-page class="content-large"></about-page>
        </div>

        <div v-if="methodology">
            <div class="back_toolbar">
                <img class="pointer" @click="methodology = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.METHODOLOGY')}}</p>
            </div>
            <methodology-page class="content-large"></methodology-page>
        </div>

        <div v-if="login">
            <div class="back_toolbar">
                <img  class="pointer" @click="login = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.LOGIN')}}</p>
            </div>
            <login-page @clicked="login_action" class="content-white"></login-page>
        </div>

        <div v-if="register">
            <div class="back_toolbar">
                <img  class="pointer" @click="register = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.REGISTER')}}</p>
            </div>
            <register-page @clicked="login_action" class="content-white"></register-page>
        </div>

        <div v-if="legal">
            <div class="back_toolbar">
                <img  class="pointer" @click="legal = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.LEGAL')}}</p>
            </div>
            <legal-page class="content-large"></legal-page>
        </div>

        <div v-if="ethics">
            <div class="back_toolbar">
                <img  class="pointer" @click="ethics = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.ETHICS')}}</p>
            </div>
            <ethics-page class="content-large"></ethics-page>
        </div>

        <div v-if="contact">
            <div class="back_toolbar">
                <img class="pointer" @click="contact = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.CONTACT')}}</p>
            </div>
            <contact-page class="content-large"></contact-page>
        </div>

        <div v-if="recover">
            <div class="back_toolbar">
                <img  class="pointer" @click="recover = false" :src="back_icon">
                <p class="text-md-center">{{$t('RECOVER.FORGOT_PW')}}</p>
            </div>
            <recover-page @clicked="login_action" class="content-white"></recover-page>
        </div>

    </div>
</template>

<script>

    import UserOdours from '../views/User/UserOdours.vue';
    import CreateOdour from '../views/Odour/OdourCreate.vue';
    import LoginPage from '../views/Login.vue';
    import RegisterPage from '../views/Register.vue';
    import RecoverPage from '../views/Recover.vue';
    import LegalPage from '../views/Legal.vue';
    import EthicsPage from '../views/Ethics.vue';
    import UserProfile from '../views/User/UserProfile.vue';
    import UserPassword from '../views/User/UserPassword.vue';
    import UserZones from '../views/User/UserZones.vue';
    import AboutPage from '../views/About.vue';
    import MethodologyPage from '../views/Methodology.vue';
    import ContactPage from '../views/Contact';
    import store from '../store/store';

    export default {

        components: {UserOdours, CreateOdour, LoginPage, RegisterPage, RecoverPage, LegalPage, EthicsPage, UserProfile, UserPassword, UserZones, AboutPage, MethodologyPage, ContactPage },

        data(){
            return {
                drawerRight: false,
                drawer: false,
                add_icon_top: '../../img/general/nav-add.svg',
                arrow_icon: '../../img/general/nav-back.svg',
                menu_icon: '../../img/general/nav-menu.svg',
                zones_icon: '../../img/menu/menu-maps.svg',
                user_icon: '../../img/menu/menu-user.svg',
                password_icon: '../../img/menu/menu-password.svg',
                odour_icon: '../../img/menu/menu-myodours.svg',
                register_icon: '../../img/menu/menu-register.svg',
                logo_icon: '../../img/general/nav-logo.svg',
                bottomNav: 'recent',
                filters: '',
                myodours_icon: '../../img/nav-bar/icon-myodours.svg',
                add_icon: '../../img/nav-bar/icon-addodour.svg',
                filter_icon: '../../img/nav-bar/icon-filter.svg',
                close_icon: '../../img/general/close-mini.svg',
                back_icon:  '../../img/general/nav-back.svg',
                isLoggedIn : null,
                name : null,
                user_id : null,
                popup: false,
                user_odours: false,
                create_odours: false,
                user_odours_bar: false,
                login: false,
                register: false,
                legal: false,
                ethics: false,
                recover: false,
                user_profile: false,
                user_password: false,
                user_zones: false,
                about: false,
                methodology: false,
                contact: false,
                show_bottom: true,
                languages: [
                    { text: this.$t('LANGUAGE.EN'), value: 'en' },
                    { text: this.$t('LANGUAGE.ES'), value: 'es' },
                    { text: this.$t('LANGUAGE.CA'), value: 'ca' },
                    { text: this.$t('LANGUAGE.PT'), value: 'pt' },
                    { text: this.$t('LANGUAGE.DE'), value: 'de' },
                    { text: this.$t('LANGUAGE.IT'), value: 'it' },
                    { text: this.$t('LANGUAGE.EL'), value: 'el' }

                ],
                locale: localStorage.language
            }
        },
        methods:{

            logout(){
                localStorage.removeItem('user');
                localStorage.removeItem('auth-token');
                this.$store.commit('token', null);
                this.$store.commit('user', null);
                this.isLoggedIn = null;
                this.drawerRight = false;
                this.create_odours = false;
                this.goHome();
            },
            changeLanguage(){
                var value =  event.target.value;

                if (!this.$i18n.messages[value]){
                    this.$i18n.locale = 'en';
                    localStorage.language = 'en';
                } else {
                    this.$i18n.locale = value;
                    localStorage.language = value;
                }

                this.locale = this.$i18n.locale;
            },
            filtersApply(value){
                this.$emit('clicked', true);
            },
            goOdourList(){
                if(localStorage.getItem('auth-token') != null){
                    this.goOdours();
                }else{
                    this.logout();
                    this.popup = true;
                }
            },
            goCreateOdour(){
                if(localStorage.getItem('auth-token') != null){
                    this.$emit('clicked', 'reset');
                    this.create_odours = true
                }else{
                    this.logout();
                    this.popup = true;
                }
            },
            cancelCreateOdour(){
                this.create_odours = false
            },
            goLogin(){
                this.drawerRight = false;
                this.popup = false;
                this.login = true;
                this.$emit('clicked', 'close_details');
            },
            goRegister(){
                this.popup = false;
                this.register = true;
            },
            //According to the action to be done, the layers are shown or closed
            login_action(value){
                if (value === 'login_close'){
                    this.login = false;
                    this.$emit('clicked', 'login');
                } else if(value === 'login_open') {
                    this.register = false;
                    this.login = true;
                }else if (value === 'register_open'){
                    this.register = true;
                } else if (value === 'recover_open'){
                    this.recover = true;
                } else if(value === 'close_recover'){
                    this.recover = false;
                } else if(value === 'close_all'){
                    this.login = false;
                    this.register = false;
                    this.recover = false;
                } else if(value === 'legal_open'){
                    this.legal = true
                } else if (value === 'ethics_open'){
                    this.ethics = true
                }
            },
            select_odour(value){
                this.user_odours = false;
                this.user_odours_bar = true;
                this.$emit('clicked', value);
            },
            new_odour(value){
                if(value === 'reset'){
                    this.resetAll();
                } else if (value === 'go_login') {
                    this.logout();
                    this.goLogin();
                } else {
                    this.create_odours = false;
                    this.$emit('clicked', value);
                }

            },
            select_zone(value){
                this.user_zones = false;
                this.$emit('clicked', value);
            },
            resetAll(){
                this.user_odours = false;
                this.show_bottom = true;
                this.create_odours = false;
                this.$emit('clicked', 'reset');
            },
            goHome(){
                this.user_odours = false;
                this.show_bottom = true;
                this.create_odours = false;
                this.$emit('clicked', 'goHome');
            },
            goAbout(){
                this.drawerRight = false;
                this.about = true;
                this.$emit('clicked', 'close_details')
            },
            goMethodology(){
                this.drawerRight = false;
                this.methodology = true;
                this.$emit('clicked', 'close_details')
            },
            goLegal(){
                this.drawerRight = false;
                this.legal = true;
                this.$emit('clicked', 'close_details')
            },
            goEthics(){
                this.drawerRight = false;
                this.ethics = true;
                this.$emit('clicked', 'close_details')
            },
            goProfile(){
                this.drawerRight = false;
                this.user_profile = true;
                this.$emit('clicked', 'close_details')
            },
            goPassword(){
                this.drawerRight = false;
                this.user_password = true;
                this.$emit('clicked', 'close_details')
            },
            goOdours(){
                this.drawerRight = false;
                this.user_odours = true;
                this.show_bottom = false;
                this.user_zones = false;
                this.create_odours = false;
                this.$emit('clicked', 'close_details')
            },
            goZones(){
                this.create_odours = false;
                this.drawerRight = false;
                this.user_zones = true;
                this.show_bottom = true;
                this.$emit('clicked', 'close_details')
            },
            goContact(){
                this.drawerRight = false;
                this.contact = true;
                this.$emit('clicked', 'close_details')
            }
        },
        computed:{
            token (){return store.state.token;},
            user(){ return store.state.user;},
            route: {
                get: function(){ return this.$route.fullPath;},
                set: function(){}
            }
        },
        watch: {
            token(newToken, oldToken){if( newToken !== null ) { this.isLoggedIn = true }},
            user(newUser, oldUser){
                if( this.isLoggedIn ){
                    var user = JSON.parse(newUser);
                    this.user_id = user['id'];
                    this.name = user['name'] + ' ' + user['surname'];
                }
            },
            route(newRoute, oldRoute){this.route = this.$route.fullPath;}
        },
        mounted(){
            if( localStorage.getItem('auth-token') !== null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ){
                var user =  JSON.parse(localStorage.getItem('user'));
                this.user_id = user['id'];
                this.name = user['name'] + ' ' + user['surname'];
            }
            this.route = this.$route.fullPath;
            this.show = true;

            this.locale = localStorage.language;
        }
    }
</script>

<style lang="scss">
    .v-list__tile {
        height:40px;
    }
    .v-list__tile--link {
        height:35px;
    }
    .v-btn--active:before, .v-btn:focus:before, .v-btn:hover:before {
        background-color: transparent;
    }
    .v-btn:before {display:none;}
    .v-item-group.v-bottom-nav .v-btn--active .v-btn__content {
        font-size: 12px!important;
    }
</style>
<style scoped lang="scss">
    @import './../../sass/app.scss';

    a, .theme--light.v-icon{
        color: $font-color-light;
    }
    .menu.theme--light.v-icon{
        color: white;
    }
    .v-toolbar__title:not(:first-child){
        margin: 0;
        width: 100%;
    }
    .v-toolbar{
        background-color: white;
        box-shadow: 0 0 10px 0 rgba(0,0,0,.15);
    }
    .v-toolbar *{
        color: $font-color-dark;
    }
    .logo{
        height: 32px;
        margin-top: 0px!important;
    }
    .left-button{
        color: #00b187;
        margin-left: 25px;
        font-size: 15px;
        margin-bottom: 0px;
        font-weight: 600;
    }
    .unlogged-menu{
        margin-top: 25px
    }
    .theme--light.v-navigation-drawer{
        background: $menu-bg;
    }
    .theme--light.v-navigation-drawer .v-divider{
        border-color: $light-gray;
    }
    .title{
        font-size: 16px!important;
        color: white;
        margin-bottom: 2px;
    }
    .title-name{
        margin: 6px 0px 15px 20px;
        color: white;
    }
    .v-list__tile__title .headline{
        font-size:20px !important;
        font-weight: 600;
    }
    .v-list__tile__action {
        min-width: 40px;
    }
    .v-btn__content span{
        color: #365165;
    }
    .bottom-title{
        color:$font-color-light!important;
        font-size: 14px!important;
    }
    .menu{
        margin-left: 20px;
        cursor:pointer;
    }
    .text-md-center{
        width: 100%;
        text-align: center;
        margin: 0;
        line-height: 95%;
        font-size: 20px;
        font-weight: 600;
        margin-top: -30px;
    }
    .back_toolbar img{
        padding: 25px 10px 10px 23px;
    }
    .back_toolbar{
        height: 64px;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
        position: fixed;
        top: 0px;
        width: 100%;
        right: 0;
        z-index: 4;
        background: white;
    }
    .back_toolbar p{
        color: #365165;
    }
    .bottom-options{
        /*position: absolute;*/
        /*bottom: 0;*/
        margin-bottom: 40px;
        margin-left: 20px;
        margin-top: 40px;
    }
    .bottom-nav{
        position: fixed;
        bottom: 0;
        width: 100%;
        right: 0;
        height: 60px;
    }
    .bottom-nav img{
        margin-bottom: 8px;
        margin-top: -5px;
    }
    .v-item-group.v-bottom-nav .v-btn:not(.v-btn--active) {
        -webkit-filter: none;
        filter: none;
    }
    .v-item-group.v-bottom-nav .v-btn .v-btn__content {
        font-size:11px;
    }
    .v-item-group.v-bottom-nav.theme--light.v-bottom-nav--absolute.v-bottom-nav--active.transparent{
        height: 60px!important;
    }
    button{
        opacity: 1!important;
    }
    .add-btn{
        margin-top: -43px!important;
    }
    hr {
        margin-top: 5px!important;
        margin-bottom: 5px!important;
    }
    .content_create{
        position: absolute;
        width: 100%;
        top: 0px;
        background: #f1f1f1;
        height: 100%;
    }
    .content{
        position: absolute;
        width: 100%;
        top: 8px;
        background: #fafafa;
        height: calc(100% - 8px);
    }
    .content-light{
        background: #fafafa!important;
    }
    .content-large{
        background: $secondary-bg!important;
        position: absolute;
        width: 100%;
        top: 64px!important;
        z-index: 1;
    }
    .content-white{
        background: white!important;
        position: absolute;
        width: 100%;
        top: 0px!important;
        z-index: 1;
    }
    .map-btn{
        color: $primary-font-color!important;
        text-transform: uppercase;
        text-align: center;
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
    .apply-btn.map{
        margin-bottom: 20px;
    }
    .info-close{
        position: absolute;
        right: 24px;
        top: 14px;
    }
    .apply{
        color: $primary-font-color!important;
        text-transform: uppercase;
        text-align: center;
        padding-bottom: 10px;
        font-size: 15px!important;
        font-weight: 600;
    }
    .title-popup{
        text-align: center;
        font-size: 14px!important;
        margin-top: 16px;
        font-weight: 600!important;
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

    select{
        color: #b5b7bb !important;
        font-size: 14px !important;
        padding: 8px 5px 8px 15px;
        margin-bottom: 5px;
        width:100%;
    }

    option{
        background-color: #343a40;
    }

</style>!-->

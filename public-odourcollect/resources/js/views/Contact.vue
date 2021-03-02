<template>
    <v-container>
        <h1 class="font-weight-medium display-1">{{$t('MENU.CONTACT')}}</h1>

        <form>
            <div v-if="!user">
                <v-text-field
                    name="login"
                    :label="$t('FORMS.INPUTS.EMAIL')"
                    :rules="emailRules"
                    type="email"
                    v-model="email"
                    required
                ></v-text-field>
            </div>
            <p color="primary" class="pform subheading font-weight-medium">{{$t('CONTACT.QUESTION')}}</p>
            <v-select v-model="option_selected" :items="options" item-value="value" item-text="text" :placeholder="placeholder"></v-select>

            <p color="primary" class="pform subheading font-weight-medium">{{$t('CONTACT.MSG')}}</p>
            <textarea v-model="msg" placeholder="" :rows="4" :max-rows="4"></textarea>

            <!--<label for="date">Fecha de nacimiento</label>-->
            <!--<datepicker id="date" :value="input_datebirth"></datepicker>-->

            <div class="align fix-bottom">
                <img v-if="publishing" :src="load_icon">
                <v-btn  v-if="!publishing" color="secondary" class="body-2 font-weight-regular" @click="sendContact">{{$t('CONTACT.SEND')}}</v-btn>
            </div>
        </form>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="state.status" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title">{{state.title}}
                            <div class="info-close pointer" @click="state.status = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="separator"></div>

                        <v-card-text style="padding-top: 60px;padding-bottom: 100px;">
                            <div class="apply-btn">
                                <div class="apply-btn map">
                                    <p>{{state.msg}}</p>
                                </div>
                            </div>
                        </v-card-text>

                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply pointer" @click="state.status = false">{{$t('UPDATE_PROFILE.AGREE')}}</h2>
                        </div>

                        <div style="flex: 1 1 auto;"></div>
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>
    </v-container>
</template>

<script>

    import { validationMixin } from 'vuelidate';
    import { required, minLength, sameAs, email } from 'vuelidate/lib/validators';
    import Datepicker from 'vuejs-datepicker';

    export default {
        components:{
          Datepicker
        },
        mixins: [validationMixin],

        validations: {
            name: { required },
            msg: { required },
        },

        data(){
            return {
                isLoggedIn : null,
                name : null,
                msg: '',
                user: '',
                email : "",
                id_user: '',
                token: '',
                message:'',
                publishing: false,
                state : {
                    status : false,
                    msg: '',
                    title: ''
                },
                close_icon: '../../../img/general/close-mini.svg',
                load_icon:'../../../img/general/ajax-loader.gif',
                options: [
                    { text: this.$t('CONTACT.CONTACT'), value: 'Contact' },
                    { text: this.$t('CONTACT.INCIDENCE'), value: 'Incidence' },
                ],
                option_selected: '',
                placeholder: this.$t('CONTACT.SELECT'),
                emailRules: [
                    (v) => !!v || this.$t('UPDATE_PROFILE.REQUIRED_EMAIL'),
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || this.$t('UPDATE_PROFILE.FORMAT_EMAIL'),
                ],

            }
        },
        computed: {
            usernameErrors () {
                const errors = []
                if (!this.$v.name.$dirty) return errors
                !this.$v.input_username.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_USERNAME'))
                return errors
            },
        },
        methods: {
            //Send contact to admin and save it on db
            sendContact () {
                var vm = this;

                if (!vm.option_selected || !vm.msg){
                    vm.state.status = true;
                    vm.state.msg = this.$t('CONTACT.REVIEW');
                    vm.state.title = this.$t('UPDATE_PROFILE.KO_TITLE');
                } else {

                    vm.publishing = true;

                    if( !this.isLoggedIn ){
                        vm.id_user = 0;
                    }

                    axios.post('api/contact', {
                        token: vm.token,
                        user: vm.id_user,
                        email: vm.email,
                        subject: vm.option_selected,
                        body: vm.msg
                    }).then(response => {
                        vm.publishing = false;
                        vm.state.status = true;
                        vm.state.title = this.$t('UPDATE_PROFILE.OK_TITLE');
                        if (vm.option_selected === 'Contact'){
                            vm.state.msg = this.$t('CONTACT.OK_CONTACT');
                        } else {
                            vm.state.msg = this.$t('CONTACT.OK_INDICENCE');
                        }
                        vm.option_selected = '';
                        vm.msg = '';
                    }).catch(error => {
                        vm.publishing = false;
                        vm.state.status = true;
                        vm.state.msg = this.$t('UPDATE_PROFILE.KO');
                        vm.state.title = this.$t('UPDATE_PROFILE.KO_TITLE');

                    });

                }
            },
        },
        mounted(){

            var vm = this;

            //Looks if the user is logged in
            if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ){
                //If logged in save the user name in the data
                vm.user = JSON.parse( localStorage.getItem('user') );
                vm.token = localStorage.getItem('auth-token');
                vm.id_user = vm.user.id;
                vm.email = vm.user.email
            }

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
    @import '../../sass/app.scss';

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
        border-radius: 30px;
        margin-top: 10px;
        width: 100%;
        height: 45px;
        font-size: 15px !important;
        font-weight: 600 !important;
        box-shadow: none !important;
    }
    .align{
        text-align: center;
    }
    .separator {
        border-bottom: 1px solid #dadada;
        margin-top: 20px;
    }
    .apply{
        color: $primary-font-color!important;
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
    .apply-btn.map{
        margin-bottom: 20px;
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
    .pointer{
        cursor: pointer;
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
    .info-close img{
        margin-top: 5px;
    }
    p{
        font-size: 13px;
    }
    .title{
        text-align: center;
        font-size: 14px!important;
        margin-top: 16px;
        font-weight: 600!important;
    }
    .info-close{
        position: absolute;
        right: 14px;
        top: 4px;
        padding: 10px;
    }
    .container {
        padding:50px 20px 40px;
        margin-top: -64px;
        height: 100%;
    }
    textarea{
        width: 100%;
        background-color: white;
        border-radius: 20px;
        padding: 10px;
        font-size: 16px;
    }
</style>

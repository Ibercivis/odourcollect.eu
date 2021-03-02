<template>
    <v-container>
        <h1 class="font-weight-medium display-1">{{$t('MENU.USER_PROFILE')}}</h1>

        <form>
            <v-text-field
                    v-model="input_username"
                    :error-messages="usernameErrors"
                    :label="$t('UPDATE_PROFILE.USERNAME')"
                    required
                    @input="$v.input_username.$touch()"
                    @blur="$v.input_username.$touch()"
            ></v-text-field>

            <v-text-field
                    v-model="input_name"
                    :error-messages="nameErrors"
                    :label="$t('UPDATE_PROFILE.NAME')"
                    required
                    @input="$v.input_name.$touch()"
                    @blur="$v.input_name.$touch()"
            ></v-text-field>

            <v-text-field
                    v-model="input_surname"
                    :error-messages="surnameErrors"
                    :label="$t('UPDATE_PROFILE.SURNAME')"
                    required
                    @input="$v.input_surname.$touch()"
                    @blur="$v.input_surname.$touch()"
            >{{$v.input_surname}}</v-text-field>

            <v-text-field
                    v-model="input_email"
                    :append-icon="e2 ? 'visibility' : 'visibility_off'"
                    @click:append="() => (e2 = !e2)"
                     :type="e2 ? 'text' : 'password'"
                    :error-messages="emailErrors"
                    :label="$t('UPDATE_PROFILE.EMAIL')"
                    required
                    @input="$v.input_email.$touch()"
                    @blur="$v.input_email.$touch()"
            ></v-text-field>

            <v-text-field
                    v-model="input_telf"
                    :append-icon="e3 ? 'visibility' : 'visibility_off'"
                    @click:append="() => (e3 = !e3)"
                    :error-messages="telfErrors"
                     :type="e3 ? 'number' : 'password'"
                    :label="$t('UPDATE_PROFILE.PHONE')"
                    @input="$v.input_telf.$touch()"
                    @blur="$v.input_telf.$touch()"
            ></v-text-field>

            <!--<label for="date">Fecha de nacimiento</label>-->
            <!--<datepicker id="date" :value="input_datebirth"></datepicker>-->

            <div class="align fix-bottom">
                <v-btn color="secondary" class="body-2 font-weight-regular" @click="saveProfileChanges">{{$t('UPDATE_PROFILE.SAVE')}}</v-btn>
            </div>
        </form>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="state.status" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title">{{state.title}}
                            <div class="info-close" @click="state.status = false"><img :src="close_icon"></div>
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
                            <h2 class="apply" @click="state.status = false">{{$t('UPDATE_PROFILE.AGREE')}}</h2>
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
            input_username: { required },
            input_name: { required },
            input_surname: { required },
            input_email: { required, email },
            input_telf: { required },
//            input_datebirth: { required },
        },

        data(){
            return {
                isLoggedIn : null,
                name : null,
                input_name: '',
                input_username: '',
                input_surname: '',
                input_email: '',
                input_telf: '',
                e2: false,
                e3: false,
//                input_datebirth: '03/12/1900',
                user: '',
                token: '',
                message:'',
                state : {
                    status : false,
                    msg: '',
                    title: ''
                },
                close_icon: '../../../img/general/close-mini.svg',

            }
        },
        computed: {
            usernameErrors () {
                const errors = []
                if (!this.$v.input_username.$dirty) return errors
                !this.$v.input_username.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_USERNAME'))
                return errors
            },
            nameErrors () {
                const errors = []
                if (!this.$v.input_name.$dirty) return errors
                !this.$v.input_name.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_NAME'))
                return errors
            },
            surnameErrors () {
                const errors = []
                if (!this.$v.input_surname.$dirty) return errors
                !this.$v.input_surname.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_SURNAME'))
                return errors
            },
            emailErrors () {
                const errors = []
                if (!this.$v.input_email.$dirty) return errors
                !this.$v.input_email.email && errors.push(this.$t('UPDATE_PROFILE.FORMAT_EMAIL'))
                !this.$v.input_email.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_EMAIL'))
                return errors
            },
            telfErrors () {
                const errors = []
                if (!this.$v.input_telf.$dirty) return errors
                !this.$v.input_telf.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_PHONE'))
                return errors
            },
        },
        methods: {
            //Save the new profile information
            saveProfileChanges () {
                var vm = this;

                axios.post('../../api/user/update/' + vm.user.id, {
                    token: vm.token,
                    username: vm.input_username,
                    name: vm.input_name,
                    surname: vm.input_surname,
                    email: vm.input_email,
//                    datebirth: vm.input_datebirth,
                    phone: vm.input_telf
                }).then(response => {
                    vm.state.status = true;
                    vm.state.msg = this.$t('UPDATE_PROFILE.OK');
                    vm.state.title = this.$t('UPDATE_PROFILE.OK_TITLE');
                }).catch(error => {
                    vm.state.status = true;
                    vm.state.msg = this.$t('UPDATE_PROFILE.KO');
                    vm.state.title = this.$t('UPDATE_PROFILE.KO_TITLE');

                    //If response code is 401 / 403 / 500 show alert of bad login or login problems
                });
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
            }

            //Get the user information
            axios.post('../../api/user/' + vm.user.id, {
                token: vm.token
            }).then(response => {
                var data = response.data.object;

                vm.input_username = data.username;
                vm.input_name = data.name;
                vm.input_surname = data.surname;
                vm.input_email = data.email;

                vm.input_telf = data.phone;
//                if(data.datebirth){
//                    vm.input_datebirth = data.datebirth;
//                }

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
</style>
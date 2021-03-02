<template>
    <v-container>
        <h1 class="font-weight-medium display-1">{{$t('MENU.USER_PASSWORD')}}</h1>

        <form>
            <v-text-field
                    v-model="input_oldpw"
                    :append-icon="e2 ? 'visibility' : 'visibility_off'"
                    @click:append="() => (e2 = !e2)"
                    :type="e2 ? 'text' : 'password'"
                    :label="$t('FORMS.INPUTS.OLD_PASSWORD')"
                    required
            ></v-text-field>

            <v-text-field
                    v-model="input_newpw"
                    :append-icon="e2 ? 'visibility' : 'visibility_off'"
                    @click:append="() => (e2 = !e2)"
                    :error-messages="newpwErrors"
                    :type="e2 ? 'text' : 'password'"
                    :label="$t('FORMS.INPUTS.NEW_PASSWORD')"
                    required
                    @input="$v.input_newpw.$touch()"
                    @blur="$v.input_newpw.$touch()"
            ></v-text-field>

            <v-text-field
                    v-model="input_repitedpw"
                    :append-icon="e2 ? 'visibility' : 'visibility_off'"
                    @click:append="() => (e2 = !e2)"
                    :error-messages="repitedpwErrors"
                    :type="e2 ? 'text' : 'password'"
                    :label="$t('FORMS.INPUTS.REPEAT_PASSWORD')"
                    required
                    @input="$v.input_repitedpw.$touch()"
                    @blur="$v.input_repitedpw.$touch()"
            ></v-text-field>

            <div class="align fix-bottom"><v-btn color="secondary" class="body-2 font-weight-regular" @click="savePwChanges">{{$t('UPDATE_PROFILE.UPDATE')}}</v-btn></div>

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

    import { required, minLength, sameAs } from 'vuelidate/lib/validators';

    export default {

        validations: {
            input_newpw: { required, minLength: minLength(6) },
            input_repitedpw: { sameAsPassword: sameAs('input_newpw') },
        },

        data(){
            return {
                isLoggedIn : null,
                name : null,
                input_newpw: '',
                input_repitedpw: '',
                input_oldpw: '',
                user: '',
                token: '',
                message:'',
                e2: false,
                state : {
                    status : false,
                    msg: '',
                    title: ''
                },
                close_icon: '../../../img/general/close-mini.svg',
            }
        },
        computed: {
            newpwErrors () {
                const errors = []
                if (!this.$v.input_newpw.$dirty) return errors
                !this.$v.input_newpw.minLength && errors.push( this.$t('UPDATE_PROFILE.CHARACTERS_PW'))
                !this.$v.input_newpw.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_PW'))
                return errors
            },
            repitedpwErrors () {
                const errors = []
                if (!this.$v.input_repitedpw.$dirty) return errors
                !this.$v.input_repitedpw.sameAsPassword && errors.push(this.$t('UPDATE_PROFILE.IDENTICAL_PW'))
                return errors
            },
        },
        methods: {
            //Save the new password
            savePwChanges(){
                var vm = this;

                if(vm.input_newpw == vm.input_repitedpw && vm.input_newpw && vm.input_repitedpw && vm.input_oldpw) {

                    axios.post('../../api/user/password/' + vm.user.id, {
                        token: vm.token,
                        password: vm.input_oldpw,
                        new_password: vm.input_newpw,
                        rep_password: vm.input_repitedpw,

                    }).then(response => {
                        vm.state.status = true;
                        vm.state.msg = this.$t('UPDATE_PROFILE.OK')
                        vm.state.title = this.$t('UPDATE_PROFILE.OK_TITLE');

                    }).catch(error => {
                        vm.state.status = true;
                        vm.state.msg = this.$t('UPDATE_PROFILE.KO')
                        vm.state.title = this.$t('UPDATE_PROFILE.KO_TITLE');
                        //If response code is 401 / 403 / 500 show alert of bad login or login problems
                    });
                } else {
                    vm.state.status = true;
                    vm.state.msg = this.$t('UPDATE_PROFILE.NOT_COMPLETE')
                    vm.state.title = this.$t('UPDATE_PROFILE.KO_TITLE');
                }
            }
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
        right: 24px;
        top: 14px;
        cursor: pointer;
    }
</style>

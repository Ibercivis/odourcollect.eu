<template>
    <v-container fluid fill-height>
        <v-layout justify-center>
            <v-flex xs12 sm8 md4>
                <v-card v-if="register">
                    <v-card-text>

                        <v-form ref="form" style="padding-top: 50px;">
                           <!--  <v-text-field
                                name="username"
                                :label="this.$t('UPDATE_PROFILE.USERNAME')"
                                :rules="usernameRules"
                                type="text"
                                v-model="username"
                                required
                            ></v-text-field> -->


                            <v-text-field 
                                name="login"
                                :label="$t('FORMS.INPUTS.EMAIL')"
                                :rules="emailRules" 
                                type="email"
                                v-model="email" 
                                required
                            ></v-text-field>

                            <select @change="selectAge($event)" class="age-select">
                                <option v-for="age in ages" v-bind:value="age.value">
                                    {{$t('UPDATE_PROFILE.AGE')}} {{ age.text }}
                                </option>
                            </select>
                            
                            <br/>

                            <input type="radio" id="male" value="male" v-model="gender">
                            <label class="label-gender" for="male">{{$t('UPDATE_PROFILE.MALE')}}</label>
                            <input type="radio" id="female" value="female" v-model="gender">
                            <label class="label-gender" for="female">{{$t('UPDATE_PROFILE.FEMALE')}}</label>
                            <input type="radio" id="other" value="other" v-model="gender">
                            <label class="label-gender" for="other">{{$t('UPDATE_PROFILE.OTHER')}}</label>

                            <v-text-field
                                name="password"
                                :label="this.$t('FORMS.INPUTS.PASSWORD')"
                                v-model="password"
                                :append-icon="e2 ? 'visibility' : 'visibility_off'"
                                @click:append="() => (e2 = !e2)"
                                :error-messages="newpwErrors"
                                :type="e2 ? 'text' : 'password'"
                                required
                                @input="$v.password.$touch()"
                                @blur="$v.password.$touch()"
                            ></v-text-field>

                            <v-text-field
                                name="passwordConfirmation"
                                :label="this.$t('FORMS.INPUTS.CONFIRM_PASSWORD')"
                                :append-icon="e2 ? 'visibility' : 'visibility_off'"
                                @click:append="() => (e2 = !e2)"
                                :error-messages="repitedpwErrors"
                                :type="e2 ? 'text' : 'password'"
                                v-model="password_confirmation"
                                @input="$v.password_confirmation.$touch()"
                                @blur="$v.password_confirmation.$touch()"
                                required
                            ></v-text-field>

                            <div class="inline">
                                <v-checkbox color="#384658" :id="term_id" v-model="terms" :data="terms" :value="terms"></v-checkbox>
                                <p @click="goLegal"><label :for="term_id">{{$t('REGISTER.CONDITIONS_P1')}} <span>{{$t('REGISTER.PERSONAL_DATA')}}</span></label></p>
                            </div>

                            <p class="error" v-if="error.status">{{error.msg}}</p>

                            <div class="inline">
                                <v-checkbox color="#384658" :id="term_id_1" v-model="terms1" :data="terms1" :value="terms1"></v-checkbox>
                                <p @click="goEthics"><label :for="term_id_1">{{$t('REGISTER.CONDITIONS_P1')}} <span>{{$t('REGISTER.ETHICAL')}}</span></label></p>
                            </div>

                            <p class="error" v-if="error1.status">{{error1.msg}}</p>

                            <div class="inline">
                                <v-checkbox color="#384658" :id="news_id" v-model="newsletter" :data="newsletter" :value="newsletter"></v-checkbox>
                                <label :for="news_id">{{$t('REGISTER.NEWSLETTER')}}</label>
                            </div>

                        </v-form>

                        <v-card-actions class="fix-bottom">
                            <v-spacer></v-spacer>
                            <div class="align">
                                <div class="align fix-bottom pointer"><v-btn color="secondary" class="body-2 font-weight-regular" @click="checkForm">{{$t('REGISTER.REGISTER')}}</v-btn></div>
                            </div>
                        </v-card-actions>

                        <div class="options-link pointer"><p @click="goLogin">{{$t('REGISTER.GOT_ACCOUNT')}} {{$t('REGISTER.LOG')}}</p></div>

                    </v-card-text>

                </v-card>

                <v-card v-if="!register">
                    <v-card-text class="subheading message font-weight-regular">
                        {{$t('REGISTER.REGISTER_OK')}}
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <div class="align fix-bottom"><v-btn class="body-2 font-weight-regular" color="secondary" @click="loginUser">{{$t('REGISTER.ENTER')}}</v-btn></div>
                        </v-card-actions>

                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import store from '../store/store'
    import { sameAs, required, minLength } from 'vuelidate/lib/validators';

    export default {

        validations: {
            password: { required, minLength: minLength(6) },
            password_confirmation: { sameAsPassword: sameAs('password') },
        },

        data(){
            return {
                username : "",
                email : "",
                password : "",
                ages: [
                    { text: '', value: '0' },
                    { text: '<13', value: '<13' },
                    { text: '13-19', value: '13-19' },                    
                    { text: '20-29', value: '20-29' },
                    { text: '30-39', value: '30-39' },
                    { text: '40-49', value: '40-49' },
                    { text: '50-59', value: '50-59' },
                    { text: '>60', value: '>60' }
                ],
                age_selected: "",
                gender : "",
                password_confirmation : "",
                register : true,
                terms: false,
                terms1: false,
                term_id: "term_id",
                term_id_1: "term_id_1",
                news_id: "news_id",
                newsletter: false,
                errors : {
                    email : false
                },
                emailRules: [
                    (v) => !!v || this.$t('UPDATE_PROFILE.REQUIRED_EMAIL'),
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || this.$t('UPDATE_PROFILE.FORMAT_EMAIL'),
                ],
               
                usernameRules: [
                    (v) => !!v || this.$t('UPDATE_PROFILE.REQUIRED_SURNAME')
                ],
                pwdRules:[
                    (v) => !!v || this.$t('UPDATE_PROFILE.REQUIRED_PW')
                ],
                error : {
                    status : false,
                    msg: ''
                },
                error1 : {
                    status : false,
                    msg: ''
                },
                e2: false,

            }
        },
        computed: {
            newpwErrors () {
                const errors = []
                if (!this.$v.password.$dirty) return errors
                !this.$v.password.minLength && errors.push(this.$t('UPDATE_PROFILE.CHARACTERS_PW'))
                !this.$v.password.required && errors.push(this.$t('UPDATE_PROFILE.REQUIRED_PW'))
                return errors
            },
            repitedpwErrors () {
                const errors = []
                if (!this.$v.password_confirmation.$dirty) return errors
                !this.$v.password_confirmation.sameAsPassword && errors.push(this.$t('UPDATE_PROFILE.IDENTICAL_PW'))
                return errors
            },
        },
        methods : {

            selectAge(event) {
                var age = event.target.value
                console.log(age);
                if (age =="<13"){
                    alert("<13");
                }
                if (age =="13-19"){
                    alert("13-19");
                }

                this.age_selected =  age;
            },

            checkForm(e){
                var vm = this;
                if (!vm.age_selected || !vm.email || !vm.email || !vm.password || !vm.password_confirmation){
                    this.error.status = true;
                    this.error.msg = this.$t('REGISTER.ERROR_DATA');
                } else {
                    this.handleSubmit(e);
                }
            },

            //Check if the passwords are correct and is the terms is checked and save the new user
            handleSubmit(e) {
                e.preventDefault()
                this.error.status = false;
                this.error1.status = false;

                if (this.password === this.password_confirmation && this.$refs.form.validate() && this.terms && this.terms1)
                {
                    this.error.status = false;
                    this.error1.status = false;
                
                    axios.post('../api/register', {
                        //username: this.username,
                        username: "username",
                        age: this.age_selected,
                        gender: this.gender,
                        email: this.email,
                        password: this.password,
                        newsletter: this.newsletter,
                        c_password : this.password_confirmation,
                        lang : navigator.language
                      })
                      .then(response => {
                        this.errors.email = false;
                        this.register = false;
                      })
                      .catch(error => {
                        if(error.response.data.email == 'unique'){
                            this.error1.status = true;
                            this.error1.msg = this.$t('REGISTER.ERROR_EMAIL');
                        }
                      });
                } else {
                    if (!this.terms) {
                        this.error.status = true;
                        this.error.msg = this.$t('REGISTER.ERROR_PERSONAL');
                    }

                    if (!this.terms1) {
                        this.error1.status = true;
                        this.error1.msg = this.$t('REGISTER.ERROR_ETHIC');
                    }

                }
            },
            goLogin(){
                this.$emit('clicked', 'login_open');
            },
            goLegal(){
                this.$emit('clicked', 'legal_open');
            },
            goEthics(){
                this.$emit('clicked', 'ethics_open');
            },
            //Method that handles the login form submit
            loginUser(){
                //Make a POST request to the backend and get a response
                axios.post('api/login', {
                    email: this.email,
                    password: this.password
                  })
                  .then(response => {
                    //If response code 200 - Log In the user and save the information in the Sesion
                      this.$store.commit('token', response.data.token);
                      this.$store.commit('user', JSON.stringify(response.data.object));

                    localStorage.setItem('user', JSON.stringify(response.data.object))
                    localStorage.setItem('auth-token',response.data.token)
                    //If login success redirect the user to the home page
                    if (store.state.token !== null){
                        this.$emit('clicked', 'close_all');
                    }
                  })
                  .catch(error => {
                  });
                    
            }
        },
        mounted() {},
        beforeRouteEnter (to, from, next) {
            //looks if the user is already logged in, if it iis redirects to the home page
            if (localStorage.getItem('auth-token')) {
                return next('/');
            }
            next();
        }
    }
</script>

<style scoped lang="scss">
    @import '../../sass/app.scss';

    *{
        color: $font-color-dark;
    }
    .container {
        padding-left:20px;
        padding-right:20px;
        position: absolute;
        top: 0;
        background: white;
        min-height: 100%;
        height:auto;
    }
    .v-card{
        box-shadow: none!important;
    }
    .v-card__text {
        padding:0;
    }
    .v-card__actions {
        padding:8px 0;
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
        width: 100%;
    }
    .theme--light.v-card{
        background-color: transparent;
    }
    .v-btn[data-v-3563ad7c] {
        width: 100%;
    }
    .options-link{
        text-align: center;
        width: 100%;
        color: $font-color-dark!important;
        font-size: 13px;
        font-weight: 600;
        height: 28px;
        margin-top: 10px;
    }
    .options-link a{
        color: $font-color-dark!important;
        font-size: 13px;
        font-weight: 600;
        height: 28px;
    }
    .extra-options{
        margin-top: 70px;
    }
    .error{
        color: red;
    }
    .error {
        background-color: transparent !important;
        border-color: #f44336 !important;
    }
    label{
        color: rgba(0,0,0,.54);
        font-size: 16px;
        line-height: 1;
        min-height: 8px;
        margin-top: 24px;
    }
    .inline{
        display: inline-flex;
    }
    span{
        text-decoration: underline;
    }
    .container.fill-height>.layout {
        /*height: calc(100% - 64px)!important;*/
        height:auto;
    }
    .pointer{
        cursor: pointer;
    }
    .age-select{
        font-size: 16px;
        color: #9b9b9b;
    }
    .label-gender{
        margin-right: 15px;
    }


</style>

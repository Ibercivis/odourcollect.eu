<template>
    <v-container fluid fill-height>
        <v-layout justify-center>
          <v-flex xs12 sm8 md4>
            <v-alert type="error" outline :value="alert">
              {{$t('LOGIN.LOGIN_ERRORS')}}
            </v-alert>

            <v-card>
              <v-card-text>
                <v-form ref="form">

                  <v-text-field 
                    name="login"
                    :label="$t('FORMS.INPUTS.EMAIL')"
                    :rules="emailRules" 
                    type="text" 
                    v-model="email" 
                    required
                  ></v-text-field>

                  <v-text-field 
                    id="password" 
                    name="password"
                    :label="$t('FORMS.INPUTS.PASSWORD')" 
                    :rules="pwdRules"
                    type="password" 
                    v-model="password" 
                    required
                  ></v-text-field>

                </v-form>
              </v-card-text>
              <v-card-actions class="fix-bottom">
                <v-spacer></v-spacer>
                <div class="align"><v-btn color="secondary" class="body-2 font-weight-regular" @click="handleSubmit">{{$t('REGISTER.ENTER')}}</v-btn></div>
              </v-card-actions>

              <div class="extra-options">
                <div class="align pointer"><p @click="goRecover"> {{$t('LOGIN.FORGOT_PW')}} </p></div>
                <div class="align pointer"><p @click="goRegister"> {{$t('LOGIN.NO_ACCOUNT')}}  {{$t('LOGIN.REGISTER_LINK')}}</p></div>
              </div>
            </v-card>
          </v-flex>
        </v-layout>

    </v-container>
</template>


<script>
    import store from '../store/store'


    export default {
        data(){
            return {
                alert : false,
                email : null,
                password : null,
                emailRules: [
                  (v) => !!v || this.$t('UPDATE_PROFILE.REQUIRED_EMAIL'),
                  (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || this.$t('UPDATE_PROFILE.FORMAT_EMAIL')
                ],
                pwdRules: [
                  (v) => !!v || this.$t('UPDATE_PROFILE.REQUIRED_PW')
                ],
                register: false,
                recover: false,

            }
        },
        methods : {
            //Method that handles the login form submit
            handleSubmit(e){
                e.preventDefault()

                if (this.$refs.form.validate()) {
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
                            this.$emit('clicked', 'login_close')
                        }

                      })
                      .catch(error => {
                        //If response code is 401 / 403 / 500 show alert of bad login or login problems
                        this.alert = true
                          this.$emit('clicked', 'true')

                      });
                }
            },
            goRegister(){
                this.$emit('clicked', 'register_open');
            },
            goRecover(){
                this.$emit('clicked', 'recover_open');
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

  .container {
    padding-left:20px;
    padding-right:20px;
    min-height: 100%;
    height:auto;
  }
  .v-card{
    box-shadow: none;
  }
  .v-card__text {
    padding:0;
  }
  .v-card__actions {
    padding:8px 0;
  }
  *{
    color: $font-color-dark;
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
  .theme--light.v-card{
    background-color: transparent;
  }
  .justify-center {
    /*margin-top: 30%;*/
  }
  a{
      color: $green-button!important;
  }
  .align{
    text-align: center;
    width: 100%;
    color: $font-color-dark!important;
    font-size: 13px;
    font-weight: 600;
    height: 28px;
    margin-bottom: 10px;
  }
  .align a{
    color: $font-color-dark!important;
    font-size: 13px;
    font-weight: 600;
    height: 28px;
  }
  .extra-options{
    margin-top: 70px;
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
  .back_toolbar img{
    padding: 19px 0 0 23px;
  }
  .text-md-center{
    width: 100%;
    text-align: center;
    margin: 0;
    line-height: 95%;
    font-size: 20px;
    font-weight: 600;
    margin-top: -21px;
  }
  .content-white{
    background: white!important;
    position: absolute;
    width: 100%;
    top: 0px!important;
    z-index: 1;
  }
  .container.fill-height>.layout {
      /*height: calc(100% - 64px)!important;*/
      height:auto;
  }
  .pointer{
    cursor: pointer;
  }

</style>

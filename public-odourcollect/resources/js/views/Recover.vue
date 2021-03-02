
<template>
    <v-container fluid fill-height>
        <v-layout justify-center>
          <v-flex xs12 sm8 md4>
            <v-alert type="error" outline :value="alert">
              {{$t('LOGIN.LOGIN_ERRORS')}}
            </v-alert>

            <p v-if="recover">{{$t('RECOVER.MSG')}} <br>{{$t('RECOVER.THANKS')}}</p>

            <v-card>
              <v-card-text>
                <v-form ref="form" v-if="!recover">

                  <v-text-field 
                    name="login"
                    :label="$t('FORMS.INPUTS.EMAIL')"
                    :rules="emailRules" 
                    type="text" 
                    v-model="email" 
                    required
                  ></v-text-field>

                  <p class="error" v-if="error.status">{{error.msg}}</p>

                </v-form>
              </v-card-text>
              <v-card-actions class="fix-bottom">
                <v-spacer></v-spacer>
                <div v-if="!recover" class="align pointer"><v-btn color="secondary" class="body-2 font-weight-regular" @click="handleSubmit">{{$t('RECOVER.RECOVER')}}</v-btn></div>
                <div v-if="recover" class="align pointer"><v-btn color="secondary" class="body-2 font-weight-regular" @click="goHome">{{$t('RECOVER.AGREE')}}</v-btn></div>
              </v-card-actions>

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
                recover: false,
                error : {
                    status : false,
                    msg: ''
                },
            }
        },
        methods : {
            //Method that handles the recover form submit
            handleSubmit(e){
                e.preventDefault()

                if (this.$refs.form.validate()) {
                    axios.post('../api/recover', {
                        email: this.email,
                    })
                    .then(response => {
                        this.recover = true;
//                        this.$store.commit('goPage', this.$t('RECOVER.RECOVER'))
                    })
                    .catch(error => {
                        this.error.status = true;
                        this.error.msg = this.$t('RECOVER.ERROR');
                    });

                }
            },

            goHome(e){
                this.$emit('clicked', 'close_recover');
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
    margin-top: 30%;
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
  p{
    font-size: 16px;
    text-align: center;
    margin: 0px 33px;
  }
  .error{
    color: red;
  }
  .error {
    background-color: transparent !important;
    border-color: #f44336 !important;
  }
  .container.fill-height>.layout {
    height: calc(100% - 64px)!important;
  }
  .pointer{
    cursor: pointer;
  }
</style>

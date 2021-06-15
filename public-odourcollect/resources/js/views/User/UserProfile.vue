<template>
    <v-container>
        <h1 class="font-weight-medium display-1">{{$t('MENU.USER_PROFILE')}}</h1>
        <p class="user_id">Id: {{user.id}}</p>
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
       <v-checkbox
      v-model="input_newsletter"
      @input="$v.input_newsletter.$touch()"
      @blur="$v.input_newsletter.$touch()"
      :label="$t('UPDATE_PROFILE.NEWSLETTER')"
    ></v-checkbox>


            <div class="align fix-bottom">
                <v-btn color="secondary" class="body-2 font-weight-regular" @click="saveProfileChanges">{{$t('UPDATE_PROFILE.SAVE')}}</v-btn>
            </div>
     	    <div class="align fix-bottom">
                <v-btn color="secondary" class="body-2 font-weight-regular" @click="downloadProfile">{{$t('UPDATE_PROFILE.DOWNLOAD_PROFILE')}}</v-btn>
            </div>
            <div class="align fix-bottom">
                <v-btn color="secondary" class="body-2 font-weight-regular" @click="downloadContributions">{{$t('UPDATE_PROFILE.DOWNLOAD_CONTRIBUTIONS')}}</v-btn>
            </div>
            <div class="align fix-bottom">
                <v-btn color="red" class="body-2 font-weight-regular" @click="deleteProfileDialog">{{$t('UPDATE_PROFILE.DELETE')}}</v-btn>
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

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="delete_dialog.status" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title">{{delete_dialog.title}}
                            <div class="info-close" @click="delete_dialog.status = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="separator"></div>

                        <v-card-text style="padding-top: 60px;padding-bottom: 100px;">
                            <div class="apply-btn">
                                <div class="apply-btn map">
                                    <p>MENSAJE {{delete_dialog.msg}}</p>
                                </div>
                                    <div class="inline">                                       
                                        <v-checkbox 
						v-model="input_deleteData" 
						@input="$v.input_deleteData.$touch()"
						@blur= "$v.input_deleteData.$touch()"
      						:label="$t('UPDATE_PROFILE.DELETE_ALLDATA')"
					></v-checkbox>
                                    </div>
                            </div>
                            
                        </v-card-text>

                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="deleteProfile">{{$t('UPDATE_PROFILE.AGREE')}}</h2>
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
            input_email: { required, email },
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
		input_newsletter: '',
                e2: false,
                e3: false,
                user: '',
                token: '',
                message:'',
                state : {
                    status : false,
                    msg: '',
                    title: ''
                },
                delete_dialog : {
                    status : false,
                    msg: '',
                    title: ''
                },
                input_deleteData: false,
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
                    name: "not_used",
                    surname: "not_used",
                    email: vm.input_email,
		    newsletter: vm.input_newsletter
//                  datebirth: vm.input_datebirth,
                    //phone: vm.input_telf
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
            deleteProfileDialog () {
                var vm = this;
                vm.delete_dialog.status = true;
                vm.delete_dialog.msg = "se va a borrar el perfil" //this.$t('UPDATE_PROFILE.OK');
                vm.delete_dialog.title = this.$t('UPDATE_PROFILE.OK_TITLE');
            },
            deleteProfile(){
                var vm = this;
		var deleteData = vm.input_deleteData;
		console.log(deleteData);
		console.log("----");
                axios.post('../api/user/' + vm.user.id + '/deleteAccount',  {
                    token: vm.token,
		    dat: vm.input_deleteData,
                }).then(response => {
                    console.log(response.data.object);
                }).catch(error => {
                    console.log(error);
                });
    		vm.delete_dialog.status = false;

                
            },
	   		downloadProfile(){
		   		console.log('downloadProfile');
				var vm = this;
				if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            	if( this.isLoggedIn ){
                	//If logged in save the user name in the data
                	vm.user = JSON.parse( localStorage.getItem('user') );
                	vm.token = localStorage.getItem('auth-token');
            	}
				console.log(this)
    			//Get the user information
            	axios.post('../../api/user/' + vm.user.id, {
                	token: vm.token
            	}).then(response => {
                	var data = response.data.object;
					var str='';
					var line= '';
					for (var index in data){
						line += index;
						line += ','
					}
					str += line + '\r\n';
					line='';
					 for (var index in data){
                        line += data[index];
                        line += ','
                    }
					str += line + '\r\n';

					var exportedFilename = 'personal_data_odourcollect.csv';
    				var blob = new Blob([str], { type: 'text/csv;charset=utf-8;' });
    				if (navigator.msSaveBlob) { // IE 10+
        				navigator.msSaveBlob(blob, exportedFilenmae);
    				} else {
        				var link = document.createElement("a");
        				if (link.download !== undefined) { // feature detection
            				// Browsers that support HTML5 download attribute
            				var url = URL.createObjectURL(blob);
            				link.setAttribute("href", url);
            				link.setAttribute("download", exportedFilename);
            				link.style.visibility = 'hidden';
            				document.body.appendChild(link);
           				 link.click();
            				document.body.removeChild(link);
        				}
    				}		
            	}).catch(error => {
						console.log(error);
            	});

	   		},
	   		downloadContributions(){
				var vm=this;
   				if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
        		if( this.isLoggedIn ){
            		//If logged in save the user name in the data
            		var user = JSON.parse( localStorage.getItem('user') );
            		this.name = user.name + ' ' + user.surname;
            		this.user_id = user.id;
            		vm.token = localStorage.getItem('auth-token');
        		}

        		var a = localStorage.language;

        		//Get user odour list
        		axios.post('../api/user/' + user.id + '/odours', {
            			token: vm.token
           		 }).then(response => {
					var str='';
                	var points = response.data.object;
					line= ''
					for (var index in points[0]){
						if (index == 'location')
							line +='latitude,longitude,Other'
					else{
						line += index;
						line +=',';
					}
				}
				str += line + '\r\n';
				for (var i = 0; i < points.length; i++) {
        			var line = '';
        			for (var index in points[i]) {
            				if (line != '') line += ','
					if(index=='location')
						for(var subindex in points[i][index])
							if (subindex=='longitude' || subindex=='latitude'){
								line += points[i][index][subindex]
								line += ','
							}
            				line += points[i][index];

        			}
        			str += line + '\r\n';
    			}
				var exportedFilename = 'export.csv';
				console.log('here');
    			var blob = new Blob([str], { type: 'text/csv;charset=utf-8;' });
    			if (navigator.msSaveBlob) { // IE 10+
        			navigator.msSaveBlob(blob, exportedFilenmae);
    			} else {
        			var link = document.createElement("a");
        			if (link.download !== undefined) { // feature detection
            				// Browsers that support HTML5 download attribute
            				var url = URL.createObjectURL(blob);
            				link.setAttribute("href", url);
            				link.setAttribute("download", exportedFilename);
            				link.style.visibility = 'hidden';
            				document.body.appendChild(link);
           				 link.click();
            				document.body.removeChild(link);
        			}
    			}		

            	}).catch(error => {
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
		var data2 = response.data.newsletter;
		vm.input_newsletter = data2;
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
    .user_id{
        margin: 10px 0 20px;
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
</style>

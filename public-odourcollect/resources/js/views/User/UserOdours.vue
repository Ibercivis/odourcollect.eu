<template>
    <div id="scroll">
        <ul>
            <div  v-show="cargador" class="loading-container align">
                <div class="loading-icon"><img :src="loading_icon"></div>
            </div>

            <div v-show="!cargador" class="loading-container align" v-if="oddours.length == 0">
                <div class="loading-icon">
                    <img :src="noodour_icon">
                    <p>{{$t('MENU.NO_ODOURS')}}</p>
                </div>
            </div>

            <div v-show="!cargador" v-else>
                    <div width="50%" class="align fix-bottom">
                        <v-btn  class="body-2 font-weight-regular" color="#00b187" style="font"  @click="downloadcsv"><p class="font-white">Download CSV</p></v-btn>
                    </div>
                </form>
                <li v-for="oddour in oddours">
                    <a :id="'oddour-' + oddour.id">
                        <div @click="show_marker(oddour.id)">
                            <div>
                                <p class="font-weight-medium date">{{oddour.created_at}}</p>
                            </div>
                            <img class="arrow flip" :src="arrow_icon">
                        </div>
                    </a>
                    <v-card-actions class="icon-container" style="width: 100%">
                        <v-btn color="secondary" class="large-button body-2 font-weight-regular btn-delete"  @click="deleteOdour = true; id_odor_tempo = oddour.id">{{$t('INFORMATION.MAP.DELETE')}}</v-btn>
                    </v-card-actions>
                    <v-divider></v-divider>
                </li>
            </div>
        </ul>
        
        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="deleteOdour" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title-box">{{$t('UI.INFO.ODOUR_DELETE.TITLE')}}
                            <div class="info-close" @click="deleteOdour = false"><img :src="close_icon"></div>
                            <p>{{id_odor_tempo }}</p>
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
    </div>

</template>

<script>

export default {

    data(){
        return {
            isLoggedIn : null,
            name : null,
            oddours: [],
            token: '',
            user_id: 0,
            arrow_icon: '../../../img/general/nav-back.svg',
            loc_icon: '../../../img/general/info-spot.svg',
            loading_icon: '../../../img/general/loading.svg',
            noodour_icon: '../../../img/general/no-odour.png',
            back_icon:  '../../../img/general/nav-back.svg',
            close_icon: '../../../img/general/close-mini.svg',
            cargador: true,
            deleteOdour: false,
            id_odor_tempo: ""
        }
    },
    methods:{
      //Return the selected odour to be shown
      show_marker(oddour){
            this.$emit('clicked', ['odour', oddour])
        },


        odourDelete(){
                var vm = this;
                vm.deleteOdour = false;
                axios.post('../../api/odor/' + vm. id_odor_tempo + '/delete', {
                    token: vm.token,
                }).then(response => {
                    $(".back_toolbar .pointer").click();                    
                }).catch(error => {
                    this.alert = true
                });
         },
	downloadcsv(){
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
        var element = document.getElementById("scroll");
        var top = element.offsetTop;
        window.scrollTo(0, top - 20);

        //Looks if the user is logged in
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
                var points = response.data.object;

                //Cargar la informacion
                points.forEach(function (point){
                    var options = {
                        year: "numeric",
                        month: "long",
                        day: "numeric"
                    };

                    var now = new Date(point.created_at.replace(/ /g,"T"));
                    var nowUtc = new Date( now.getTime() + (-(now.getTimezoneOffset()) * 60000));

                    point.created_at = nowUtc.toLocaleDateString(a,options);
                    vm.oddours.push(point);
                });

                vm.cargador = false;

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
        border-radius: 20px;
        margin-top: 10px;
        margin-right: 20px;
        font-size:15px !important;
        font-weight:600 !important;
        box-shadow: none !important;
    }
    .align{
        text-align: center;
    }
    li{
        list-style: none;
    }
    p{
        margin-bottom: 0;
    }
    .arrow{
        float: right;
        margin-top: -28px;
        margin-right: 5px;
    }
    .flip{
        transform: scaleX(-1);
    }
    img{
        margin-right: 10px;
    }
    .date{
        font-size:  16px!important;
        font-weight:600 !important;
    }
    .place{
        font-size: 12px!important;
    }
    .msg{
        margin-top:20px;
        font-size: 16px;
    }
    .loading-container {
        height:85vh;
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
    .font-white {
        color: white;
    }
    .title-box{
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
        cursor: pointer;
    }
    .separator {
        border-bottom: 1px solid #dadada;
        margin-top: 5px;
    }
    .center{
        text-align: center;
    }
    .delete-msg{
        margin-bottom: 20px;
    }
    .apply-btn{
        text-align: center;
        bottom: -20px;
        width: 100%;
        left: 0;
        background-color: white;
    }
    .pointer{
        cursor: pointer;
    }
    .apply{
        color: $primary-font-color;
        text-transform: uppercase;
        text-align: center;
        padding-bottom: 10px;
        font-size: 15px!important;
        font-weight: 600;
    }
    .delete-btn{
        float: right;
        width: 50%;
    }
</style>

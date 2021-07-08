<template>
    <v-app>
        <v-content>
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>
import i18n from '../i18n/i18n';


export default {
    components: {

    },
    data(){
        return {
            isLoggedIn : null,
            name : null,
            reload: 0,
            language: '',
            navigator: false
        }
    },
    computed: {
        //Takes from the store if we are in the home page or not, in order to change the toolbar
        isHome: function () {
            return this.$store.state.isHome;
        }
    },
    watch:{
        language(lng) {
            localStorage.language = lng;
        }
    },
    methods : {

    },
    mounted(){
        //Looks if the user is logged in
        if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
        this.navigator = false;
        if (localStorage.language){
            this.language = localStorage.language;
        } else{
            localStorage.language = navigator.language.split(/-|_/)[0];
            this.navigator = true;
        }

        if( this.isLoggedIn ){
            //If logged in save the user name in the data
            var user = JSON.parse( localStorage.getItem('user') )
            this.name = user.username
        }

        //Get the language from the browser
        if (!this.$i18n.messages[localStorage.language]){
            this.$i18n.locale = 'en';
            localStorage.language = 'en';
        } else {
            this.$i18n.locale = localStorage.language;
            this.locale = this.$i18n.locale;            
        }
        this.language = localStorage.language;

        if(this.navigator){
            location. reload();
        }
        
    }
}
</script>

<style> </style>

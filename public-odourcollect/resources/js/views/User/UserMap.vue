<template>
    <div>
        <oddour-map :singleMarker="singleMarker"></oddour-map>
    </div>
</template>

<script>
    import OddourMap from '../../components/OddourMap.vue';

    export default {
        components: {OddourMap},
        data(){
            return {
                isLoggedIn : null,
                name : null,
                singleMarker: true,
            }
        },
        computed:{},

        mounted(){

            if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ){
                //If logged in save the user name in the data
                var user = JSON.parse( localStorage.getItem('user') )
                this.name = user.name + ' ' + user.surname
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

<style scoped>
</style>
<template>
    <div>
        <h1>User Notifications</h1>
    </div>
</template>

<script>
export default {
    data(){
        return {
            isLoggedIn : null,
            name : null
        }
    },
    mounted(){
        //Looks if the user is logged in
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
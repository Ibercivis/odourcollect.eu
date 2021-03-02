import Vue from 'vue';
import Vuex from 'vuex';

// Modules...
//import UI from './modules/ui/_index';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    isHome: true,
    pageName: null,
    token: null,
    user: null,
    isLoggedIn: false,
    lng: null,
    lat: null,
    language: null
  },

  getters: {
	isLogged(state){
		return state.isLoggedIn
	},
    lng(state){
		return state.lng
	},
	lat(state){
  		return state.lat
	},
    language(state){
	    return state.language
    }
  },

  setters: {
      language(state, data){
          state.language = data;
      }
  },

  mutations: {
  	goHome (state) {
  		state.isHome = true
  	},

  	goPage (state, title) {
  		state.isHome = false
  		state.pageName = title
  	},

  	token(state, data){
  		state.token = data
	},

	user(state, data){
  		state.user = data
	},

	isLoggedIn(state){
		state.isLoggedIn = true
	},
    lng(state, data){
	  	state.lng = data;
    },
    lat(state, data){
	  	state.lat = data;
    },
    language(state, data){
  	    state.language = data;
    }
  }
});

export default store;

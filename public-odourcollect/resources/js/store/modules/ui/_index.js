import getters from './getters';
import mutations from './mutations';
import actions from './actions';

const state = {
  UI: {
    TOOLBAR: {
      isHome: true,
    },
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

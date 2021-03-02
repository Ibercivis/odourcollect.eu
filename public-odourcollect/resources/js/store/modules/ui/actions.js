const setToolbarHome = ({ commit }, value) => {
  const pSetToolbarHome = new Promise((resolve, reject) => {
    commit('SET_TOOLBAR_HOME', value);
    resolve('Ok');
    reject('Error');
  });
  return pSetToolbarHome;
};

export default {
  pSetToolbarHome,
};

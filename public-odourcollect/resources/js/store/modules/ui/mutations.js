/* eslint no-param-reassign: ["error", { "props": false }] */

const SET_TOOLBAR_HOME = (state, value) => {
  state.UI.TOOLBAR.isHome = value;
};

export default {
  SET_TOOLBAR_HOME,
};

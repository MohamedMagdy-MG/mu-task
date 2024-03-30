import { createStore } from "vuex";

export default createStore({
  state: {
    isLoggedIn: localStorage.getItem('isLoggedIn') || false,
    user: JSON.parse(localStorage.getItem('user')) || null,
    language: localStorage.getItem('language') || "en"
  },
  getters: {
    user: (state) => state.user,
    isLoggedIn: (state) => state.isLoggedIn,
    lang: (state) => state.language
  },
  mutations: {
    SET_IS_LOGGED_ID(state, payload) {
      state.isLoggedIn = payload;
      localStorage.setItem('isLoggedIn', payload);
    },
    SET_USER(state, payload) {
      state.user = JSON.parse(payload);
      localStorage.setItem('user', payload);
    },
    SET_LANGUAGE(state) {
      state.language = state.language === 'en' ? 'ar' : 'en';
      localStorage.setItem('language', state.language);

    }


  },
  actions: {
    resetUser({ commit }) {
      commit("SET_USER", null);
      commit("SET_IS_LOGGED_ID", false);
    },
    loginUser({ commit }) {
      commit("SET_USER", localStorage.getItem('user'));
      commit("SET_IS_LOGGED_ID", localStorage.getItem('isLoggedIn'));
    },

  },
  modules: {},
});


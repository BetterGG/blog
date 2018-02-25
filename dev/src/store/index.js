import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

let store = new Vuex.Store({
  state: {
    drafts_count: 0
  },
  mutations: {
    getDraftsCount(state,count){
      state.drafts_count = count;
    }
  }
});
export default store;

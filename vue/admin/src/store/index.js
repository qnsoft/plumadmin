import Vue from 'vue'
import Vuex from 'vuex'
import getters from './getters'
import permission from './modules/permission'
import user from './modules/user'
import tabs from './modules/tabs'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    permission,
    user,
    tabs
  },
  getters
})

export default store

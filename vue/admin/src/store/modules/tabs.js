import Router from '@/router/index'
import { constantRoutes } from '@/router'
import { treeToArray } from '@/utils'
import * as tools from 'lodash'

// 判断路径是否在tabs里
function inPathArray(path, tabs) {
  return pathFindIndex(path, tabs) !== -1
}

// 获取当前路由的index
function pathFindIndex(path, tabs) {
  return tabs.findIndex(item => {
    return item.path === path
  })
}

// 获取tab,根据path
function getTabByPath(path, tabs) {
  return tabs[pathFindIndex(path, tabs)]
}

export default {
  namespaced: true,
  state: {
    tabs: [],
    activeTabs: []
  },
  mutations: {
    SET_TABS(state, tabs) {
      state.tabs = tabs
    },
    APPEND_ACTIVE_TABS(state, tabs) {
      state.activeTabs.push(tabs)
    },
    DELETE_ACTIVE_TABS(state, path) {
      state.activeTabs.splice(pathFindIndex(path, state.activeTabs), 1)
    },
    DELETE_OTHER_ACTIVE_TABS(state, path) {
      let index = pathFindIndex(path, state.activeTabs)
      state.activeTabs = state.activeTabs.slice(index, index + 1)
    },
    DELETE_RIGHT_ACTIVE_TABS(state, path) {
      let index = pathFindIndex(path, state.activeTabs)
      state.activeTabs = state.activeTabs.slice(0, index + 1)
    }
  },
  actions: {
    //初始化
    init({ commit }) {
      //不需要tabs的路由
      let notRoutes = ['']
      treeToArray(constantRoutes).forEach(item => {
        notRoutes.push(item.path)
      })
      let routes = Router.getRoutes()
      //获取所有的tabs,且简化
      routes = routes.filter(item => {
        return notRoutes.indexOf(item.path) === -1
      })
      routes = routes.map(item => {
        return {
          title: item.meta.title ?? '',
          path: item.path,
          name:item.name??''
        }
      })
      commit('SET_TABS', routes)
    },
    add({ state, commit }, path) {
      //在tabs里,不在activeTabs,添加tabs
      if (inPathArray(path, state.tabs) && !inPathArray(path, state.activeTabs)) {
        commit('APPEND_ACTIVE_TABS', getTabByPath(path, state.tabs))
      }
    },
    closeCurrent({ commit, state }, path) {
      //如果当前激活只有一个或者没有在激活列表里则不操作
      if (state.activeTabs.length > 1 && inPathArray(path, state.activeTabs)) {
        //删除当前的
        commit('DELETE_ACTIVE_TABS', path)
        //然后跳转到最后一位路由上
        Router.push(state.activeTabs.slice(-1)[0].path)
      }
    },
    //关闭其他
    closeOther({ commit, state }, path) {
      //如果当前激活只有一个或者没有在激活列表里则不操作
      if (state.activeTabs.length > 1 && inPathArray(path, state.activeTabs)) {
        //删除当前的
        commit('DELETE_OTHER_ACTIVE_TABS', path)
        //然后跳转到最后一位路由上
        Router.push(state.activeTabs.slice(-1)[0].path)
      }
    },
    //关闭右侧
    closeRight({ commit, state }, path) {
      //如果当前激活只有一个或者没有在激活列表里则不操作
      if (inPathArray(path, state.activeTabs) && pathFindIndex(path, state.activeTabs) + 1 < state.activeTabs.length) {
        //删除当前的
        commit('DELETE_RIGHT_ACTIVE_TABS', path)
        //然后跳转到最后一位路由上
        Router.push(state.activeTabs.slice(-1)[0].path)
      }
    }
  }

}

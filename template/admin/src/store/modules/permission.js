import Layout from '@/layout'
import store from '@/store'

/**
 * 是否有权限
 * @param permission
 * @returns {boolean}
 */
export function hasPermission(permission) {
    const allPermission = store.getters.permission
    return allPermission.some(e => permission.toLowerCase() === e.toLowerCase())
}

/**
 * 整理路由
 * @param routes
 * @returns {*}
 */
function combinationRoutes(routes) {
    return routes.map(val => {
        let item = {
            path: val.routes,
            component: resolve => require([`@/views/${val.component.replaceAll(/(^\/)|(\/$)/g, '')}`], resolve),
            name: val.name,
            meta: {
                title: val.title,
                icon: val.icon,
                keep_alive: val.keep_alive,
                hidden: val.menu_hidden
            }
        }
        if (val.children && val.children.length > 0) {
            item.children = combinationRoutes(val.children)
        }
        return item
    })
}

//整理菜单
function combinationMenus(menus) {
    return menus.map(val => {
        let item = {
            id: val.id,
            title: val.title,
            icon: val.icon,
            path: val.routes,
            hidden: val.menu_hidden,
            url: (val.type === 1 && val.url.length > 0) ? val.url : false
        }
        if (val.children && val.children.length > 0) {
            item.children = combinationMenus(val.children)
        }
        return item
    })
}

//获取路由是否缓存
function getKeepAliveName(routes) {
    let keepAliveName = []
    routes.forEach(item => {
        if (item.keep_alive) {
            keepAliveName.push(item.name)
        }
        if (item.children && item.children.length > 0) {
            keepAliveName.push(...getKeepAliveName(item.children))
        }
    })
    return keepAliveName
}

const state = {
    menus: [],
    keep_alive_name: [],
    permission: []
}

const mutations = {
    SET_MENUS: (state, menus) => {
        state.menus = menus
    },
    SET_KEEP_ALIVE_NAME: (state, name) => {
        state.keep_alive_name = name
    },
    SET_PERMISSIONS: (state, permission) => {
        state.permission = permission
    }
}

const actions = {
    generateRoutes({commit, dispatch}, user) {
        return new Promise(resolve => {
            let routes, menus, keepAliveName
            routes = combinationRoutes(user.routes)
            menus = combinationMenus(user.menus)
            keepAliveName = getKeepAliveName(user.routes)
            //插入到模板
            routes = [{
                path: '/admin/',
                component: Layout,
                redirect: user.routes[0].routes,
                children: routes
            }, {
                path: '*',
                hidden: false,
                redirect: '/404'
            }]
            commit('SET_MENUS', menus)
            commit('SET_KEEP_ALIVE_NAME', keepAliveName)
            commit('SET_PERMISSIONS', user.permission)
            resolve(routes)
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}

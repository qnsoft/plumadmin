import request from '@/utils/request'
import router, {resetRouter} from '@/router'
import {clearAuth} from "@/utils/auth";

const getDefaultState = () => {
    return {
        nickname: '',
        user_id: 0,
        avatar: '',
        user: {}
    }
}

const state = getDefaultState()

const mutations = {
    RESET_STATE: (state) => {
        Object.assign(state, getDefaultState())
    },
    SET_USER: (state, user) => {
        state.user = user
        state.nickname = user.nickname
        state.user_id = user.id
        state.avatar = user.avatar
    }
}

const actions = {
    // get user info
    getInfo({commit}) {
        return new Promise((resolve, reject) => {
            request.post('user.info/detail')
                .then(({data}) => {
                    commit('SET_USER', data)
                    resolve(data)
                }).catch(error => {
                reject(error)
            })
        })
    },

    // user logout
    logout({dispatch}) {
        return new Promise((resolve, reject) => {
            request.post('user.info/logout')
                .then(data => {
                    dispatch('resetToken')
                    router.push('/login')
                    resolve(data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },

    // remove token
    resetToken({commit}) {
        return new Promise(resolve => {
            clearAuth()
            commit('RESET_STATE')
            resolve()
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}


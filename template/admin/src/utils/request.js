import axios from 'axios'
import {Message, MessageBox} from 'element-ui'
import {clearAuth, getRefreshToken, getToken, isRefreshTokenExpire, isTokenExpire, setAuth} from '@/utils/auth'
import {requestUrl} from '@/config'


let refresh = false
let requestList = []

// create an axios instance
const service = axios.create({
    baseURL: requestUrl,
    timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
    config => {
        //如果token存在,带上token请求
        if (getToken()) {
            //携带token
            config.headers['Authorization'] = 'Bearer ' + getToken()
            //如果是请求刷新token不需要拦截直接返回
            if (config.url.toLowerCase().includes('login/refresh')) {
                return config
            }
            //token临近过期(50秒),且refresh没有过期,则刷新token
            if (!isTokenExpire() && isRefreshTokenExpire()) {
                if (!refresh) {
                    refresh = true
                    //请求刷新
                    service.post('login/refresh', {refresh_token: getRefreshToken()})
                        .then(({data}) => {
                            setAuth(data)
                            requestList.forEach((cb) => cb(data.token))
                            refresh = false
                            requestList = []
                        })
                }
                //等待token请求
                return new Promise(resolve => {
                    requestList.push(token => {
                        config.headers['Authorization'] = 'Bearer ' + token
                        resolve(config)
                    })
                })
            }
        }
        return config
    },
    error => {
        return new Promise(() => {
        })
    }
)

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        const data = response.data
        if (data.code === 10000) {
            return data
        } else if (data.code === 10401) {
            //清除缓存
            clearAuth()
            //登录
            location.href = '/login'
            return new Promise(() => {
            })
        } else {
            // 如果没有显式定义custom的toast参数为false的话，默认对报错进行toast弹出提示
            if (response.config.toast !== false) {
                Message.error(data.message)
            }
            return Promise.reject(data)
        }
    },
    error => {
        MessageBox.alert('服务器错误,请稍后访问!', {type: 'error', title: '提示'})
        return new Promise(() => {
        })
    }
)

export default service

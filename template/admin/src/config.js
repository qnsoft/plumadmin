//生产环境下请求
const PRO_REQUEST_URL = '/backend'
//开发环境下请求
const DEV_REQUEST_URL = 'http://plumadmin.test/backend'


module.exports = {
    requestUrl: process.env.NODE_ENV === 'development' ? DEV_REQUEST_URL : PRO_REQUEST_URL,
    title: 'PLUM ADMIN',
    logo: require('@/assets/logo.png'),
    sliderLogo: require('@/assets/logo.png'),
    sliderTitle: 'PLUM ADMIN'
}

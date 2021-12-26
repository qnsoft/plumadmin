import Vue from 'vue'
import 'normalize.css/normalize.css' // A modern alternative to CSS resets
import '@/styles/index.scss' // global css
import DialogSubmit from '@/components/DialogSubmit/index'
import Search from '@/components/Search/index'
import PlTable from '@/components/PTable/index'
import PlUploadLibrary from '@/components/Upload/UploadLibrary'
import * as tools from "lodash";
import request from "@/utils/request";
import {hasPermission} from "@/store/modules/permission";

Vue.component('DialogSubmit', DialogSubmit)
Vue.component('Search', Search)
Vue.component('PlTable', PlTable)
Vue.component('PlUploadLibrary', PlUploadLibrary)

Vue.prototype.$tools = tools
Vue.prototype.$http = request


//directive
Vue.directive('permission', {
    inserted(el, binding) {
        let permission = binding.value
        if (!hasPermission(permission)) {
            el.parentNode && el.parentNode.removeChild(el)
        }
    }
})

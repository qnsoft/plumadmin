<?php
// 应用公共文件


/**
 * 获取当前模块登录验证的用户ID
 * @author Plum
 * @email liujunyi_coder@163.com
 * @time 2021年11月29日 14:17
 */
function get_user_id()
{
    return \plum\lib\Token::auth(app('http')->getName(), false);
}

/**
 * 抛出异常
 * @author Plum
 * @email liujunyi_coder@163.com
 * @time 2021年11月29日 16:55
 */
function error($message='')
{
    throw new \plum\core\exception\FailException($message);
}

/**
 * 图像item转化成id
 * @author Plum
 * @email liujunyi_coder@163.com
 * @time 2021/12/27
 */
function set_image(&$data){
    \plum\helper\Helper::setImage($data);
}

/**
 * id转换成图像item
 * @author Plum
 * @email liujunyi_coder@163.com
 * @time 2021/12/27
 */
function get_image(&$data,$fields){
    \plum\helper\Helper::getImage($data,$fields);
}
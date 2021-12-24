<?php

namespace plum\core\traits;

use plum\core\enum\ResponseEnum;

trait ResponseTrait
{
    /**
     * 响应数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年09月30日 19:34
     */
    public function renderSuccess($data = [], $message = 'SUCCESS')
    {
        $code = ResponseEnum::SUCCESS;
        $response = compact('code', 'data', 'message');
        return json($response);
    }

    /**
     * 响应分页数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年09月30日 19:34
     */
    public function renderPage($data = [], $message = 'SUCCESS')
    {
        $code = ResponseEnum::SUCCESS;
        if (is_array($data)) {
            $data = [
                'list'       => $data['data'],
                'pagination' => [
                    'page'  => $data['current_page'],
                    'size'  => $data['per_page'],
                    'total' => $data['total'],
                ]
            ];
        } else {
            $data = [
                'list'       => $data->getCollection()->toArray() ?: [],
                'pagination' => [
                    'page'  => $data->currentPage(),
                    'size'  => $data->listRows(),
                    'total' => $data->total(),
                ]
            ];
        }
        $response = compact('code', 'data', 'message');
        return json($response);
    }
}

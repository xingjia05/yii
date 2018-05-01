<?php
namespace common\dataservice\live;


use common\dataservice\BaseService;
use common\models\Live;

class LiveList extends BaseService
{
    public function getList($page, $size) {
        $result = array(
            'list' => array(),
            'page_info' => array(
                'total_size' => 0,
            )
        );
        $liveModel = new Live();
        $list = $liveModel->getList(($page-1)*$size, $size);
        $count = $liveModel->getCount();
        $result['page_info']['total_size'] = $count;
        $result['page_info']['total_page'] = ceil($count/$size);
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'id'     => $value['id'],
                'name'   => $value['name'],
                'h5_url' => $value['h5_url'],
                'hls_url' => $value['hls_url'],
                'rtmp_url' => $value['rtmp_url'],
            );
        }
        return $result;
    }
    
}
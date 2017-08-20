<?php
namespace common\dataservice\community;


use common\dataservice\BaseService;
use common\models\community\CommunityList AS CommunityListModel;

class CommunityList extends BaseService
{
    public function getList() {
        $result = array(
            'list' => array()
        );
        $communityModel = new CommunityListModel();
        $list = $communityModel->getList();
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'community_name' => $value['name'],
                'community_id'   => $value['id'],
            );
        }
        return $result;
    }
}
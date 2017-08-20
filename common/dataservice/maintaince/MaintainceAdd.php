<?php
namespace common\dataservice\maintaince;


use common\dataservice\BaseService;
use common\models\Maintaince;

class MaintainceAdd extends BaseService
{
    
    public static $status = array(
        '1' => '未解决',
        '2' => '解决',
    );
    
    public function add($data) {
        $maintaince = new Maintaince();
        $sqlData = array(
            'theme' => $data['theme'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'time' => date('Y-m-d H:i:s'),
        );
        return $maintaince->add($sqlData);
    }
    
    public function update($data) {
        $maintaince = new Maintaince();
        $oldData = $maintaince::findById($data['maintaince_id']);
        $sqlData = array();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $value != $oldData[$key]) {
                $sqlData[$key] = $value;
            }
        }
        return $maintaince->edit($data['maintaince_id'], $sqlData);
    }
    
    public function getList() {
        $result = array(
            'list' => array()
        );
        $maintaince = new Maintaince();
        $list = $maintaince->getList();
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'maintaince_id' => $value['id'],
                'theme'       => $value['theme'],
                'phone'       => $value['phone'],
                'address'     => $value['address'],
                'time'        => $value['time'],
                'status'      => isset(self::$status[$value['status']]) ? self::$status[$value['status']] : '未知',
                'server_name' => $value['server_name'],
                'server_id'   => $value['server_id'],
            );
        }
        return $result;
    }
    
}
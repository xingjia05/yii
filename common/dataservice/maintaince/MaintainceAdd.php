<?php
namespace common\dataservice\maintaince;


use common\dataservice\BaseService;
use common\models\Maintaince;
use common\models\Server;

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
        if (isset($data['server_id'])) {
            $serverDb = Server::findById($data['server_id']);
            $data['server_name'] = isset($serverDb['name']) ? $serverDb['name'] : '';
        }
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
    
    public function getList($page = 1, $size = 10) {
        $result = array(
            'list' => array(),
            'page_info' => array(
                'size' => $size,
                'total_size' => $size,
                'current_page' => $page,
                'total_page' => $page,
            )
        );
        $maintaince = new Maintaince();
        $list = $maintaince->getList(($page-1) * $size , $size);
        $count = $maintaince->getCount();
        $result['page_info']['total_size'] = $count;
        $result['page_info']['total_page'] = ceil($count/$size);
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
                'status'      => $value['status'],
                'status_name' => isset(self::$status[$value['status']]) ? self::$status[$value['status']] : '未知',
                'server_name' => $value['server_name'],
                'server_id'   => $value['server_id'],
            );
        }
        return $result;
    }
    
}
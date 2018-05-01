<?php
namespace common\dataservice\server;


use common\dataservice\BaseService;
use common\models\Server;
use common\models\Maintaince;

class ServerAdd extends BaseService
{
    
    public static $status = array(
        '1' => '在职',
        '2' => '离职',
    );
    
    public function add($data) {
        $serverModel = new Server();
        $sqlData = array(
            'name'       => $data['server_name'],
            'phone'      => $data['phone'],
            'entry_time' => $data['entry_time'],
            'status'     => $data['status'],
        );
        return $serverModel->add($sqlData);
    }
    
    public function update($data) {
        $serverModel = new Server();
        $oldData = $serverModel::findById($data['server_id']);
        $sqlData = array();
        $data['name'] = $data['server_name'];
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $value != $oldData[$key]) {
                $sqlData[$key] = $value;
            }
        }
        return $serverModel->edit($data['server_id'], $sqlData);
    }
    
    public function getList() {
        $result = array(
            'list' => array()
        );
        $serverModel = new Server();
        $list = $serverModel->getList(0, 100, array(Server::STATUS_ON, Server::STATUS_DEFAULT));
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'server_name' => $value['name'],
                'server_id'   => $value['id'],
                'phone'       => $value['phone'],
                'serve_count' => Maintaince::getCountByServer($value['id']),
                'entry_time'  => $value['entry_time'],
                'status'      => $value['status'],
                'status_str'  => isset(self::$status[$value['status']]) ? self::$status[$value['status']] : '未知',
            );
        }
        return $result;
    }
}
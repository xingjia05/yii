<?php
namespace common\dataservice\announcement;


use common\dataservice\BaseService;
use common\models\Announcement;

class AnnouncementAdd extends BaseService
{
    
    public function add($data) {
        $announcementModel = new Announcement();
        $sqlData = array(
            'announcement_title'  => $data['announcement_title'],
            'issuer'              => $data['issuer'],
            'image'               => $data['image'],
            'content'             => $data['content'],
        );
        return $announcementModel->add($sqlData);
    }
    
    public function update($data) {
        $announcementModel = new Announcement();
        $oldData = $announcementModel::findById($data['announcement_id']);
        $sqlData = array();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $value != $oldData[$key]) {
                $sqlData[$key] = $value;
            }
        }
        return $announcementModel->edit($data['announcement_id'], $sqlData);
    }
    
    public function getList() {
        $result = array(
            'list' => array()
        );
        $announcementModel = new Announcement();
        $list = $announcementModel->getList();
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'announcement_id'    => $value['id'],
                'announcement_title' => $value['announcement_title'],
                'issuer'             => $value['issuer'],
                'time'               => $value['create_time'],
            );
        }
        return $result;
    }
    
    public function delete($announcementId) {
        $sqlData = array(
            'is_deleted'      => Announcement::IS_DELETED_YES
        );
        $announcementModel = new Announcement();
        return $announcementModel->edit($announcementId, $sqlData);
    }
}
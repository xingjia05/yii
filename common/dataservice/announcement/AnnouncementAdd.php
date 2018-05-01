<?php
namespace common\dataservice\announcement;


use common\dataservice\BaseService;
use common\models\Announcement;

class AnnouncementAdd extends BaseService
{
    
    public static $defaultImage = 'http://47.93.188.93:8081/20171015//101500280813.jpg';
    
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
    
    public function getList($page, $size) {
        $result = array(
            'list' => array(),
            'page_info' => array(
                'size' => $size,
                'total_size' => $size,
                'current_page' => $page,
                'total_page' => $page,
            )
        );
        $announcementModel = new Announcement();
        $list = $announcementModel->getList(($page-1)*$size, $size);
        $count = $announcementModel->getCount();
        $result['page_info']['total_size'] = $count;
        $result['page_info']['total_page'] = ceil($count/$size);
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'announcement_id'    => $value['id'],
                'announcement_title' => $value['announcement_title'],
                'issuer'             => $value['issuer'],
                'time'               => $value['create_time'],
                'image'              => !empty($value['image']) ? $value['image'] : self::$defaultImage,
                'content'            => $value['content'],
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
    
    public function getInfo($newsId) {
        $announcementModel = new Announcement();
        $dbData = $announcementModel::findById($newsId);
        return array(
            'announcement_id' => $dbData['id'],
            'announcement_title' => $dbData['announcement_title'],
            'issuer' => $dbData['issuer'],
            'image' => $dbData['image'],
            'content' => $dbData['content'],
            'create_time' => $dbData['create_time'],
        );
    }
}
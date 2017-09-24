<?php
namespace common\dataservice\government;


use common\dataservice\BaseService;
use common\models\GovernmentNews;

class NewsAdd extends BaseService
{
    
    public function add($data) {
        $governmentNewsModel = new GovernmentNews();
        $sqlData = array(
            'news_title' => $data['news_title'],
            'issuer'     => $data['issuer'],
            'image'      => $data['image'],
            'content'    => $data['content'],
        );
        return $governmentNewsModel->add($sqlData);
    }
    
    public function update($data) {
        $governmentNewsModel = new GovernmentNews();
        $oldData = $governmentNewsModel::findById($data['news_id']);
        $sqlData = array();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $value != $oldData[$key]) {
                $sqlData[$key] = $value;
            }
        }
        return $governmentNewsModel->edit($data['news_id'], $sqlData);
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
        $governmentNewsModel = new GovernmentNews();
        $list = $governmentNewsModel->getList(($page-1)*$size, $size);
        $count = $governmentNewsModel->getCount();
        $result['page_info']['total_size'] = $count;
        $result['page_info']['total_page'] = ceil($count/$size);
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'news_id'     => $value['id'],
                'news_title'  => $value['news_title'],
                'issuer'      => $value['issuer'],
                'content'     => $value['content'],
                'time'        => $value['create_time'],
                'image'       => $value['image'],
                'create_time' => $value['create_time'],
            );
        }
        return $result;
    }
    
    public function delete($newsId) {
        $sqlData = array(
            'is_deleted'      => GovernmentNews::IS_DELETED_YES
        );
        $governmentNewsModel = new GovernmentNews();
        return $governmentNewsModel->edit($newsId, $sqlData);
    }
    
    public function getInfo($newsId) {
        $governmentNewsModel = new GovernmentNews();
        $dbData = $governmentNewsModel::findById($newsId);
        return array(
            'news_id' => $dbData['id'],
            'news_title' => $dbData['news_title'],
            'issuer' => $dbData['issuer'],
            'image' => $dbData['image'],
            'content' => $dbData['content'],
            'create_time' => $dbData['create_time'],
        );
    }
}
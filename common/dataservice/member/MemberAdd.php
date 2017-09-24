<?php
namespace common\dataservice\member;


use common\dataservice\BaseService;
use common\models\Member;

class MemberAdd extends BaseService
{
    
    public function add($data) {
        $member = new Member();
        $sqlData = array(
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => md5($data['password']),
        );
        return $member->add($sqlData);
    }
    
    public function update($data) {
        $member = new Member();
        if (isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        $oldData = $member::findById($data['member_id']);
        $sqlData = array();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $oldData) && $value != $oldData[$key]) {
                $sqlData[$key] = $value;
            }
        }
        return $member->edit($data['member_id'], $sqlData);
    }
    
    public function getList($page=1, $size=10) {
        $result = array(
            'list' => array(),
            'page_info' => array(
                'size' => $size,
                'total_size' => $size,
                'current_page' => $page,
                'total_page' => $page,
            )
        );
        $member = new Member();
        $list = $member->getList($page, $size);
        $count = $member->getCount();
        $result['page_info']['total_size'] = $count;
        $result['page_info']['total_page'] = ceil($count/$size);
        if (empty($list)) {
            return $result;
        }
        foreach ($list as $value) {
            $result['list'][] = array(
                'member_id' => $value['id'],
                'name'      => $value['name'],
                'phone'     => $value['phone'],
            );
        }
        return $result;
    }
    
    public function delete($memberId) {
        $sqlData = array(
            'is_deleted'      => Member::IS_DELETED_YES
        );
        $member = new Member();
        return $member->edit($memberId, $sqlData);
    }
}
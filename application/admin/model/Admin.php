<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{


    public function addAdmin($data) {
        if(empty($data) || !is_array($data)) {
            return false;
        }

        if($data['password']) {
            $data['password'] = md5($data['password']);
        }

        if($this->save($data)) {
            return true;
        }else {
            return false;
        }


    }

    public function getAdmin() {
        $pageRow = 3;
        $res = $this->paginate($pageRow);
        return $res;
    }

    public function getAdminById($id) {
        $res = db('admin')->where(['id'=>$id])->find();
        return $res;
    }

    public function updateAdminById($id, $data) {
        $res = db('admin')->where('id', $id)->update($data);
        return $res;
    }

}

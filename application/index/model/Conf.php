<?php
namespace app\index\model;

use think\Model;

class Conf extends Model
{

    public function getAllConf() {
        $_confs = $this->select();
        $confs = [];

        foreach ($_confs as $k=>$v) {
            $confs[$v['enname']] = $v['value'];
        }

        return $confs;
    }

}

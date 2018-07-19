<?php
namespace app\index\model;

use think\Model;

class Cate extends Model
{

    public function getAllConf() {
        $_confs = $this->select();
        $confs = [];

        foreach ($_confs as $k=>$v) {
            $confs[$v['enname']] = $v['value'];
        }

        return $confs;
    }

    //获取所有子栏目cateid和自身cateid
    public function getChildrenId($cateid) {
        $cateres = db('cate')->select();
        $arr = $this->_getChildrenId($cateres, $cateid);
        $arr[] = intval($cateid);
        $strId = implode(',', $arr);
        return $strId;

    }

    public function _getChildrenId($cateres, $cateid) {
        static $arr = [];
        foreach ($cateres as $k=>$v) {
            if($v['pid'] == $cateid) {
                $arr[] = $v['id'];
                $this->_getChildrenId($cateres, $v['id']);
            }
        }
        return $arr;
    }

    public function getParentsId($cateid){
        $cateres=$this->field('id,pid,catename,type')->select();
        $cates=db('cate')->field('id,pid,catename,type')->find($cateid);
        $pid=$cates['pid'];
        if($pid){
            $arr=$this->_getParentsId($cateres,$pid);
        }
        $arr[]=$cates;
        return $arr;
    }

    public function _getParentsId($cateres,$pid){
        static $arr=array();
        foreach ($cateres as $k => $v) {
            if($v['id'] == $pid){
                $arr[]=$v;
                $this->_getParentsId($cateres,$v['pid']);
            }
        }
        return $arr;
    }

    //获取首页子栏目导航
    public function getNavCates() {
        $navCates = db('cate')->where('recommend', '1')->select();
        return $navCates;
    }

    //获取栏目信息用户keywords desc
    public function getCateInfo($cateid) {
        $cateInfo = $this->field('id, catename, desc, keywords')->find($cateid);
        return $cateInfo;
    }


}

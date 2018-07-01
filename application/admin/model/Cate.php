<?php
namespace app\admin\model;

use think\Model;

class Cate extends Model
{


    public function getCate() {
        $res = db('cate')->select();
        return $res;
    }

    public function getCateById($id) {
        $res = db('cate')->where('id', $id)->find();
        return $res;
    }

    public function getCateTree() {
        $cates = db('cate')->order('sort')->select();
        return $this->sort($cates);
    }

    public function sort($data, $pid=0, $level=0) {
        static $arr = array();
        foreach ($data as $k=>$v) {

            if($v['pid'] == $pid) {
                $v['level'] = $level;
                $arr[] = $v;
                $this->sort($data, $v['id'], $level+1);
            }
        }
        return $arr;
    }


    public function getChildrenId($cateid) {
        $cateres = db('cate')->select();
        return $this->_getChildrenId($cateres, $cateid);

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

}

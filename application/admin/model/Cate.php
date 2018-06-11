<?php
namespace app\admin\model;

use think\Model;

class Cate extends Model
{

    public function addCate($data) {

        if(!is_array($data) || $data['pid'] == '' || trim($data['catename']) == '' || $data['type'] == '') return false;
        $res = db('cate')->insert($data);
        return $res;
    }


    public function getCate() {
        $res = db('cate')->field('id, catename, type, pid')->select();
        return $res;
    }

    public function getCateById($id) {
        $res = db('cate')->where('id', $id)->find();
        return $res;
    }

    public function getCateTree() {
        $cates = db('cate')->field('id, catename, type, pid')->select();
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

    public function deletecateById($id) {
        $res = db('cate')->where('id', $id)->delete();
        return $res;
    }

    public function getChildrenId($cateid) {
        $cateres = db('cate')->field('id, catename, type, pid')->select();
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

    public function updateCateById($data, $id) {
        if(!$data['catename']) {
            return 1;//栏目名为空
        }
        $res = db('cate')->where('id', $id)->update($data);
        return $res;
    }
}

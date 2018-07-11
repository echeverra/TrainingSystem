<?php
namespace app\admin\model;

use think\Model;

class AuthRule extends Model
{

    public function getAuthRuleTree() {
        $authRules = db('auth_rule')->order('sort')->select();
        return $this->sort($authRules);
    }

    public function sort($data, $pid=0) {
        static $arr = array();
        foreach ($data as $k=>$v) {

            if($v['pid'] == $pid) {
                $v['dataid'] = $this->getParentId($v['id']);
                $arr[] = $v;
                $this->sort($data, $v['id']);
            }
        }
        return $arr;
    }


    public function getChildrenId($authRuleId) {
        $authRules = db('auth_rule')->select();
        return $this->_getChildrenId($authRules, $authRuleId);

    }

    public function _getChildrenId($authRules, $authRuleId) {
        static $arr = [];

        foreach ($authRules as $k=>$v) {
            if($v['pid'] == $authRuleId) {
                $arr[] = $v['id'];
                $this->_getChildrenId($authRules, $v['id']);
            }
        }
        return $arr;
    }


    public function getParentId($authRuleId) {
        $authRules = db('auth_rule')->select();
        return $this->_getParentId($authRules, $authRuleId, True);

    }

    public function _getParentId($authRules, $authRuleId, $clear=False) {
        static $arr = [];
        if($clear) {
            $arr = array();
        }
        foreach ($authRules as $k=>$v) {
            if($v['id'] == $authRuleId) {
                $arr[] = $v['id'];
                $this->_getParentId($authRules, $v['pid'], False);
            }
        }
        asort($arr);
        $arrStr = implode('-', $arr);
        return $arrStr;
    }


}

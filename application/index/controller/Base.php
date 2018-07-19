<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Conf as ConfModel;
use app\index\model\Cate as CateModel;

class Base extends Controller
{
    public function _initialize()
    {
        if(input('cateid')) {
            $this->getPosition(input('cateid'));
        }
        if(input('artid')) {
            $article = db('article')->where('id', input('artid'))->find();
            $cateid = $article['id'];
            $this->getPosition($cateid);
        }
        //网站配置项
        $this->getConfs();
        //栏目导航
        $this->getAllCates();
    }

    public function getAllCates() {
        $cates = db('cate')->where('pid', 0)->select();
        foreach($cates as $k=>$v) {
            $children = db('cate')->where('pid', $v['id'])->select();
            if($children) {
                $cates[$k]['children'] = $children;
            }else {
                $cates[$k]['children'] = 0;
            }
        }
//        dump($cates);die;
        $this->assign('cates', $cates);
    }

    public function jsonReturn($code, $data, $msg='') {
        return json(['code'=>$code, 'data'=>$data, 'msg'=>$msg]);
    }

    public function getConfs() {
        $confModel = new ConfModel();
        $confs = $confModel->getAllConf();
        //获取所有后台配置项
        $this->assign('confs', $confs);
    }

    //获取当前位置
    public function getPosition($cateid) {
        $cateModel = new CateModel();
        $parentsCates = $cateModel->getParentsId($cateid);
//        dump($parentsCates);die;
        $this->assign('parentsCates', $parentsCates);
    }
}

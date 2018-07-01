<?php

namespace app\admin\controller;

use app\admin\model\Conf as ConfModel;
use think\Loader;

class Conf extends Base
{

    public function lis()
    {

        $row = 10;
        $conf = new ConfModel();
        $confs = $conf->order('sort')->paginate($row);
        $type = $conf->type;

        if(request()->isPost()) {
            $sorts = input('post.');

            foreach ($sorts as $k=>$v) {
                if(!is_numeric($v) || intval($v)>100 || intval($v)<1 || !(is_numeric($v)&&!strpos($v, '.'))) {
                    $this->error('请输入1到100的正整数');
                }
            }
            foreach ($sorts as $k=>$v) {
                $conf->update(['id'=>$k, 'sort'=>$v]);
            }
            $this->success('排序成功');
        }

        $this->assign(['confs' => $confs, 'type' => $type]);
        return $this->fetch('lis');
    }


    public function add()
    {
        $conf = new ConfModel();
        $type = $conf->type;

        if (request()->isPost()) {
            $data = input('post.');
            $validate = Loader::validate('Conf');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            if($data['values']) {
                $data['values'] = str_replace('，', ',', $data['values']);
            }
            if ($conf->save($data)) {
                $this->success('添加配置成功', url('conf/lis'));
            } else {
                $this->error('添加配置失败');
            }

        }

        $this->assign('type', $type);
        return $this->fetch('add');
    }

    public function edit()
    {
        $confmodel = new ConfModel();
        $type = $confmodel->type;
        $id = input('id');
        $conf = $confmodel->find($id);

        if (request()->isPost()) {
            $data = input('post.');
            $validate = Loader::validate('Conf');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            if($data['values']) {
                $data['values'] = str_replace('，', ',', $data['values']);
            }
            if ($conf->update($data)) {
                $this->success('修改配置成功', url('conf/lis'));
            } else {
                $this->error('修改配置失败');
            }
        }

        $this->assign(['conf' => $conf, 'type' => $type]);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $conf = new ConfModel();
        if($conf->where('id', $id)->delete()) {
            $this->success('删除配置成功', url('conf/lis'));
        }else {
            $this->error('删除配置失败');
        }
    }

    public function conf() {
        $confmodel = new ConfModel();
        $confs = $confmodel->order('sort')->select();

        if(request()->isPost()) {
            $data = input('post.');
            $dataKeyArr = [];   //提交name数组
            foreach ($data as $k=>$v) {
                $dataKeyArr[] = $k;
            }

            //取出所有enname
            $_ennameArr = db('conf')->field('enname')->select();  //二维数组
            $ennameArr = [];
            foreach ($_ennameArr as $k=>$v) {
                $ennameArr[] = $v['enname'];        //变一维数组
            }

            //比对，筛选出checkbox提交的name(未勾选)
            foreach ($ennameArr as $k=>$v) {
                if(!in_array($v, $dataKeyArr)) {
                    $confmodel->where('enname', $v)->update(['value'=>'']); //没有提交的字段，置空
                }
            }

            foreach ($data as $k=>$v) {
                $confmodel->where('enname', $k)->update(['value'=>$v]);
            }

            $this->success('修改配置成功');
        }

        $this->assign('confs', $confs);
        return $this->fetch('conf');
    }
}

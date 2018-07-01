<?php
namespace app\admin\controller;
use app\admin\model\Link as LinkModel;
use think\Loader;

class Link extends Base
{


    public function lis()
    {
        $row = 10;
        $link = new LinkModel();

        if(request()->isPost()) {
            $sorts = input('post.');
            foreach ($sorts as $k=>$v) {
                if(!is_numeric($v) || intval($v)>100) {
                    $this->error('请输入小于等于100的正确数值');
                }
            }
            foreach ($sorts as $k=>$v) {
                $link->update(['id'=>$k, 'sort'=>$v]);

            }
            $this->success('排序成功');
        }

        $links = $link->order('sort')->paginate($row);
        $this->assign("links", $links);
        return $this->fetch('lis');
    }

    public function add()
    {

        if(request()->isPost()) {
            $data = input('post.');
            $link = new LinkModel();
            $validate = Loader::validate('Link');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $res = $link->save($data);
            if($res) {
                $this->success('添加友情链接成功', url('Link/lis'));
            }else {
                $this->error('添加友情链接失败');
            }
        }

        return $this->fetch('add');
    }

    public function edit()
    {
        $link = new LinkModel();
        $id = input('id');
        $linkres = $link->find($id); //查询不到NULL
        if (!$linkres) {
            $this->error('无法查询到该友情链接', url('Link/lis'));
        }

        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');
            $validate = Loader::validate('Link');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $res = $link->update($data);
            if($res || $res==0) {  //等于0为没修改，影响0条
               $this->success('修改友情链接成功', url('Link/lis'));
            }else {
                $this->error('修改友情链接失败');
            }
        }

        $this->assign('link', $linkres);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $link = new LinkModel();
        $res = $link->where(['id'=>$id])->delete();
        if($res) {
            $this->success('删除友情链接成功', url('link/lis'));
        }else {
            $this->error('删除友情链接失败');
        }
    }


}

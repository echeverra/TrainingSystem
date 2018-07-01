<?php
namespace app\admin\controller;
use app\admin\model\Cate as CateModel;
use app\admin\model\Article as ArticleModel;
use think\Loader;

class Cate extends Base
{

    public $beforeActionList = [
        'delSonCate' => ['only'=>"del"]
    ];

    public function lis()
    {
        $cate = new CateModel();

        if(request()->isPost()) {
            $sorts = input('post.');

            foreach ($sorts as $k=>$v) {
                if(!is_numeric($v) || intval($v)>100 || intval($v)<1 || !(is_numeric($v)&&!strpos($v, '.'))) {
                    $this->error('请输入1到100的正整数');
                }
            }
            foreach ($sorts as $k=>$v) {
                $cate->update(['id'=>$k, 'sort'=>$v]);
            }
            $this->success('排序成功');
        }

        $cates = $cate->getCateTree();
        $this->assign("cates", $cates);
        return $this->fetch('lis');
    }

    public function add()
    {
        $cate = new CateModel();

        if(request()->isPost()) {
            $data = input('post.');
            $validate = Loader::validate('Cate');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $res = $cate->save($data);
            if($res) {
                $this->success('添加栏目成功！', 'lis');
            }else {
                $this->error('添加栏目失败！请确认必填项');
            }
        }

        $cates = $cate->getCateTree();
        $this->assign('cates', $cates);
        return $this->fetch('add');
    }

    public function edit()
    {
        $cate = new CateModel();
        $id = input('id');
        $cateres = $cate->getCateById($id); //查询不到NULL
        $cateTree = $cate->getCateTree();
        if (!$cateres) {
            $this->error('无法查询到该栏目', url('cate/lis'));
        }

        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');
            $validate = Loader::validate('Cate');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $res = $cate->update($data);

            if($res || $res==0) {  //等于0为没修改，影响0条
               $this->success('修改栏目成功', url('cate/lis'));
            }else {
                $this->error('修改栏目失败');
            }
        }

        $this->assign(['cate'=> $cateres, 'cateTree'=> $cateTree]);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $cate = new CateModel();
        $res = $cate->where(['id'=>$id])->delete();
        if($res) {
            $this->success('删除栏目成功', url('cate/lis'));
        }else {
            $this->error('删除栏目失败');
        }
    }

    public function delSonCate() {
        $cateid = input('id'); //要删除的当前栏目的id
        $cate = new CateModel();
        $sonid = $cate->getChildrenId($cateid);

        $allcateid = $sonid;
        $allcateid[] = $cateid;
        $article = new ArticleModel();
        foreach ($allcateid as $k => $v) {
            $article->where(['cateid'=>$v])->delete();
        }
        if($sonid) {
            db('cate')->delete($sonid);
        }
    }

}

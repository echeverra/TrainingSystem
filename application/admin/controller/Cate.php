<?php
namespace app\admin\controller;
use app\admin\model\Cate as CateModel;

class Cate extends Base
{

    public $beforeActionList = [
        'delSonCate' => ['only'=>"del"]
    ];

    public function cateList()
    {
        $cate = new CateModel();

        if(request()->isPost()) {
            $sorts = input('post.');
            foreach ($sorts as $k=>$v) {
                $cate->update(['id'=>$k, 'sort'=>$v]);
            }
            $this->success('排序成功');
        }

        $cates = $cate->getCateTree();
        $this->assign("cates", $cates);
        return $this->fetch('cateList');
    }

    public function add()
    {
        $cate = new CateModel();

        if(request()->isPost()) {
            $data = input('post.');
            $res = $cate->addCate($data);

            if($res) {
                $this->success('添加栏目成功！', 'cateList');
            }else {
                $this->error('添加栏目失败！');
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
            $this->error('无法查询到该栏目', url('cate/cateList'));
        }

        if(request()->isPost()) {  //提交修改表单
            $data = input('post.');
            $res = $cate->updateCateById($data, $cateres['id']);

            if($res == -1) {
                $this->error('栏目名不能为空');
            }else if($res || $res==0) {  //等于0为没修改，影响0条
               $this->success('修改栏目成功', url('cate/cateList'));
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
        $res = $cate->deletecateById($id);
        if($res) {
            $this->success('删除栏目成功', url('cate/cateList'));
        }else {
            $this->error('删除栏目失败');
        }
    }

    public function delSonCate() {
        $cateid = input('id'); //要删除的当前栏目的id
        $cate = new CateModel();
        $sonid = $cate->getChildrenId($cateid);
        if($sonid) {
            db('cate')->delete($sonid);
        }
    }

}

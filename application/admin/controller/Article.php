<?php
namespace app\admin\controller;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Cate as CateModel;
use think\Loader;

class Article extends Base
{

    public function lis() {
        $row = 10;
        $article = new ArticleModel();

        if(request()->isPost()) {
            $sorts = input('post.');
            foreach ($sorts as $k=>$v) {
                if(!is_numeric($v) || intval($v)>100 || intval($v)<1 || !(is_numeric($v)&&!strpos($v, '.'))) {
                    $this->error('请输入1到100的正整数');
                }
            }
            foreach ($sorts as $k=>$v) {
                $article->update(['id'=>$k, 'sort'=>$v]);
            }
            $this->success('排序成功');
        }

        //联表查询
        $articles = db('article')->alias('a')->join('cate b', 'a.cateid = b.id')->order('a.id desc')->field('a.*, b.catename')->paginate($row);
//        $articles = $article->getArticles();
        $this->assign('articles', $articles);
        return $this->fetch('lis');
    }

    public function filterLis() {
        $row = 10;
        $article = new ArticleModel();

        if(request()->isPost()) {
            $sorts = input('post.');
            foreach ($sorts as $k=>$v) {
                if(!is_numeric($v) || intval($v)>100 || intval($v)<1 || !(is_numeric($v)&&!strpos($v, '.'))) {
                    $this->error('请输入1到100的正整数');
                }
            }
            foreach ($sorts as $k=>$v) {
                $article->update(['id'=>$k, 'sort'=>$v]);
            }
            $this->success('排序成功');
        }

        //联表查询
        $articles = db('article')->alias('a')->join('cate b', 'a.cateid = b.id')->where('a.recommend', 1)->order('a.sort asc')->field('a.*, b.catename')->paginate($row);
//        $articles = $article->getArticles();
        $this->assign('articles', $articles);
        return $this->fetch('lis');
    }

    public function add() {
        if(request()->isPost()) {
            $data = [
                'title' => input('title'),
                'author' => input('author'),
                'keywords' => input('keywords'),
                'desc' => input('desc'),
                'cateid' => input('cateid'),
                'content' => input('content'),
                'status' => input('status'),
                'recommend' => input('recommend'),
                'time' => time(),
            ];
            $article = new ArticleModel();
            $validate = Loader::validate('Article');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            if($article->save($data)) {
                $this->success('添加文章成功', url('lis'));
            }else {
                $this->error('添加文章失败');
            }
        }
        $cate = new CateModel();
        $cates = $cate->getCateTree();
        $this->assign("cates", $cates);
        return $this->fetch('add');
    }

    public function edit() {

        if(request()->isPost()) {
            $data = [
                'id' => input('id'),
                'title' => input('title'),
                'author' => input('author'),
                'keywords' => input('keywords'),
                'desc' => input('desc'),
                'cateid' => input('cateid'),
                'content' => input('content'),
                'status' => input('status'),
                'recommend' => input('recommend'),
                'time' => time(),
            ];
            $article = new ArticleModel();
            $validate = Loader::validate('Article');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            if($article->update($data)) {
                $this->success('修改文章成功', url('lis'));
            }else {
                $this->error('修改文章失败');
            }
        }

        $article = new ArticleModel();
        $id = input('id');
        $articleres = $article->getArticleById($id);

        //查询栏目
        $cate = new CateModel();
        $cates = $cate->getCateTree();
        $this->assign(["cates"=>$cates, 'article'=>$articleres]);
        return $this->fetch('edit');
    }

    public function del() {
        $id = input('id');
        $article = new ArticleModel();
        if($article->destroy($id)) {
            $this->success('删除文章成功', url('lis'));
        }else {
            $this->error('删除文章失败');
        }
    }

}

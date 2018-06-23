<?php
namespace app\admin\controller;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Cate as CateModel;

class Article extends Base
{

    public function articleList() {
        $row = 10;
        $article = new ArticleModel();
        //联表查询
        $articles = db('article')->alias('a')->join('cate b', 'a.cateid = b.id')->field('a.*, b.catename')->paginate($row);
//        $articles = $article->getArticles();
        $this->assign('articles', $articles);
        return $this->fetch('articleList');
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
                'time' => time(),
            ];
            $article = new ArticleModel();
            if($article->save($data)) {
                $this->success('添加文章成功', url('articleList'));
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
                'time' => time(),
            ];
            $article = new ArticleModel();
            if($article->update($data)) {
                $this->success('修改文章成功', url('articleList'));
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
            $this->success('删除文章成功', url('articleList'));
        }else {
            $this->error('删除文章失败');
        }
    }

}

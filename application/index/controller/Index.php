<?php
namespace app\index\controller;
use app\index\model\Article as ArticleModel;
use app\index\model\Cate as CateModel;

class Index extends Base
{
    public function index()
    {
        $articleModel = new ArticleModel();
        $newArticles =  $articleModel->getNewArticles(10);
        $hotIndexArticles = $articleModel->getIndexHotArticles(5);
        $cateModel = new CateModel();

        //友情链接
        $links = db('link')->select();
        //获取内容部分导航栏目
        $navCates = $cateModel->getNavCates();
        //获取推荐文章(banner)
        $recommendArticles = $articleModel->getRecommendArticles();

        $this->assign([
            'newArticles'=>$newArticles,
            'hotIndexArticles'=>$hotIndexArticles,
            'links'=>$links,
            'navCates'=>$navCates,
            'recommendArticles'=>$recommendArticles
        ]);
        return $this->fetch('index');
    }
}

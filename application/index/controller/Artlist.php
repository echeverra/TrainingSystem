<?php
namespace app\index\controller;
use app\index\model\Article as ArticleModel;

class Artlist extends Base
{
    public function lis()
    {
        $articleModel = new ArticleModel();
        $articles = $articleModel->getAllArticles(input('cateid'));
        $hotArticles = $articleModel->getHotArticles(input('cateid'), 5);

        $this->assign(['articles'=>$articles, 'hotArticles'=>$hotArticles]);
        return $this->fetch('lis');
    }
}

<?php
namespace app\index\controller;
use app\index\model\Article as ArticleModel;

class ImgList extends Base
{
    public function lis()
    {
        $articleModel = new ArticleModel();
        $articles = $articleModel->getAllArticles(input('cateid'));
        $this->assign(['articles'=>$articles]);
        return $this->fetch('lis');
    }
}

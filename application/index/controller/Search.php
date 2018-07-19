<?php
namespace app\index\controller;
use app\index\model\Article as ArticleModel;
class Search extends Base
{
    public function index()
    {
        $row = 10;
        $keywords = input('keywords');
        //带参分页
        if(trim($keywords) != '') {
            $searchRes = db('article')->where('title', 'like', '%'.$keywords.'%')->order('id desc')->paginate($row ,false, $config=['query'=>['keywords'=>$keywords]]);
            $this->assign(['searchRes'=>$searchRes]);
        }
        $articleModel = new ArticleModel();
        $hotIndexArticles = $articleModel->getIndexHotArticles(5);
        $this->assign([
            'hotIndexArticles'=>$hotIndexArticles,
            'keywords'=>$keywords
        ]);
        return $this->fetch('index');
    }
}

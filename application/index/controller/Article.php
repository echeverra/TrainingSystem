<?php
namespace app\index\controller;
use app\index\model\Article as ArticleModel;
class Article extends Base
{
    public function article()
    {
        $artid = input('artid');
        db('article')->where(['id'=>$artid])->setInc('click');  //点击访问数自增1
        $article = db('article')->find($artid);

        //获取推荐文章
        $articleModel = new ArticleModel();
        $hotArticles = $articleModel->getHotArticles($article['cateid'], 3);
        $this->assign(['article'=>$article, 'hotArticles'=>$hotArticles]);
        return $this->fetch('article');
    }
}

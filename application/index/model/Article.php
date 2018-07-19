<?php
namespace app\index\model;
use think\Model;
use app\index\model\Cate as CateModel;

class Article extends Model
{
    public function getAllArticles($cateid) {
        $row = 10;
        $cateModel = new CateModel();
        if($cateid == 1 || $cateid ==6) {//1全部文章列表  6所有图片列表
            $articles = db('article')->where('status', '1')->order('id desc')->paginate($row);
        }else {
            $allCateId = $cateModel->getChildrenId($cateid);
            $articles = db('article')->where("cateid", "in", $allCateId)->where('status', '1')->order('id desc')->paginate($row);
        }
        return $articles;
    }

    //获取栏目热门文章
    public function getHotArticles($cateid, $num=5)
    {
        $cateModel = new CateModel();
        if ($cateid==1) { //全部文章列表
            $articles = db('article')->where('status', '1')->field('id, title, time, thumb, click, like')->order('click desc')->limit($num)->select();
        }else {
            $allCateId = $cateModel->getChildrenId($cateid);
            $articles = db('article')->where("cateid IN($allCateId)")->where('status', '1')->field('id, title, time, thumb, click, like')->order('click desc')->limit($num)->select();

        }

        return $articles;
    }

    //获取最新文章
    public function getNewArticles($num=10) {
        $newArticles = db('article')->alias('a')->where('a.status', '1')->join('ts_cate c', 'a.cateid=c.id')->field('a.id, a.title, a.desc, a.keywords, a.author, a.thumb, a.click, a.like, a.time, a.cateid, c.catename, c.type')
            ->limit($num)->order('a.id desc')->select();
        return $newArticles;
    }

    //获取首页全部热门文章
    public function getIndexHotArticles($num=5) {
        $hotIndexArticles = $this->field('id, title, thumb')->where('status', '1')->order('click desc')->select();
        return $hotIndexArticles;
    }

    //获取推荐文章(banner)
    public function getRecommendArticles() {
        $recommendArticles = $this->where(['recommend'=>1, 'status'=>1])->field('id, title, thumb, sort')->select();
        return $recommendArticles;
    }
}

<?php
namespace app\admin\model;

use think\Model;

class Article extends Model
{

    protected static function init()
    {
        Article::event('before_insert', function ($article) {
            if($_FILES['thumb']['name']) {
                $file = request()->file('thumb');
                if($file) {
                    $info = $file->move(ROOT_PATH . 'public/uploads');
                    if($info) {
                        $article['thumb'] =  '/uploads/' . $info->getSaveName();
                    }else {
                        echo $info->getError();
                    }
                }
            }
        });

        Article::event('before_update', function ($article) {
            if($_FILES['thumb']['name']) {
                $articleRes=Article::find($article->id);
                $thumbPath = $_SERVER['DOCUMENT_ROOT'] . $articleRes['thumb'];
                //判断原来是否存在缩略图,若存在则删除
                if(file_exists($thumbPath)) {
                    @unlink($thumbPath);
                }
                $file = request()->file('thumb');
                if($file) {
                    $info = $file->move(ROOT_PATH . 'public/uploads');
                    if($info) {
                        $article['thumb'] = '/uploads/' . $info->getSaveName();
                    }else {
                        echo $info->getError();
                    }
                }
            }
        });

        Article::event('before_delete', function ($article) {
                $articleRes=Article::find($article->id);
                $thumbPath = $_SERVER['DOCUMENT_ROOT'] . $articleRes['thumb'];
                //判断原来是否存在缩略图,若存在则删除
                if(file_exists($thumbPath)) {
                    @unlink($thumbPath);
                }
        });
    }

    public function getArticles() {
        $row = 2;
        $res = $this->paginate($row);
        return $res;
    }

    public function cate() {
        return $this->belongsTo('cate', 'id', 'id');
    }

    public function getArticleById($id) {
        $res = $this->where(['id'=>$id])->find();
        return $res;
    }

}

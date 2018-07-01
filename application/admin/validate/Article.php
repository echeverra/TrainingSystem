<?php
namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title'  =>  ['require', 'max'=>60],
        'keywords' =>  ['require', 'max'=>100],
        'desc' =>  ['require', 'max'=>255],
        'content' =>  ['require'],
        'cateid' =>  ['require'],
    ];

    protected $message = [
        'title.require'  =>  '文章标题不能为空',
        'title.max'  =>  '文章标题不能超过60个字符',
        'keywords.require'  =>  '关键字不能为空',
        'keywords.max'  =>  '关键字不能超过100个字符',
        'desc.require'  =>  '文章描述不能为空',
        'desc.max'  =>  '文章描述不能超过255个字符',
        'content.require'  =>  '文章内容不能为空',
        'cateid.require' =>  '所属栏目不能为空',
    ];

    protected $scene = [
        'add'   =>  ['title', 'keywords', 'desc', 'content', 'cateid'],
        'edit'   =>  ['title', 'keywords', 'desc', 'content', 'cateid'],
    ];

}

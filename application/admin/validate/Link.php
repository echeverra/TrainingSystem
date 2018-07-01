<?php
namespace app\admin\validate;

use think\Validate;

class Link extends Validate
{
    protected $rule = [
        'title'  =>  ['require', 'max'=>30, 'unique'=>'link'],
        'url' =>  ['url', 'require', 'max'=>150],
    ];

    protected $message = [
        'title.require'  =>  '友情链接标题不能为空',
        'title.unique'  =>  '友情链接标题不能重复',
        'title.max'  =>  '友情链接标题不能超过30个字符',
        'url.require' =>  '友情链接不能为空',
        'url.max' =>  '友情链接不能超过150个字符',
        'url.url' =>  '友情链接地址格式不正确',
    ];

    protected $scene = [
        'add'   =>  ['title'=>'require|max:30|unique:link','url'],
        'edit'  =>  ['title','url'],
    ];

}

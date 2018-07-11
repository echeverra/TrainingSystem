<?php
namespace app\admin\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'title'  =>  ['require', 'max'=>20, 'unique'=>'auth_rule'],
        'name'  =>  ['require', 'max'=>80, 'unique'=>'auth_rule'],
    ];

    protected $message = [
        'title.require'  =>  '权限标题不能为空',
        'title.unique'  =>  '权限标题不能重复',
        'title.max'  =>  '权限标题不能超过20个字符',
        'name.require' =>  '控制器/方法名不能为空',
        'name.max' =>  '控制器/方法名不能超过80个字符',
        'name.unique' =>  '控制器/方法名不能重复',
    ];

    protected $scene = [
        'add'   =>  ['title', 'name'],
        'edit'   =>  ['title', 'name'],
    ];

}

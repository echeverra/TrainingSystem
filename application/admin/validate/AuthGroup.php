<?php
namespace app\admin\validate;

use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'title'  =>  ['require', 'max'=>100, 'unique'=>'auth_group'],
        'status' =>  ['require'],
        'rules' =>  ['require', 'max'=>80],
    ];

    protected $message = [
        'title.require'  =>  '用户组名称不能为空',
        'title.unique'  =>  '用户组名称不能重复',
        'title.max'  =>  '用户组名称不能超过100个字符',
        'status.require' =>  '启用状态不能为空',
        'rules.require' =>  '配置权限不能为空',
        'rules.max' =>  '配置权限不能超过80个字符',
    ];

    protected $scene = [
        'add'  =>  ['title','status', 'rules'],
        'edit'  =>  ['title','status', 'rules'],
    ];

}

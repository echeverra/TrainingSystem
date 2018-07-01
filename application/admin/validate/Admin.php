<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'username'  =>  ['require', 'max'=>30, 'unique'=>'admin'],
        'password' =>  ['require', 'max'=>32],
    ];

    protected $message = [
        'username.require'  =>  '管理员名不能为空',
        'username.max'  =>  '管理员名不能超过30个字符',
        'username.unique'  =>  '管理员名不能重复',
        'password.require'  =>  '管理员密码不能为空',
        'password.max'  =>  '管理员密码不能超过32个字符',
    ];

    protected $scene = [
        'add'   =>  ['username', 'password'],
        'edit'   =>  ['username', 'password'=>'max:32'],
    ];

}

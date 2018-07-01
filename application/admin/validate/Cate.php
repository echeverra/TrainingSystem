<?php
namespace app\admin\validate;

use think\Validate;

class Cate extends Validate
{
    protected $rule = [
        'catename'  =>  ['require', 'max'=>30, 'unique'=>'cate'],
        'type' =>  ['require'],
        'pid' =>  ['require'],
    ];

    protected $message = [
        'catename.require'  =>  '栏目名不能为空',
        'catename.max'  =>  '栏目名不能超过30个字符',
        'catename.unique'  =>  '栏目名不能重复',
        'type.require'  =>  '栏目类型不能为空',
        'pid.require'  =>  '上级栏目不能为空',
        'sort.require' =>  '栏目排序不能为空',
        'sort.number' =>  '栏目排序只能填写数字',
    ];

    protected $scene = [
        'add'   =>  ['catename', 'type', 'pid'],
        'edit'   =>  ['catename', 'type', 'pid'],
    ];

}

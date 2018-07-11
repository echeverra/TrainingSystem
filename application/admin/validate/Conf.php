<?php
namespace app\admin\validate;

use think\Validate;

class Conf extends Validate
{
    protected $rule = [
        'cnname'  =>  ['require', 'max'=>50, 'unique'=>'conf'],
        'enname'  =>  ['require', 'max'=>50, 'unique'=>'conf', 'alphaDash'],
        'value' =>  ['require'],
        'keywords' => ['max'=>100],
        'desc' => ['max'=>255],
    ];

    protected $message = [
        'cnname.require'  =>  '配置中文名称不能为空',
        'cnname.unique'  =>  '配置中文名称不能重复',
        'cnname.max'  =>  '配置中文名称不能超过30个字符',
        'enname.require'  =>  '配置英文名称不能为空',
        'enname.unique'  =>  '配置英文名称不能重复',
        'enname.max'  =>  '配置英文名称不能超过30个字符',
        'enname.alphaDash'  =>  '配置英文名称只能为英文和数字，下划线_及破折号-',
        'value.require' =>  '配置值不能为空',
        'keywords.max'  =>  '配置关键词不能超过100个字符',
        'desc.max'  =>  '配置描述不能超过100个字符',
    ];

    protected $scene = [
        'add'   =>  ['cnname', 'enname', 'keywords', 'desc'],
        'edit'   =>  ['cnname', 'enname', 'keywords', 'desc'],
    ];

}

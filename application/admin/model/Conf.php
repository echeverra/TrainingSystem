<?php
namespace app\admin\model;

use think\Model;

class Conf extends Model
{

    public $type = array(
        '1' => '单行文本框',
        '2' => '文本域',
        '3' => '单选按钮',
        '4' => '复选按钮',
        '5' => '下拉菜单',
    );

}

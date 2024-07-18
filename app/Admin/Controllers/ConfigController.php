<?php


namespace App\Admin\Controllers;


use App\DataModels\Config;
use App\Service\ConfigService;
use App\Service\ConfigTextService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class ConfigController extends AdminController
{
    protected $title = '配置管理';
    protected $description = [
        'index' => " ",
        "show" => " ",
    ];

    public function index(Content $content)
    {
        return $content
            ->title($this->title)
            ->description($this->description['index'])
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(Config::index());
                });
            });
    }

    public function change()
    {
        $parameters = request()->all();
        if (is_wrong_data($parameters, "key")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "val")) {
            // return handle_response([], "error");
        }

        if ($parameters["key"] == "xianjing_time") {
            if (!is_numeric($parameters["val"])) {
                return handle_response([], "只能为数字");
            }
        }
        if ($parameters["key"] == "xianjing_num") {
            if (!is_numeric($parameters["val"])) {
                return handle_response([], "只能为数字");
            }
        }

        $config = ConfigService::get($parameters["key"]);
        if (!$config) {
            return handle_response([], "error");
        }

        ConfigService::set($config, $parameters["val"]);
        
        return handle_response([], "成功");
    }
    
    public function setReplyKey()
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "data")) {
            return handle_response([], "数据为空");
        }
        
        $data = $parameters["data"];
        
        $data = str_replace("\n", "", $data);
        $data = str_replace("，", ",", $data);
        
        $config = ConfigTextService::one("reply");
        if (!$config) {
            return handle_response([], "error");
        }
        
        ConfigTextService::setKey($config, $data);
        
        return handle_response([], "成功");
    }
    
    public function setReplyVal()
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "data")) {
            return handle_response([], "数据为空");
        }
        
        $data = $parameters["data"];
        
        $config = ConfigTextService::one("reply");
        if (!$config) {
            return handle_response([], "error");
        }

        ConfigTextService::setVal($config, $data);
        
        return handle_response([], "成功");
    }
}
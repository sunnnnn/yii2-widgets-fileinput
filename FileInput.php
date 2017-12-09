<?php
namespace sunnnnn\fileinput; 

use Yii;
use yii\helpers\Html; 
use yii\helpers\Json;
use yii\widgets\InputWidget; 

/**
 * http://plugins.krajee.com/file-input
 * https://github.com/sunnnnn/yii2-widgets-fileinput
 * 
* @use: 
* @date: 2017年12月8日 下午1:24:21
* @author: sunnnnn [www.sunnnnn.com] [mrsunnnnn@qq.com]
 */
class FileInput extends InputWidget{
    /**
     * @var string
     */
    public $_action      = null;
    /**
     * 是否支持多文件上传
     * @var boolean
     */
    public $_multiple    = true;
    /**
     * 占位符
     * @var string
     */
    public $_placeholder = '请选择文件进行上传';
    /**
     * 语言，默认中文
     * @var string
     */
    public $_language    = 'zh';
    /**
     * 自定义样式类
     * @var string
     */
    public $_class       = '';
    /**
     * 支持上传文件的扩展名， ["csv", "txt"]
     * @var array
     */
    public $_extensions  = [];
    /**
     * 是否展示上传图片
     * @var boolean
     */
    public $_showPreview = true;
    /**
     * 预览文件的格式 'image', 'html', 'text', 'video', 'audio', 'flash', 'object', 'other'等
     * @var string
     */
    public $_previewType = 'image';
    /**
     * 上传时附加信息
     * @var array
     */
    public $_data = [];
    /**
     * 原生配置
     * @var array
     */
    public $_options = [];
    /**
     * 当前配置和原生配置合并
     * @var boolean
     */
    public $_optionsMerge = true;
    /**
     * 初始化预览数据
     * _preview => [
     *    ['key/id' => '主键', 'url' => '图片路径', 'name/caption' => '图片名称', 'delete' => '删除图片的路由', 'download' => false, 'type' => '文件类型', 'filetype' => '具体文件类型', 'data' => ['附加数据' => '数组格式'], 'size' => '文件大小，单位B'],
     *    ['key/id' => 1, 'url' => 'http://xxx.xxx.xxxx/xxx.jpg', 'name/caption' => 'JGP', 'delete' => Url::to(['/action/delete1'])],
     *    ['key/id' => 2, 'url' => 'https://xxx.xxx.xxxx/xxx.png', 'name/caption' => 'PNG', 'delete' => Url::to(['/action/delete2'])],
     *    ['key/id' => 3, 'url' => '/uplaods/images/xxx.mp4', 'name/caption' => '这是一首mp4', 'delete' => Url::to(['/action/delete3']), 'download' => true, 'type' => 'video', 'filetype' => 'video/mp4', 'size' => 1024000],
     * ]
     * @var array
     */
    public $_preview = [];
    /**
     * 一次上传的最大上传文件数量
     * @var unknown
     */
    public $_maxFileCount = 20;
    /**
     * 一次上传的最小上传文件数量
     * @var unknown
     */
    public $_minFileCount = 1;
    
    public function run(){
        parent::run();
        $this->renderWidget();
    }
    
    public function renderWidget(){
        
        if($this->_multiple === true){
            $this->options['multiple'] = true;
        }
        
        if($this->hasModel()){
            $input = Html::activeFileInput($this->model, $this->attribute, $this->options);
        }else{
            $input = Html::fileInput($this->name, null, $this->options);
        }
        
        $this->renderAsset();
        echo $input;
    }
    
    public function renderAsset(){
        $view = $this->getView();
        
        FileInputAsset::register($view);
        
        $options = [
            'uploadUrl' => $this->_action,
            'uploadExtraData' => $this->_data,
            'language' => $this->_language,
            'msgPlaceholder' => $this->_placeholder,
            'mainClass' => $this->_class,
            'showPreview' => $this->_showPreview,
            'previewFileType' => $this->_previewType,
            'allowedFileExtensions' => $this->_extensions,
            'minFileCount' => $this->_minFileCount,
            'maxFileCount' => $this->_maxFileCount
        ];
        
        if(!empty($this->_preview) && is_array($this->_preview)){
            $_result = $this->generatePreview($this->_preview);
            
            if(!empty($_result['url'])){
                $options['initialPreview'] = $_result['url'];
                $options['initialPreviewConfig'] = $_result['config'];
                $options['initialPreviewAsData'] = true;
                $options['overwriteInitial'] = false;
            }
        }
        
        if(!empty($this->_options)){
            $options = $this->_optionsMerge === true ? array_merge($options, $this->_options) : $this->_options;
        }
        
        $js = <<<JS
            $(function(){
                $('#{$this->options['id']}').fileinput({$this->jsonEncode($options)});
        	});
JS;
        $view->registerJs($js, $view::POS_END);
    }
    
    private function jsonEncode($array){
        return json::encode($array);
    }
    
    private function generatePreview($preview = []){
        $_url = $_config = [];
        if(!empty($preview) && is_array($preview)){
            foreach($preview as $key => $val){
                if(!empty($val['url'])){
                    $_url[] = $val['url'];
                    $_tmp = [
                        'key' => isset($val['key']) ? $val['key'] : (isset($val['id']) ? $val['id'] : $key),
                        'caption' => isset($val['caption']) ? $val['caption'] : (isset($val['name']) ? $val['name'] : ''),
                        'url' => isset($val['delete']) ? $val['delete'] : '',
                        'downloadUrl' => isset($val['download']) && $val['download'] === true ? $val['url'] : false,
                    ];
                    if(isset($val['type'])){
                        $_tmp['type'] = $val['type'];
                    }
                    if(isset($val['filetype'])){
                        $_tmp['filetype'] = $val['filetype'];
                    }
                    if(isset($val['data'])){
                        $_tmp['extra'] = $val['data'];
                    }
                    if(isset($val['size'])){
                        $_tmp['size'] = $val['size'];
                    }
                    $_config[] = $_tmp;
                }
            }
        }
        
        return ['url' => $_url, 'config' => $_config];
    }
    
}

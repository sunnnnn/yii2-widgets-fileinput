<?php
namespace sunnnnn\fileinput; 

use yii\helpers\Html; 
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
     * 纯净版，为true时，所有参数都需要自定义
     * @var boolean
     */
    public $_pure = false;
    /**
     * 展示预览模式
     * @var boolean
     */
    public $_modeView = false;
    /**
     * form表单上传模式
     * @var boolean
     */
    public $_modeForm = false;
    /**
     * ajax上传模式
     * @var boolean
     */
    public $_modeAjax = true;
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
    public $_placeholder = '';
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
     * icon  ,  '_previewType' => 'other'
     * @var array
     */
    public $_previewIcons = [
        'doc' => '<i class="fa fa-file-word-o text-primary"></i>',
        'docx' => '<i class="fa fa-file-word-o text-primary"></i>',
        'xls' => '<i class="fa fa-file-excel-o text-success"></i>',
        'xlsx' => '<i class="fa fa-file-excel-o text-success"></i>',
        'txt' => '<i class="fa fa-file-text-o text-info"></i>'
    ];
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
    public $_previewAsData = true;
    public $_previewOverwrite= false;
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
        if(in_array($this->_language, ['zh', 'zh-cn', 'zh-CN'])){
            $this->_language = 'zh';
        }
        $this->renderWidget();
    }
    
    public function renderWidget(){
        
        if($this->_multiple === true && $this->_pure === false){
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
        
        if($this->_pure === false){
            
            if($this->_modeView === true){
                $options = [
                    'uploadUrl' => null,
                    'showClose' => false,
                    'showCaption' => false,
                    'showBrowse' => false,
                    'showUpload' => false,
                    'showRemove' => false,
                    'language' => $this->_language,
                    'mainClass' => $this->_class,
                    'showPreview' => $this->_showPreview,
                    'previewFileType' => $this->_previewType,
                    'initialPreviewAsData' => $this->_previewAsData,
                    'previewFileIconSettings' => $this->_previewIcons
                ];
            }elseif($this->_modeForm === true){
                $options = [
                    'showUpload' => false,
                    'language' => $this->_language,
                    'msgPlaceholder' => $this->_placeholder,
                    'mainClass' => $this->_class,
                    'showPreview' => $this->_showPreview,
                    'previewFileType' => $this->_previewType,
                    'allowedFileExtensions' => $this->_extensions,
                    'initialPreviewAsData' => $this->_previewAsData,
                    'previewFileIconSettings' => $this->_previewIcons
                ];
                
            }elseif($this->_modeAjax === true){
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
                    'maxFileCount' => $this->_maxFileCount,
                    'initialPreviewAsData' => $this->_previewAsData,
                    'overwriteInitial' => $this->_previewOverwrite,
                    'previewFileIconSettings' => $this->_previewIcons
                ];
            }else{
                $options = [];
            }
            
            if(!empty($this->_preview) && is_array($this->_preview)){
                $_result = FileHelper::generatePreview($this->_preview);
                
                if(!empty($_result['url'])){
                    $options['initialPreview'] = $_result['url'];
                    $options['initialPreviewConfig'] = $_result['config'];
                }
            }
        }else{
            $options = [];
        }
        
        if(!empty($this->_options)){
            $options = $this->_optionsMerge === true ? array_merge($options, $this->_options) : $this->_options;
        }
        
        $jsonOptions = FileHelper::jsonEncode($options);
        
        $js = <<<JS
            $(function(){
                $('#{$this->options['id']}').fileinput({$jsonOptions});
        	});
JS;
        $view->registerJs($js, $view::POS_END);
    }
    
}
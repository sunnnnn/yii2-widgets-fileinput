<?php
namespace sunnnnn\fileinput;

use yii\helpers\Json;

class FileHelper{
    
    public static function generatePreview($preview = []){
        $_url = $_config = [];
        if(!empty($preview) && is_array($preview)){
            if (count($preview) == count($preview, 1)) {
                $preview = [$preview];
            }
            
            foreach($preview as $key => $val){
                if(!empty($val['url'])){
                    $_url[] = $val['url'];
                    $_tmp = [
                        'key' => isset($val['key']) ? $val['key'] : (isset($val['id']) ? $val['id'] : $key),
                        'caption' => isset($val['caption']) ? $val['caption'] : (isset($val['name']) ? $val['name'] : ''),
                        'url' => isset($val['delete']) ? $val['delete'] : false,
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
    
    public static function generatePreviewForReturn($data, $json = false){
        $preview = self::generatePreview($data);
        
        $return = [
            'initialPreview' => $preview['url'],
            'initialPreviewConfig' => $preview['config'],
            'append' => true
        ];
        
        return $json === true ? json::encode($return) : $return;
    }
    
    public static function jsonEncode($array){
        return json::encode($array);
    }
}
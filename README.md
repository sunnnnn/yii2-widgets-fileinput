# yii2-widgets-fileinput


## 安装

    composer require sunnnnn/yii2-widgets-fileinput

## 使用 ##

 - 在ActiveForm中

```php
<?= $form->field($model, 'file')->widget(\sunnnnn\fileinput\FileInput::className()); ?>
```

 - 不使用ActiveForm
 
```php
\sunnnnn\fileinput\FileInput::widget([
    'name' => 'file',
]);
```

 - 相关参数
 
```php
<?= $form->field($model, 'file')->widget(\sunnnnn\fileinput\FileInput::className(), [
    '_action' => Url::to(['/user/file-upload']),  //上传的action
    '_placeholder' => '占位符',
    '_multiple' => true,  //是否支持多文件上传，默认true
    '_extensions' => ['jpg', 'png', 'gif'],  //限定上传文件后缀名
    '_preview' => [  //初始化预览文件
        [
            'url' => 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/631px-FullMoon2010.jpg', //文件链接
            'name' => '月球',  //文件名称
            'id' => 1,  //文件主键，主要用于删除操作
            'delete' => Url::to(['/user/file-delete']), //删除文件的action
            'download' => true, //是否显示下载文件按钮， 默认false
            'type' => 'image',  //文件类型， 可不填。默认image
            'size' => 13575,  //文件大小，可不填
            'data' => ['aaa' => '123']  //附加数据，删除操作传入后台，可不填
        ],
        [
            'url' => 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Earth_Eastern_Hemisphere.jpg/600px-Earth_Eastern_Hemisphere.jpg',
            'caotion' => '地球',  //等同于name
            'key' => 2,  //等同于id
            'delete' => Url::to(['/user/file-delete'])
        ]
    ],
    '_options' => [],  //原生配置

]); ?>
```

> [查看原生配置文档][1]


  [1]: http://plugins.krajee.com/file-input/plugin-options
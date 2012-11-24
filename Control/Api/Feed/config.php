<?php
/**
 * 使用4为整数的编码表示动态的类型和相应的操作
 * 注明：1. 可以方便的通过key映射到模板，
 *       2. 类型的和操作之间的映射是可控制的；
 *       
 *       
 * -----------考虑在其他实体的API或者M层调用的时候默认触发对应的动态的添加操作；方便格式的统一和调用的一致性；
 */
return array(
    'person_feed_settings' => array(
        '说说' => '{*add_account*}发表了:{*feed_content*}',
        '日志' => '{*add_account*}发表了日志:{*feed_content*}',
        '相册' => '{*add_accout*}更新了相册',
        '留言板' => '{*add_account*}发表了留言:{*feed_content*}',
    ),
    
    'class_feed_settings' => array(
        
    
    
    ),
    
    'feed_templates' => array(
        '说说:add'     => '{*add_account*}发表了:{*feed_content*}',
    	'说说:upd'     => '{*add_account*}发表了:{*feed_content*}',
    	'说说:del'  => '{*add_account*}发表了:{*feed_content*}',
    
        '日志:add' => '{*add_account*}发表了日志:{*feed_content*}',
    	'日志:upd' => '{*add_account*}发表了日志:{*feed_content*}',
    	'日志:del' => '{*add_account*}发表了日志:{*feed_content*}',
    
        //'相册' => '{*add_accout*}更新了相册',
        //'留言板' => '{*add_account*}发表了留言:{*feed_content*}',
    )

);
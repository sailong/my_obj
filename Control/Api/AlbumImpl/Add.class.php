<?php
class Add {
    
    public function by_class($datas) {
        if(empty($datas) || !is_array($datas)) {
            return false;
        }
        
        $mAlbum = ClsFactory::Create('@.Model.Album.mAlbum');
        
        $rs = $mAlbum->addAlbum($datas, true);
        
        return $rs;
    }
    
    
}
<?php
import('@.Control.Sns.ClassHomework.Ext.HomeworkAccess.HomeworkAccessBase');
class HomeworkFamily extends HomeworkAccessBase {
    
    public function getUserAccessList() {
        return array(
            'scan_view' => true,
            'is_family' => true,
        );
    }
    
    protected function isClassAdmin() {
        return false;
    }
    
}
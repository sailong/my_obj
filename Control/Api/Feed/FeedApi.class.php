<?php
class FeedApi extends ApiController {
    
    public function index() {
        
        exit;
        $redis = new rBase();
        
        echo 'here11';
        
        $redis->hMset('user', array('name' => 'anlicheng', 'age' => 10));
       // dump($redis->hGetAll('user'));
        
        echo 'call me!';
        
    }
    
}



























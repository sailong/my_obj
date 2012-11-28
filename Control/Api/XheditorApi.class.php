<?php
class XheditorApi extends AppendIterator{
    function upload() {
        import('@.Control.Api.XheditorImpl.Upload');
        $uploadobj = new Upload();
        $uploadobj->uploadfun();
    }
}
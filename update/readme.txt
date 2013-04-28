
从旧版本升级到wmw3.0改版步骤，看文件名的顺序
1. 执行 1-updateToAriesOldTable.sql: mysql -u xxx -pxxx wmw_aries < 1-updateToAriesOldTable.sql
2. 执行 2-updateToAriesNewTable.sql: mysql -u xxx -pxxx wmw_areis < 2-updateToAriesNewTable.sql
3. 执行老数据导入3-importOldDate.sql :  mysql -u xxx -pxxx wmw_areis < 3-importOldDate.sql
4. 执行日志数据导入4-importBlog.php :  /usr/bin/php  4-importBlog.php
5. 执行相册数据导入5-importAlbum.php： /usr/bin/php  5-importAlbum.php
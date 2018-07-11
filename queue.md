 调试模式下的使用:
 php think queue:work --queue saveLoginLog --tries 5 --sleep 3 --daemon
 
 生产环境中的使用:
 nohup php think queue:work --queue saveLoginLog --tries 5 --sleep 3 --daemon 1>queue.log 2>&1 &

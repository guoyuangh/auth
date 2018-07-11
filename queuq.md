 php think queue:work --queue saveLoginLog --tries 5 --sleep 3 --daemon
 
 nohup php think queue:work --queue saveLoginLog --tries 5 --sleep 3 --daemon 1>queue.log 2>&1 &
 
 
 
 
 git remote add origin https://github.com/lihaotian0607/auth.git
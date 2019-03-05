
场景一：

初始化：git init

用户：git config  --global user.name 'xiaolitongzhi'

邮箱：git config  --global user.email 'xiaolitongzhi1996@126.com'

远程仓库： git remote add url https://github.com/xiaolitongzhi/tp5.git

关联推送：git push -u url master:master 

推送代码：git push  url master:master





场景二：

克隆：git clone https://github.com/xiaolitongzhi/tp5.git

配置用户名：git config  --global user.name 'xiaolitongzhi'

配置邮箱：git config  --global user.email 'xiaolitongzhi1996@126.com'
远程仓库： git remote add url https://github.com/xiaolitongzhi/tp5.git

更新composer:composer install

添加切换分支：git checkout -b home

推送代码：git push url home:home

拉取代码：git pull url master





合并分支：

查看分支：git fetch

确认合并：git merge url/home 【空白文件指明合并之理由】
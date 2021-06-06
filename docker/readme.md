# 使用步骤
## 启动容器
```bash
docker run -dti --restart=always --name am -p 10021:10021 -v /root/coding/easyswoole:/easyswoole registry.cn-hangzhou.aliyuncs.com/hdtopku/easyswoole php easyswoole start

```

## 一、创建项目

```bash
mkdir  easyswoole && cd easyswoole
git clone git@github.com:hdtopku/easyswoole_docker.git docker
# 拉取官方镜像（推荐）
docker pull easyswoole/easyswoole3
# 或者直接拉取已构建的镜像
docker pull registry.cn-hangzhou.aliyuncs.com/hdtopku/easyswoole:v3.3.1
docker tag registry.cn-hangzhou.aliyuncs.com/hdtopku/easyswoole:v3.3.1 easyswoole/easyswoole3
# 再或者自行构建镜像
docker build -t hdtopku/easyswoole:v3.3.1 docker/.
docker tag hdtopku/easyswoole:v3.3.1 easyswoole/easyswoole3
```
## 二、启动 （ 确保有运行权限）

```bash
### 1. 启动

sh docker/container.sh

### 访问 测试

curl http://127.0.0.1:9501

```

## 三、 常用命令
```bash
### 2. 重启

sh docker/container.sh restart

### 3. 关闭
sh docker/container.sh stop

### 4. 进入容器bash

sh docker/container.sh shell

```


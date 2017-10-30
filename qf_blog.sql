/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : qf_blog

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-10-30 14:01:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for qf_admin
-- ----------------------------
DROP TABLE IF EXISTS `qf_admin`;
CREATE TABLE `qf_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faceurl` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(5) DEFAULT NULL,
  `remark` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `last_login_ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login_brand` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_admin` int(1) DEFAULT '0',
  `is_active` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of qf_admin
-- ----------------------------
INSERT INTO `qf_admin` VALUES ('50', '9dabcd12aabd6d4b293570bd7b04b484', 'admin', 'https://www.sunmale.cn/static/common/images/face/8.jpg', '', '1', '超级管理员，拥有全部权限', '1505286093', '1508920846', null, null, null, '1', '1');

-- ----------------------------
-- Table structure for qf_article
-- ----------------------------
DROP TABLE IF EXISTS `qf_article`;
CREATE TABLE `qf_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` text COLLATE utf8mb4_unicode_ci,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_reprint` int(3) NOT NULL DEFAULT '0',
  `typeid` int(3) NOT NULL,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reprint_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_time` int(20) NOT NULL,
  `update_time` int(20) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '0',
  `view` int(11) NOT NULL DEFAULT '0',
  `common` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `like` int(11) DEFAULT '0',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `update_time` (`update_time`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of qf_article
-- ----------------------------
INSERT INTO `qf_article` VALUES ('1', 'Linux搭建svn服务器', '1,2', '<p><span style=\"font-size: 14px;\"><b>什么是SVN</b></span></p><p>简单的说，您可以把SVN当成您的备份服务器，更好的是，他可以帮您记住每次上传到这个服务器的档案内容。并且自动的赋予每次的变更一个版本。 &nbsp;</p><p>本篇文章就是以 linux centos为例讲解Linux下搭建svn服务器</p><p><span style=\"color: rgb(34, 34, 34); font-family: Consolas, &quot;Lucida Console&quot;, &quot;Courier New&quot;, monospace; white-space: pre-wrap; background-color: rgb(255, 255, 255); font-size: 14px;\"><strong><br></strong></span></p><p><b>Linux下安装SVN服务器&nbsp;&nbsp;</b><br></p><p>&nbsp;1.下载安装svn &nbsp;linux命令行直接输入 &nbsp;# yum install subversion&nbsp;&nbsp;<br></p><p><font color=\"#3f3f3f\" face=\"Consolas, Lucida Console, Courier New, monospace\"><span style=\"white-space: pre-wrap;\">&nbsp;</span></font>2.创建svn文件保存目录还有 &nbsp; &nbsp;# mkdir /home/svn<br></p><p><font color=\"#222222\" face=\"Consolas, Lucida Console, Courier New, monospace\"><span style=\"white-space: pre-wrap;\">&nbsp;</span></font>3.新建一个资源版本库 &nbsp; &nbsp; # svnadmin create /home/svn/project</p><p><strong><br></strong></p><p><b>目录用途说明：&nbsp;&nbsp;</b></p><p>hooks目录：放置hook脚本文件的目录&nbsp;&nbsp;</p><p>locks目录：用来放置subversion的db锁文件和db_logs锁文件的目录，用来追踪存取文件库的客户端&nbsp;</p><p>&nbsp;format文件：是一个文本文件，里面只放了一个整数，表示当前文件库配置的版本号<br></p><p>conf目录：是这个仓库的配置文件（仓库的用户访问账号、权限等）</p><p><b><br></b></p><p><b>配置conf文件下的三个文件 &nbsp;</b><br></p><p>1 .# vi /home/svn/project/conf/svnserve.conf &nbsp; 打开并编辑这个文件 &nbsp;把 &nbsp;password-db = passwd &nbsp;跟 &nbsp;authz-db = authz &nbsp;前面的#还有空格去除。 &nbsp;&nbsp;&nbsp;</p><p><img src=\"https://www.sunmale.cn/uploads/20170928/6ed0385de96cccda2dbb670a8c36193c.png\" style=\"max-width:100%;\" class=\"\"></p><p>2. # vi /home/svn/project/conf/passwd  &nbsp;这个文件是设置用户名密码的 &nbsp;</p><p><img src=\"https://www.sunmale.cn/uploads/20170928/6081a3b0e928f445836ef23b4770349c.png\" style=\"max-width:100%;\" class=\"\"></p><p>3.# vi /home/svn/project/conf/authz&nbsp;&nbsp;这个文件是设置用户权限的 r代表读取权限 &nbsp;w代表写入权限&nbsp;&nbsp;<br></p><p><img src=\"https://www.sunmale.cn/uploads/20170928/f728b0df1ec1ed467abee3ba02a84a2f.png\" style=\"max-width:100%;\" class=\"\"></p><p><b>启动SVN服务器 &nbsp;</b></p><p># &nbsp;svnserve -d &nbsp;-r /home/svn/ &nbsp; &nbsp; &nbsp;监听这个svn版本库的根目录<strong style=\"color: rgb(63, 63, 63); font-family: 宋体, SimSun; font-size: 18px;\"><span style=\"white-space: pre-wrap;\">&nbsp;&nbsp;</span></strong><br></p><p>注意： 对权限配置文件的修改立即生效，不必重启svn。 因为svn默认端口是 &nbsp;3690 &nbsp;使用客户端&nbsp;，连接必须确保防火墙是允许此端口的。 如果不允许 &nbsp;添加防火墙允许访问规则<br></p><p><span style=\"background-color: rgb(248, 248, 248); color: rgb(61, 70, 77); font-family: &quot;Pingfang SC&quot;, STHeiti, &quot;Lantinghei SC&quot;, &quot;Open Sans&quot;, Arial, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, &quot;WenQuanYi Micro Hei&quot;, SimSun, sans-serif; white-space: pre-wrap;\">           </span># iptables -A INPUT -p tcp --dport 3690 &nbsp;-j ACCEPT添加规则</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;# service iptables save 保存规则&nbsp;&nbsp;<br></p><p><span style=\"white-space: pre-wrap; box-sizing: inherit; -webkit-tap-highlight-color: transparent; color: rgb(61, 70, 77); background-color: rgb(248, 248, 248); font-family: 宋体, SimSun;\"><b><br></b></span></p><p><b>windows 本地使用客户端连接SVN</b>&nbsp;&nbsp;<br></p><p>1. 下载&nbsp;SVN客户端：TortoiseSVN，官网下载：<a href=\"http://tortoisesvn.net/downloads.html\">http://tortoisesvn.net/downloads.html</a>&nbsp;&nbsp;<strong><span style=\"white-space: pre-wrap; box-sizing: inherit; -webkit-tap-highlight-color: transparent; color: rgb(61, 70, 77); background-color: rgb(248, 248, 248); font-family: 宋体, SimSun;\"><br></span></strong></p><p>2.安装好客户端后 &nbsp;鼠标右键 &nbsp;svn检出&nbsp;输入版本库的地址 &nbsp;svn://ip地址/project &nbsp;， &nbsp;这时候本地就下载一个带 .svn的文件夹 &nbsp;默认跟 project名称一样.</p><p>3.本地修改数据 先更新文件 &nbsp;在提交到服务器版本库中。&nbsp;&nbsp;<br></p><p>这样一个SVN服务就搭建完成了 &nbsp; 可以轻松管理你的项目<span style=\"color: rgb(89, 89, 89);\">&nbsp;</span></p><p>&nbsp; SVN学习视频资料 &nbsp;Windows下如何搭建SVN服务器 &nbsp;<span style=\"color: rgb(89, 89, 89);\">&nbsp;&nbsp;</span>&nbsp; 网盘地址：&nbsp;<font color=\"#c24f4a\"><a href=\"http://pan.baidu.com/s/1kUIR5dH\">&nbsp;</a><font color=\"#c24f4a\"><a href=\"http://pan.baidu.com/s/1kUIR5dH\" target=\"_blank\">Windows下如何使用SVN</a>&nbsp;</font>&nbsp;</font></p><p><span style=\"color: rgb(89, 89, 89);\">&nbsp;&nbsp;</span></p><p><br></p>', '1', '30', '您可以把SVN当成您的备份服务器，更好的是，他可以帮您记住每次上传到这个服务器的档案内容。并且自动的赋予每次的变更一个版本。  本篇文章就是以 linux centos为例讲解Linux下搭建svn服务器 ', '晴枫', '', '1481389355', '1508920156', '1', '792', '0', '50', '0', '2016-12');
INSERT INTO `qf_article` VALUES ('2', 'Linux下使用Cron定时执行脚本', '1', '<p><span style=\"font-size: 16px; color: rgb(63, 63, 63); font-family: arial, helvetica, sans-serif;\">我是使用 Linux centos7.0讲解的。</span></p><p><span style=\"font-size: 16px; color: rgb(63, 63, 63); font-family: arial, helvetica, sans-serif;\"><br></span></p><p><span style=\"color: rgb(38, 38, 38);\">因为最近公司项目需求 需要写一个脚本每天自动去获取数据。因为php本身的 sleep()函数定时不太好，</span></p><p><br></p><p><span style=\"color: rgb(38, 38, 38);\">所以就用了 Linux系统自带的定时器 cron 。</span></p><p><span style=\"color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"color:#262626\">1.安装cron <span style=\"font-size: 16px;\">&nbsp;#&nbsp;</span></span><span style=\"font-family: 宋体; text-indent: 28px; color: rgb(51, 51, 51); font-size: 16px;\">yum install crontabs</span><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\">（</span><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(255, 0, 0);\">我执行这一步的时候，提示我已经安装了，不知道啥时候安装的</span><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\">）</span></p><p><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\"><br></span></p><p><span style=\"font-family: 宋体; text-indent: 28px; color: rgb(51, 51, 51); font-size: 16px;\">2.查看安装目录 &nbsp;which crond &nbsp; 一般会是在 /usr/sbin/crond</span></p><p><span style=\"color: rgb(38, 38, 38); font-family: 宋体; text-indent: 28px;\"><br></span></p><p><span style=\"font-family: 宋体, SimSun;\">3. 使用命令<br></span></p><p><span style=\"font-family: 宋体, SimSun;\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;/usr/sbin/service crond start &nbsp; //启动服务</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br>&nbsp;/usr/sbin/service crond stop &nbsp; //关闭服务</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br>&nbsp;/usr/sbin/service crond restart &nbsp; //重启服务</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br>&nbsp;/usr/sbin/service crond reload &nbsp; //重新载入配置</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;查看crontab服务状态：service crond status</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;手动启动crontab服务：service crond start</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;查看crontab服务是否已设置为开机启动，执行命令：ntsysv</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;在CentOS系统中加入开机自动启动:chkconfig --level 35 crond on</span></p><p><span style=\"font-family: 宋体, SimSun;\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;CentOS系统 crontab命令</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;功能说明：设置计时器。</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;语法：crontab [-u &lt;用户名称&gt;][配置文件] 或 crontab [-u &lt;用户名称&gt;][-elr]</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;补充说明：cron是一个常驻服务，它提供计时器的功能，</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">让用户在特定的时间得以执行预设的指令或 程序。只要用户会编辑计时器的配置文件，</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">就可以使 用计时器的功能。其配置文件格式如下：Minute &nbsp;Hour Day Month DayOFWeek Command</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">参数：&nbsp;</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">&nbsp;-e 　编辑该用户的计时器设置。&nbsp;<br>&nbsp;-l 　列出该用户的计时器设置。&nbsp;<br>&nbsp;-r 　删除该用户的计时器设置。&nbsp;<br>&nbsp;-u&lt;用户名称&gt; 　指定要设定计时器的用户名称。</span></p><p><span style=\"font-family: 宋体, SimSun; color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\"></span></p><p style=\"font-size: 24px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2;\"><span style=\"color: rgb(38, 38, 38); font-family: 宋体, SimSun; font-size: 16px;\">4 使用方式</span></p><p style=\"font-size: 24px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2;\"><span style=\"color:#262626;font-family:宋体, SimSun\"></span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">基本格式 :<br>*　　*　　*　　*　　*　　command<br>分　时　日　月　周　命令</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">第1列表示分钟1～59 每分钟用*或者 */1表示<br>第2列表示小时1～23（0表示0点）<br>第3列表示日期1～31<br>第4列表示月份1～12<br>第5列标识号星期0～6（0表示星期天）<br>第6列要运行的命令</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">crontab文件的一些例子：</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun; color: rgb(38, 38, 38);\">30 21 * * * /usr/local/etc/rc.d/lighttpd restart</span><span style=\"font-size: 16px; font-family: 宋体, SimSun;\"><br>上面的例子表示每晚的21:30重启apache。</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">45 4 1,10,22 * * /usr/local/etc/rc.d/lighttpd restart<br>上面的例子表示每月1、10、22日的4 : 45重启apache。</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">10 1 * * 6,0 /usr/local/etc/rc.d/lighttpd restart<br>上面的例子表示每周六、周日的1 : 10重启apache。</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">0,30 18-23 * * * /usr/local/etc/rc.d/lighttpd restart<br>上面的例子表示在每天18 : 00至23 : 00之间每隔30分钟重启apache。</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">0 23 * * 6 /usr/local/etc/rc.d/lighttpd restart<br>上面的例子表示每星期六的11 : 00 pm重启apache。</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">* */1 * * * /usr/local/etc/rc.d/lighttpd restart<br>每一小时重启apache</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">* 23-7/1 * * * /usr/local/etc/rc.d/lighttpd restart<br>晚上11点到早上7点之间，每隔一小时重启apache</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">0 11 4 * mon-wed /usr/local/etc/rc.d/lighttpd restart<br>每月的4号与每周一到周三的11点重启apache</span></p><p style=\"font-size: 14px; font-variant-ligatures: normal; orphans: 2; white-space: normal; widows: 2; color: rgb(51, 51, 51); font-family: Arial; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px; font-family: 宋体, SimSun;\">0 4 1 jan * /usr/local/etc/rc.d/lighttpd restart<br>一月一号的4点重启apache</span></p><p><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\"><br></span><br></p><p><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\"><br></span></p><p><span style=\"font-family: 宋体; font-size: 14px; text-indent: 28px; color: rgb(51, 51, 51);\"><br></span></p><p><span style=\"color:#262626\"></span><br></p><p><span style=\"color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"color: rgb(38, 38, 38);\"><br></span></p><p><span style=\"font-size: 16px; color: rgb(63, 63, 63); font-family: arial, helvetica, sans-serif;\"><br></span></p><p><span style=\"font-size: 16px; color: rgb(63, 63, 63); font-family: arial, helvetica, sans-serif;\"><br></span></p><p><br></p>', '1', '30', 'Linux下使用cron定时执行脚本', '晴枫', '', '1481983401', '1508197586', '0', '479', '0', '50', '0', '2016-12');
INSERT INTO `qf_article` VALUES ('3', 'thinkphp5.0使用sqlserver数据库', '3,4', '<p><span style=\"font-size: 18px; font-family: 宋体, SimSun; box-sizing: border-box; margin: 0px; padding: 0px; color: rgb(51, 51, 51); background-color: rgb(255, 255, 255);\"><span style=\"font-family: &quot;Helvetica Neue&quot;, Helvetica, &quot;PingFang SC&quot;, 微软雅黑, Tahoma, Arial, sans-serif; font-size: 14px;\">1.首先打开ThinkPHP5的数据库配置文件，将数据库连接信息修改如下：</span>&nbsp;&nbsp;</span></p><pre><code>    \'type\'            =&gt; \'Sqlsrv\',\n    // 服务器地址\n    \'hostname\'        =&gt; \'localhost\',\n    // 数据库名\n    \'database\'        =&gt; \'\',\n    // 用户名\n    \'username\'        =&gt; \'sa\',\n    // 密码\n    \'password\'        =&gt; \'sa\',\n    // 端口\n    \'hostport\'        =&gt; \'1433\',</code></pre><p><span style=\"font-size: 18px; font-family: 宋体, SimSun; color: rgb(51, 51, 51);\"></span></p><p>上面的type类型必须改为Sqlsrv。 &nbsp;<br></p><p><br></p><p>2.下载windows提供的php支持sqlserver数据库的扩展，下载地址如下。&nbsp;&nbsp;<span style=\"color: rgb(51, 51, 51); font-family: 宋体, SimSun; font-size: 18px; background-color: rgb(255, 255, 255);\"><br></span></p><p><a href=\"https://www.microsoft.com/en-us/download/details.aspx?id=20098\">https://www.microsoft.com/en-us/download/details.aspx?id=20098</a>&nbsp;&nbsp;<br></p><p>下载跟php版本相对应的驱动版本。 &nbsp;</p><table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><th>Microsoft PHP 驱动程序版本&nbsp;&nbsp;&nbsp;</th><th>PHP 版本&nbsp;&nbsp;&nbsp;</th></tr><tr><td>&nbsp;\n\n3.2</td><td>&nbsp;\n\n5.6、5.5 和 5.4</td></tr><tr><td>&nbsp;\n\n3.1</td><td>&nbsp;5.5和5.4</td></tr><tr><td>&nbsp;\n\n3.0</td><td>&nbsp;5.4</td></tr></tbody></table><p>现在4.0支持的php版本是7.0以上了。&nbsp;&nbsp;<br></p><p><br></p><p>3.下载后安装解压到某个目录下，分别是不同版本的的驱动库。 &nbsp; &nbsp;把你需要的扩展放在php版本的扩展中。（也就是php目录的 ext文件下） &nbsp;</p><p><br></p><p>4.打开php安装目录中的php.ini，来到extension扩展库区，向里面加入如下内容，因为我的php版本是5.6。大家根据自己的版本进行修改即可。 &nbsp;<br></p><p>&nbsp; &nbsp; &nbsp;extension=php_sqlsrv_56_nts.dll &nbsp;<br></p><p>&nbsp; &nbsp; extension=php_pdo_sqlsrv_56_nts.dll&nbsp;&nbsp;<br></p><p>这个时候使用php的 phpinfo()方法就能看到添加的扩展已经出现了，如果没有显示，说明配置错误，检查下自己的php版本跟操作系统。&nbsp;&nbsp;<br></p><p><br></p><p>4.第一次连接sqlserver可能会提示odbc有问题&nbsp;，网上的解决方法有很多，百度一下就可以。最简单的方法可以去下载一个sqlserver odbc驱动源，安装即可。这里没办法演示，只能遇到自己解决，我把sqlserver odbc的下载地址提供在下面。下载安装就行了。 &nbsp; &nbsp;&nbsp;<a href=\"https://www.microsoft.com/zh-cn/download/details.aspx?id=36434\">https://www.microsoft.com/zh-cn/download/details.aspx?id=36434</a>&nbsp;&nbsp;</p><p><br></p><p>5.最后直接连接使用个最简单的操作测试是否成功就行了，测试代码如下。 &nbsp;<br></p><pre><code>public function  test(){\n       $user =   Db::table(\'Test\')-&gt;select();\n       print_r($user);\n     }</code></pre><p><br></p><p>注意 &nbsp;： &nbsp;使用thinkphp5连接sqlserver数据库只能使用windows服务器。使用过程中发现只能使用 &nbsp;Db::table(\'完整表名\') &nbsp;进行数据库操作或者直接自己写原生sql语句。 &nbsp; 模型操作跟 Db::name(\'不带前缀表‘) 会出现错误。 测试使用的thinkphp5.0.4版本。</p><p><br></p>', '1', '24', 'PHP+MySQL是天生的好搭档，在ThinkPHP中，我们也是通过配置直接连上MySQL，使用起来非常便捷，近日遇到了ThinkPHP连接SQL Server的问题，而如何使用ThinkPHP连接SQL Server已是老生常谈，查了网上很多资料都不适合，最后整合了多方资料，终于弄好了，下面是具体的方法，这里使用的是phpStudy，PHP版本选择的是PHP5.6nts版本。', '晴枫', '', '1487839960', '1508922978', '1', '360', '0', '50', '0', '2017-02');
INSERT INTO `qf_article` VALUES ('4', 'phpStrom断点调试', '3', '<p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><span style=\"font-family:宋体, SimSun\"><span style=\"font-size: 24px;\">phpStudy+phpStrom+xDebug调试php代码。</span></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><span style=\"font-family: 宋体, SimSun; color: rgb(0, 0, 0); font-size: 18px;\">PHPSTORM版本 : 10.2.0</span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><span style=\"color: rgb(0, 0, 0); font-family: 宋体, SimSun; font-size: 18px;\">php版本 : 5.6.27</span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><span style=\"font-family: 宋体, SimSun; color: rgb(0, 0, 0); font-size: 18px;\">xdebug版本：2.4</span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><br></p><ol class=\" list-paddingleft-2\" style=\"list-style-type: decimal;\"><li><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\">打开 php.ini 中的 zend_extension=\"D:\\software\\php\\php-5.6.27-nts\\ext\\php_xdebug.dll\"扩展，打开phpinfo()显示出如下图。</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><img src=\"/public/uploads/ueditor/images/20170425/1493114294602171.png\" title=\"1493114294602171.png\" alt=\"QQ截图20170425175522.png\"></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><br></p></li></ol><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\">2.&nbsp;&nbsp;&nbsp;下面是客户端调试，打开</span><a target=\"_blank\" href=\"http://www.chenxuanyi.cn/xampp-phpstorm-xdebug.html\" title=\"XAMPP环境下用phpStorm+XDebug进行断点调试的配置\" style=\"color: rgb(106, 57, 6); text-decoration: underline; font-size: 16px;\"><span style=\"font-size: 16px;\">phpStorm</span></a><span style=\"font-size: 16px;\">，进入File&gt;Settings&gt;PHP&gt;Servers，这里要填写服务器端的相关信息，name填localhost，host填localhost，port填80，debugger选</span><a target=\"_blank\" href=\"http://www.chenxuanyi.cn/xampp-phpstorm-xdebug.html\" title=\"XAMPP环境下用phpStorm+XDebug进行断点调试的配置\" style=\"color: rgb(106, 57, 6); text-decoration: underline; font-size: 16px;\"><span style=\"font-size: 16px;\">XDebug</span></a></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\">3.&nbsp;&nbsp;&nbsp;进入File&gt;Settings&gt;PHP&gt;Debug，看到</span><a target=\"_blank\" href=\"http://www.chenxuanyi.cn/xampp-phpstorm-xdebug.html\" title=\"XAMPP环境下用phpStorm+XDebug进行断点调试的配置\" style=\"color: rgb(106, 57, 6); text-decoration: underline; font-size: 16px;\"><span style=\"font-size: 16px;\">XDebug</span></a><span style=\"font-size: 16px;\">选项卡，port填9000，其他默认</span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\">4.&nbsp;&nbsp;&nbsp;进入File&gt;Settings&gt;PHP&gt;Debug&gt;DBGpProxy，IDE key 填&nbsp;PHPSTORM，host 填localhost，port 填80</span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\">5.&nbsp;&nbsp;&nbsp;点OK退出设置。</span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><br></span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><img src=\"/public/uploads/ueditor/images/20170425/1493118636364436.png\" title=\"1493118636364436.png\" alt=\"0.png\"></span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><br></span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><img src=\"/public/uploads/ueditor/images/20170425/1493118420922148.png\" title=\"1493118420922148.png\" alt=\"1.png\" width=\"300\" height=\"405\"></span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><img src=\"/public/uploads/ueditor/images/20170425/1493118460244391.png\" title=\"1493118460244391.png\" alt=\"2.png\"></span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><br></span></p><p style=\"color: rgb(54, 46, 43); font-family: Arial; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"font-size: 16px;\"><img src=\"/public/uploads/ueditor/images/20170425/1493118520698195.png\" title=\"1493118520698195.png\" alt=\"3.png\"></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><img src=\"/public/uploads/ueditor/images/20170425/1493118542454331.png\" title=\"1493118542454331.png\" alt=\"4.png\"></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><br></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; color: rgb(85, 85, 85); font-family: &quot;microsoft yahei&quot;; font-size: 15px; white-space: normal; background-color: rgb(255, 255, 255); line-height: 2em;\"><img src=\"/public/uploads/ueditor/images/20170425/1493118563605619.png\" title=\"1493118563605619.png\" alt=\"5.png\"></p><p><br></p><p><br></p><p><br></p><p><br></p>', '1', '24', 'phpStudy+phpStrom+xDebug调试php代码。', '晴枫', '', '1493118575', '1507621465', '0', '83', '0', '50', '0', '2017-04');

-- ----------------------------
-- Table structure for qf_article_tag
-- ----------------------------
DROP TABLE IF EXISTS `qf_article_tag`;
CREATE TABLE `qf_article_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `css` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of qf_article_tag
-- ----------------------------
INSERT INTO `qf_article_tag` VALUES ('1', 'Linux', '1', null, null, 'layui-bg-orange');
INSERT INTO `qf_article_tag` VALUES ('2', 'svn', '1', null, null, 'layui-badge');
INSERT INTO `qf_article_tag` VALUES ('3', 'tp5', '1', null, null, 'layui-bg-blue');
INSERT INTO `qf_article_tag` VALUES ('4', 'sqlserver', '1', null, null, 'layui-bg-black');
INSERT INTO `qf_article_tag` VALUES ('5', '快捷登录', '1', null, null, 'layui-badge');
INSERT INTO `qf_article_tag` VALUES ('6', 'redis', '1', null, null, 'layui-bg-orange');
INSERT INTO `qf_article_tag` VALUES ('7', 'lnmp', '1', null, null, 'layui-bg-black');
INSERT INTO `qf_article_tag` VALUES ('8', 'nginx', '1', null, null, 'layui-bg-blue');
INSERT INTO `qf_article_tag` VALUES ('9', 'mysql', '1', null, null, 'layui-bg-orange');

-- ----------------------------
-- Table structure for qf_article_type
-- ----------------------------
DROP TABLE IF EXISTS `qf_article_type`;
CREATE TABLE `qf_article_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pid` int(5) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  `create_time` int(20) DEFAULT NULL,
  `update_time` int(20) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `remark` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of qf_article_type
-- ----------------------------
INSERT INTO `qf_article_type` VALUES ('12', '编程', null, '0', '1', '1479440963', '1505731718', '0', '日常遇到的程序技术');
INSERT INTO `qf_article_type` VALUES ('13', '生活', null, '0', '1', '1479441239', '1505731444', '10', '生活的记录1');
INSERT INTO `qf_article_type` VALUES ('18', '心情', null, '13', '1', '1479441317', '1505731670', '0', '');
INSERT INTO `qf_article_type` VALUES ('19', '随笔', null, '13', '1', '1479441327', null, null, '');
INSERT INTO `qf_article_type` VALUES ('20', '摄影', null, '13', '1', '1479441342', '1505731686', '20', '生活');
INSERT INTO `qf_article_type` VALUES ('24', 'PHP', null, '12', '1', '1479458575', '1505731604', '0', 'PHP的文章类容');
INSERT INTO `qf_article_type` VALUES ('25', '数据库', null, '12', '1', '1479458613', '1508327122', '20', '数据库的文章');
INSERT INTO `qf_article_type` VALUES ('27', '前端', null, '12', '1', '1479779792', '1508327183', '30', '前端js结束');
INSERT INTO `qf_article_type` VALUES ('28', 'Java', null, '12', '1', '1479779811', '1505731630', '10', 'Java技术');
INSERT INTO `qf_article_type` VALUES ('30', 'Linux', null, '12', '1', '1480922965', '1505731654', '40', 'Linux操作系统方面的知识');

-- ----------------------------
-- Table structure for qf_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `qf_auth_group`;
CREATE TABLE `qf_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qf_auth_group
-- ----------------------------
INSERT INTO `qf_auth_group` VALUES ('99', '文章管理员', '1', '', '1505303985', '1506497076');
INSERT INTO `qf_auth_group` VALUES ('100', '测试账号组', '1', '103,113,104,105,106,134,107,110,111,112,108,109', '1508984701', '1508991882');

-- ----------------------------
-- Table structure for qf_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `qf_auth_group_access`;
CREATE TABLE `qf_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qf_auth_group_access
-- ----------------------------

-- ----------------------------
-- Table structure for qf_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `qf_auth_rule`;
CREATE TABLE `qf_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `css` varchar(20) NOT NULL COMMENT '样式',
  `condition` char(100) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL,
  `remark` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qf_auth_rule
-- ----------------------------
INSERT INTO `qf_auth_rule` VALUES ('103', '#', '系统管理', '1', '1', '&#xe620;', '', '0', '0', '1505303639', '1505303639', '系统管理');
INSERT INTO `qf_auth_rule` VALUES ('104', '#', '权限管理', '1', '1', '&#xe631;', '', '0', '10', '1505303666', '1505303666', '权限管理');
INSERT INTO `qf_auth_rule` VALUES ('105', 'admin/Admin/index', '用户管理', '1', '1', '&#xe612;', '', '104', '0', '1505303858', '1505303858', '用户管理');
INSERT INTO `qf_auth_rule` VALUES ('106', 'admin/Group/index', '用户组管理', '1', '1', '&#xe613;', '', '104', '10', '1505303892', '1505303892', '用户组管理');
INSERT INTO `qf_auth_rule` VALUES ('107', 'admin/Rule/index', '菜单管理', '1', '1', '&#xe671;', '', '104', '20', '1505303916', '1505303916', '菜单管理');
INSERT INTO `qf_auth_rule` VALUES ('108', '#', '微语管理', '1', '1', '', '', '0', '100', '1505313545', '1505313686', '微语管理');
INSERT INTO `qf_auth_rule` VALUES ('109', 'admin/said/index', '我的微语言', '1', '1', '', '', '108', '0', '1505313673', '1505572104', '我的微语言');
INSERT INTO `qf_auth_rule` VALUES ('110', '#', '文章管理', '1', '1', '&#xe705;', '', '0', '30', '1505379698', '1505379698', '文章管理');
INSERT INTO `qf_auth_rule` VALUES ('111', 'admin/Articletype/index', '文章分类', '1', '1', '&#xe62a;', '', '110', '0', '1505379931', '1505379931', '文章分类');
INSERT INTO `qf_auth_rule` VALUES ('112', 'admin/Article/index', '文章列表', '1', '1', '', '', '110', '10', '1505807869', '1505808557', '文章列表');
INSERT INTO `qf_auth_rule` VALUES ('113', 'admin/System/index', '网站设置', '1', '1', '', '', '103', '0', '1506410839', '1506430547', '网站基本设置');
INSERT INTO `qf_auth_rule` VALUES ('114', 'admin/System/add_base', '添加基本配置', '1', '1', '', '', '113', '0', '1508985329', '1508985329', '添加基本配置');
INSERT INTO `qf_auth_rule` VALUES ('115', 'admin/Admin/add', '新增用户', '1', '1', '', '', '105', '0', '1508985396', '1508985396', '新增管理用户');
INSERT INTO `qf_auth_rule` VALUES ('116', 'admin/Admin/edit', '修改用户', '1', '1', '', '', '105', '10', '1508985447', '1508985447', '修改用户信息');
INSERT INTO `qf_auth_rule` VALUES ('117', 'admin/Admin/delete', '删除用户', '1', '1', '', '', '105', '20', '1508985468', '1508985468', '删除用户信息');
INSERT INTO `qf_auth_rule` VALUES ('118', 'admin/Group/add', '新增用户组', '1', '1', '', '', '106', '0', '1508985525', '1508985525', '新增用户组');
INSERT INTO `qf_auth_rule` VALUES ('119', 'admin/Group/edit', '修改用户组', '1', '1', '', '', '106', '10', '1508985550', '1508985550', '修改用户组');
INSERT INTO `qf_auth_rule` VALUES ('120', 'admin/Group/delete', '删除用户组', '1', '1', '', '', '106', '20', '1508985582', '1508985582', '删除用户组');
INSERT INTO `qf_auth_rule` VALUES ('121', 'admin/Group/rule_group', '分配用户组权限', '1', '1', '', '', '106', '30', '1508985652', '1508985652', '分配用户组权限');
INSERT INTO `qf_auth_rule` VALUES ('122', 'admin/Rule/add', '新增功能菜单', '1', '1', '', '', '107', '0', '1508985704', '1508985704', '新增功能菜单');
INSERT INTO `qf_auth_rule` VALUES ('123', 'admin/Rule/edit', '修改功能菜单', '1', '1', '', '', '107', '10', '1508985726', '1508985726', '修改功能菜单');
INSERT INTO `qf_auth_rule` VALUES ('124', 'admin/Rule/delete', '删除功能菜单', '1', '1', '', '', '107', '30', '1508985759', '1508985772', '删除功能菜单');
INSERT INTO `qf_auth_rule` VALUES ('125', 'admin/Articletype/add', '新增文章分类', '1', '1', '', '', '111', '0', '1508986744', '1508986744', '新增文章分类');
INSERT INTO `qf_auth_rule` VALUES ('126', 'admin/Articletype/edit', '修改文章分类', '1', '1', '', '', '111', '10', '1508986766', '1508986766', '修改文章分类');
INSERT INTO `qf_auth_rule` VALUES ('127', 'admin/Articletype/delete', '删除文章分类', '1', '1', '', '', '111', '20', '1508986786', '1508986786', '删除文章分类');
INSERT INTO `qf_auth_rule` VALUES ('128', 'admin/Article/add', '新增文章', '1', '1', '', '', '112', '0', '1508986839', '1508986839', '新增文章');
INSERT INTO `qf_auth_rule` VALUES ('129', 'admin/Article/edit', '修改文章', '1', '1', '', '', '112', '10', '1508986857', '1508986857', '修改文章');
INSERT INTO `qf_auth_rule` VALUES ('130', 'admin/Article/delete', '删除文章', '1', '1', '', '', '112', '20', '1508986877', '1508986877', '删除文章');
INSERT INTO `qf_auth_rule` VALUES ('131', 'admin/said/add', '新增我的微语', '1', '1', '', '', '109', '0', '1508986925', '1508986925', '新增我的微语');
INSERT INTO `qf_auth_rule` VALUES ('132', 'admin/said/edit', '修改我的微语言', '1', '1', '', '', '109', '10', '1508986948', '1508986948', '修改我的微语言');
INSERT INTO `qf_auth_rule` VALUES ('133', 'admin/said/delete', '删除我的微语', '1', '1', '', '', '109', '20', '1508986974', '1508986974', '删除我的微语');
INSERT INTO `qf_auth_rule` VALUES ('134', 'admin/Admin/updatepassword', '修改密码', '1', '1', '', '', '106', '40', '1508991273', '1508991273', '修改密码');

-- ----------------------------
-- Table structure for qf_message
-- ----------------------------
DROP TABLE IF EXISTS `qf_message`;
CREATE TABLE `qf_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  `os` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_time` int(20) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  `headurl` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '1.留言。2,文章评论',
  `article_id` int(11) DEFAULT NULL COMMENT '文章自增ID,外键',
  `status` int(1) DEFAULT '1',
  `reply_nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply_id` int(11) DEFAULT NULL COMMENT '回复人的Id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of qf_message
-- ----------------------------
INSERT INTO `qf_message` VALUES ('1', '1', '晴枫', '上海市', '101.229.253.197', null, '<img alt=\"[酷]\" title=\"[酷]\" src=\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/40/cool_thumb.gif\">   样式还是很好看', '小米', '1507904110', '0', 'http://q.qlogo.cn/qqapp/101372250/8012FCD136B54A53C1ECBAC72C7E0A70/40', '2', '15', '1', null, null);
INSERT INTO `qf_message` VALUES ('2', '3', 'sunmale', '上海市', '116.231.171.47', '1982127547@qq.com', '测试：博客很不错哎', 'Win 10', '1507978140', '0', 'https://www.sunmale.cn/static/common/images/face/1.jpg', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('3', '1', '晴枫', '上海市', '116.231.171.47', null, '@<a href=\"javascript:;\" class=\"fly-aite\">sunmale</a> 谢谢关注哈。', 'Win 10', '1507978175', '2', 'http://q.qlogo.cn/qqapp/101372250/8012FCD136B54A53C1ECBAC72C7E0A70/40', '1', null, '1', 'sunmale ', '2');
INSERT INTO `qf_message` VALUES ('5', '6', 'Jesse', '广东省深圳市', '116.7.232.146', null, 'fdfdfdf', 'Win 7', '1508468341', '0', 'http://q.qlogo.cn/qqapp/101372250/67B64381FCD7F070F86790D61D33F098/40', '2', '20', '1', null, null);
INSERT INTO `qf_message` VALUES ('6', '6', 'Jesse', '广东省深圳市', '116.7.232.146', null, 'fdf', 'Win 7', '1508468348', '0', 'http://q.qlogo.cn/qqapp/101372250/67B64381FCD7F070F86790D61D33F098/40', '2', '20', '1', null, null);
INSERT INTO `qf_message` VALUES ('7', '1', '晴枫', '上海市', '116.231.168.35', null, '@<a href=\"javascript:;\" class=\"fly-aite\">Jesse</a> <img alt=\"[酷]\" title=\"[酷]\" src=\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/40/cool_thumb.gif\">', 'Win 10', '1508469517', '6', 'http://q.qlogo.cn/qqapp/101372250/8012FCD136B54A53C1ECBAC72C7E0A70/40', '2', '20', '1', 'Jesse ', '6');
INSERT INTO `qf_message` VALUES ('8', '7', '假面', '河南省郑州市', '115.60.58.69', null, '1', 'Win 10', '1508476528', '0', 'http://q.qlogo.cn/qqapp/101372250/1D24E7618C337BFFFFDB7A7433494D31/40', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('9', '10', '叶子一哥', '浙江省杭州市', '115.205.101.221', '977583818@qq.com', '赞，界面很简洁 很喜欢。 代码有上github之类的贡献吗', 'Win 7', '1508593707', '0', 'https://www.sunmale.cn/static/common/images/face/10.jpg', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('11', '3', 'sunmale', '上海市', '116.231.168.35', '1982127547@qq.com', '@<a href=\"javascript:;\" class=\"fly-aite\">叶子一哥</a> 暂时博客还在开发测试阶段，没有git源码。等版本稳定会上传的，可以去首页关注qq群或者关注 晴灬枫 个人公众号。', 'Win 10', '1508666762', '9', 'https://www.sunmale.cn/static/common/images/face/1.jpg', '1', null, '1', '叶子一哥 ', '9');
INSERT INTO `qf_message` VALUES ('12', '10', '叶子一哥', '浙江省杭州市', '183.128.87.96', '977583818@qq.com', '@<a href=\"javascript:;\" class=\"fly-aite\">sunmale</a> OK 已申请加入QQ群', 'Win 7', '1508675708', '9', 'https://www.sunmale.cn/static/common/images/face/10.jpg', '1', null, '1', 'sunmale', '11');
INSERT INTO `qf_message` VALUES ('13', '15', '米且水戋', '江苏省扬州市', '121.233.189.185', null, '额...同95后，哈哈哈，既然来了，就留下点什么', 'Win 10', '1508723996', '0', 'http://q.qlogo.cn/qqapp/101372250/1D717E04543E975B4B501ECA5FD55A7D/40', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('14', '15', '米且水戋', '江苏省扬州市', '121.233.189.185', null, 'sunmale=sun+male=阳光+男性<br>博主是在表示自己是个阳光小男生吗<img alt=\"[阴险]\" title=\"[阴险]\" src=\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/6d/yx_thumb.gif\">', 'Win 10', '1508724447', '0', 'http://q.qlogo.cn/qqapp/101372250/1D717E04543E975B4B501ECA5FD55A7D/40', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('15', '15', '米且水戋', '江苏省扬州市', '121.233.189.185', null, '希望通过时间的沉淀能够走出属于自己的人生。<br>很喜欢你写的这句话<br>我也是这么决定的', 'Win 10', '1508724542', '0', 'http://q.qlogo.cn/qqapp/101372250/1D717E04543E975B4B501ECA5FD55A7D/40', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('16', '3', 'sunmale', '上海市', '116.231.168.35', '1982127547@qq.com', '@<a href=\"javascript:;\" class=\"fly-aite\">米且水戋</a> 哎呦  被你解读了  其实我是想做太阳一样的男孩   <img alt=\"[酷]\" title=\"[酷]\" src=\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/40/cool_thumb.gif\">', 'Win 10', '1508725597', '14', 'https://www.sunmale.cn/static/common/images/face/1.jpg', '1', null, '1', '米且水戋 ', '14');
INSERT INTO `qf_message` VALUES ('17', '3', 'sunmale', '上海市', '116.231.168.35', '1982127547@qq.com', '@<a href=\"javascript:;\" class=\"fly-aite\">米且水戋</a> 嗯 共勉之  <img alt=\"[心]\" title=\"[心]\" src=\"http://img.t.sinajs.cn/t4/appstyle/expression/ext/normal/40/hearta_thumb.gif\">', 'Win 10', '1508725698', '15', 'https://www.sunmale.cn/static/common/images/face/1.jpg', '1', null, '1', '米且水戋 ', '15');
INSERT INTO `qf_message` VALUES ('18', '21', '*°暴打小朋友. ♂', '北京市', '111.204.128.178', null, '6', 'Mac OS', '1508821467', '0', 'http://q.qlogo.cn/qqapp/101372250/1F564EC141819722FD577204F2B2DBCD/40', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('19', '23', '默〃', '北京市', '221.221.159.0', null, '博客使用什么框架开发的', 'Win 10', '1508910190', '0', 'http://q.qlogo.cn/qqapp/101372250/1CD037951B1FA2AEE58050A88BA65B37/40', '1', null, '1', null, null);
INSERT INTO `qf_message` VALUES ('20', '3', 'sunmale', '上海市', '116.231.168.35', '1982127547@qq.com', '@<a href=\"javascript:;\" class=\"fly-aite\">默〃</a> Thinkphp 5.0.11 +Layui 2.x.x', 'Win 10', '1508912817', '19', 'https://www.sunmale.cn/static/common/images/face/1.jpg', '1', null, '1', '默〃 ', '19');
INSERT INTO `qf_message` VALUES ('21', '25', '听音乐', '北京市', '123.115.63.213', null, '我现在在写一个博客的后台管理，看了楼主的博客，真棒。加油！！！', 'Win 7', '1508919800', '0', 'http://q.qlogo.cn/qqapp/101372250/675C3EC4ACB75D1692B08FACD4AFF0B9/40', '1', null, '1', null, null);

-- ----------------------------
-- Table structure for qf_said
-- ----------------------------
DROP TABLE IF EXISTS `qf_said`;
CREATE TABLE `qf_said` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` int(20) DEFAULT NULL,
  `update_time` int(20) DEFAULT NULL,
  `os` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of qf_said
-- ----------------------------
INSERT INTO `qf_said` VALUES ('1', '晴枫博客开放测试中。。。', '1508317733', '1508317733', 'Win 10', '上海市', '116.231.168.35', '1', '0');

-- ----------------------------
-- Table structure for qf_user
-- ----------------------------
DROP TABLE IF EXISTS `qf_user`;
CREATE TABLE `qf_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `headurl` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `view` int(11) DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` int(20) DEFAULT NULL,
  `update_time` int(20) DEFAULT NULL,
  `is_active` int(2) DEFAULT '0',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qq_openid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wx_openid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of qf_user
-- ----------------------------

-- ----------------------------
-- Table structure for qf_zan
-- ----------------------------
DROP TABLE IF EXISTS `qf_zan`;
CREATE TABLE `qf_zan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `os` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` int(11) NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `FK_messageid` (`message_id`),
  CONSTRAINT `FK_messageid` FOREIGN KEY (`message_id`) REFERENCES `qf_message` (`id`),
  CONSTRAINT `FK_userid` FOREIGN KEY (`userid`) REFERENCES `qf_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of qf_zan
-- ----------------------------

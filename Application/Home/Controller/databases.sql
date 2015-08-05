insert into mysql.user(Host,User,Password) values("localhost","grassu",password("grassu"));
create database grassu;
grant all privileges on grassu.* to grassu@localhost identified by 'grassu';
drop table users;
CREATE TABLE `users` (
Uid     int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id(从10000起)',
Unick varchar(32)  NOT NULL DEFAULT '' COMMENT '用户昵称，可以修改，但是也要唯一',
UPwd varchar(32)  NOT NULL DEFAULT ''  COMMENT '用户密码，MD5值',
UGender int(11) NOT NULL DEFAULT '-1'  COMMENT '用户性别，-1保密，0女，1男',
UBirthday timestamp  NULL DEFAULT 0   COMMENT '用户生日',
U_CityID int(11)   NOT NULL DEFAULT  0 COMMENT '用户位置ID，省份/城市ID',
UEmail varchar(64)   NULL DEFAULT  '' COMMENT '用户邮箱',
UMobile varchar(16)   NULL DEFAULT '' COMMENT '用户手机',
U_ChannelID int(11)   NOT NULL DEFAULT 0 COMMENT '用户兴趣频道',
UAvatar varchar(64)  NOT NULL DEFAULT ''  COMMENT '头像地址',
URemarks varchar(128)  NOT NULL DEFAULT ''  COMMENT '用户一句话描述',
USource varchar(64)   NOT NULL DEFAULT ''  COMMENT '用户注册来源信息',
UToken varchar(64)  NOT NULL DEFAULT ''  COMMENT '登录token',
URegDate timestamp  NOT NULL DEFAULT 0  COMMENT '注册日期',
ULLoginDate timestamp  NOT NULL DEFAULT 0  COMMENT '上次登录时间登录时间',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`Uid`),
UNIQUE KEY `idx_UMobile` (`UMobile`),
KEY `idx_Unick` (`Unick`),
KEY `idx_UEmail` (`UEmail`),
KEY `idx_UToken` (`UToken`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
//alter table users auto_increment = 10000;

//用户设置表
CREATE TABLE `usersets` (
Uid     int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
Sets  varchar(1024)  NOT NULL DEFAULT '' COMMENT '用户设置串',
UDate timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP  COMMENT '修改日期',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;




CREATE TABLE `devices` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT 'ID',
U_Uid int(11) NOT NULL DEFAULT 0 COMMENT '本系统的用户ID',
AppID varchar(64) NOT NULL DEFAULT '' COMMENT 'appid',
UserID varchar(64) NULL DEFAULT ''  COMMENT '百度生成的userid',
Utime timestamp  NULL DEFAULT 0     COMMENT '更新时间',
Source int(11) NULL DEFAULT 0  COMMENT 'IOS:设备的编码 Android:channel_id设备类型',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`ID`),
UNIQUE KEY `idx_Uid` (`U_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `citys` (
CityID int(11)  NOT NULL AUTO_INCREMENT COMMENT '城市ID（从1000起）',
Code varchar(8) NOT NULL DEFAULT '' COMMENT '城市区号',
CityName varchar(64) NOT NULL DEFAULT '' COMMENT '城市中文名称',
PvcName varchar(64)  NULL DEFAULT '' COMMENT '省份中文名称',
Latitude   double(10,5) NULL DEFAULT 0     COMMENT '城市纬度',
Longitude double(10,5)  NULL DEFAULT 0     COMMENT '城市经度',
Type int(11) NULL DEFAULT 0  COMMENT '城市0/省份1',
Status int(11) NULL DEFAULT 1  COMMENT '状态0:下线1:上线',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`CityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
insert into citys(Code,CityName,PvcName) values('010','北京','北京');
insert into citys(Code,CityName,PvcName) values('021','上海','上海');
insert into citys(Code,CityName,PvcName) values('022','天津','天津');
insert into citys(Code,CityName,PvcName) values('023','重庆','重庆');
insert into citys(Code,CityName,PvcName) values('0551','合肥','安徽');
insert into citys(Code,CityName,PvcName) values('0553','芜湖','安徽');
insert into citys(Code,CityName,PvcName) values('0556','安庆','安徽');
insert into citys(Code,CityName,PvcName) values('0552','蚌埠','安徽');
insert into citys(Code,CityName,PvcName) values('0558','亳州','安徽');
insert into citys(Code,CityName,PvcName) values('0565','巢湖','安徽');
insert into citys(Code,CityName,PvcName) values('0566','池州','安徽');
insert into citys(Code,CityName,PvcName) values('0550','滁州','安徽');
insert into citys(Code,CityName,PvcName) values('1558','阜阳','安徽');
insert into citys(Code,CityName,PvcName) values('0559','黄山','安徽');
insert into citys(Code,CityName,PvcName) values('0561','淮北','安徽');
insert into citys(Code,CityName,PvcName) values('0554','淮南','安徽');
insert into citys(Code,CityName,PvcName) values('0564','六安','安徽');
insert into citys(Code,CityName,PvcName) values('0555','马鞍山','安徽');
insert into citys(Code,CityName,PvcName) values('0557','宿州','安徽');
insert into citys(Code,CityName,PvcName) values('0562','铜陵','安徽');
insert into citys(Code,CityName,PvcName) values('0563','宣城','安徽');
insert into citys(Code,CityName,PvcName) values('0591','福州','福建');
insert into citys(Code,CityName,PvcName) values('0592','厦门','福建');
insert into citys(Code,CityName,PvcName) values('0595','泉州','福建');
insert into citys(Code,CityName,PvcName) values('0597','龙岩','福建');
insert into citys(Code,CityName,PvcName) values('0593','宁德','福建');
insert into citys(Code,CityName,PvcName) values('0599','南平','福建');
insert into citys(Code,CityName,PvcName) values('0594','莆田','福建');
insert into citys(Code,CityName,PvcName) values('0598','三明','福建');
insert into citys(Code,CityName,PvcName) values('0596','漳州','福建');
insert into citys(Code,CityName,PvcName) values('0931','兰州','甘肃');
insert into citys(Code,CityName,PvcName) values('0943','白银','甘肃');
insert into citys(Code,CityName,PvcName) values('0932','定西','甘肃');
insert into citys(Code,CityName,PvcName) values('0935','金昌','甘肃');
insert into citys(Code,CityName,PvcName) values('0937','酒泉','甘肃');
insert into citys(Code,CityName,PvcName) values('0933','平凉','甘肃');
insert into citys(Code,CityName,PvcName) values('0934','庆阳','甘肃');
insert into citys(Code,CityName,PvcName) values('1935','武威','甘肃');
insert into citys(Code,CityName,PvcName) values('0938','天水','甘肃');
insert into citys(Code,CityName,PvcName) values('0936','张掖','甘肃');
insert into citys(Code,CityName,PvcName) values('0941','甘南','甘肃');
insert into citys(Code,CityName,PvcName) values('1937','嘉峪关','甘肃');
insert into citys(Code,CityName,PvcName) values('0930','临夏','甘肃');
insert into citys(Code,CityName,PvcName) values('2935','陇南','甘肃');
insert into citys(Code,CityName,PvcName) values('020','广州','广东');
insert into citys(Code,CityName,PvcName) values('0755','深圳','广东');
insert into citys(Code,CityName,PvcName) values('0756','珠海','广东');
insert into citys(Code,CityName,PvcName) values('0769','东莞','广东');
insert into citys(Code,CityName,PvcName) values('0757','佛山','广东');
insert into citys(Code,CityName,PvcName) values('0752','惠州','广东');
insert into citys(Code,CityName,PvcName) values('0750','江门','广东');
insert into citys(Code,CityName,PvcName) values('0760','中山','广东');
insert into citys(Code,CityName,PvcName) values('0754','汕头','广东');
insert into citys(Code,CityName,PvcName) values('0759','湛江','广东');
insert into citys(Code,CityName,PvcName) values('0768','潮州','广东');
insert into citys(Code,CityName,PvcName) values('0762','河源','广东');
insert into citys(Code,CityName,PvcName) values('0663','揭阳','广东');
insert into citys(Code,CityName,PvcName) values('0668','茂名','广东');
insert into citys(Code,CityName,PvcName) values('0753','梅州','广东');
insert into citys(Code,CityName,PvcName) values('0763','清远','广东');
insert into citys(Code,CityName,PvcName) values('0751','韶关','广东');
insert into citys(Code,CityName,PvcName) values('0660','汕尾','广东');
insert into citys(Code,CityName,PvcName) values('0662','阳江','广东');
insert into citys(Code,CityName,PvcName) values('0766','云浮','广东');
insert into citys(Code,CityName,PvcName) values('0758','肇庆','广东');
insert into citys(Code,CityName,PvcName) values('0771','南宁','广西');
insert into citys(Code,CityName,PvcName) values('0779','北海','广西');
insert into citys(Code,CityName,PvcName) values('0770','防城港','广西');
insert into citys(Code,CityName,PvcName) values('0773','桂林','广西');
insert into citys(Code,CityName,PvcName) values('0772','柳州','广西');
insert into citys(Code,CityName,PvcName) values('1771','崇左','广西');
insert into citys(Code,CityName,PvcName) values('1772','来宾','广西');
insert into citys(Code,CityName,PvcName) values('0774','梧州','广西');
insert into citys(Code,CityName,PvcName) values('0778','河池','广西');
insert into citys(Code,CityName,PvcName) values('0775','玉林','广西');
insert into citys(Code,CityName,PvcName) values('1755','贵港','广西');
insert into citys(Code,CityName,PvcName) values('1774','贺州','广西');
insert into citys(Code,CityName,PvcName) values('0777','钦州','广西');
insert into citys(Code,CityName,PvcName) values('0776','百色','广西');
insert into citys(Code,CityName,PvcName) values('0851','贵阳','贵州');
insert into citys(Code,CityName,PvcName) values('0853','安顺','贵州');
insert into citys(Code,CityName,PvcName) values('0852','遵义','贵州');
insert into citys(Code,CityName,PvcName) values('0858','六盘水','贵州');
insert into citys(Code,CityName,PvcName) values('0857','毕节','贵州');
insert into citys(Code,CityName,PvcName) values('0855','黔东南','贵州');
insert into citys(Code,CityName,PvcName) values('0859','黔西南','贵州');
insert into citys(Code,CityName,PvcName) values('0854','黔南','贵州');
insert into citys(Code,CityName,PvcName) values('0856','铜仁','贵州');
insert into citys(Code,CityName,PvcName) values('0898','海口','海南');
insert into citys(Code,CityName,PvcName) values('0899','三亚','海南');
insert into citys(Code,CityName,PvcName) values('0802','白沙县','海南');
insert into citys(Code,CityName,PvcName) values('0801','保亭县','海南');
insert into citys(Code,CityName,PvcName) values('0803','昌江县','海南');
insert into citys(Code,CityName,PvcName) values('0804','澄迈县','海南');
insert into citys(Code,CityName,PvcName) values('0806','定安县','海南');
insert into citys(Code,CityName,PvcName) values('0807','东方','海南');
insert into citys(Code,CityName,PvcName) values('2802','乐东县','海南');
insert into citys(Code,CityName,PvcName) values('1896','临高县','海南');
insert into citys(Code,CityName,PvcName) values('0809','陵水县','海南');
insert into citys(Code,CityName,PvcName) values('1894','琼海','海南');
insert into citys(Code,CityName,PvcName) values('1899','琼中县','海南');
insert into citys(Code,CityName,PvcName) values('1892','屯昌县','海南');
insert into citys(Code,CityName,PvcName) values('1898','万宁','海南');
insert into citys(Code,CityName,PvcName) values('1893','文昌','海南');
insert into citys(Code,CityName,PvcName) values('1897','五指山','海南');
insert into citys(Code,CityName,PvcName) values('0805','儋州','海南');
insert into citys(Code,CityName,PvcName) values('0311','石家庄','河北');
insert into citys(Code,CityName,PvcName) values('0312','保定','河北');
insert into citys(Code,CityName,PvcName) values('0314','承德','河北');
insert into citys(Code,CityName,PvcName) values('0310','邯郸','河北');
insert into citys(Code,CityName,PvcName) values('0315','唐山','河北');
insert into citys(Code,CityName,PvcName) values('0335','秦皇岛','河北');
insert into citys(Code,CityName,PvcName) values('0317','沧州','河北');
insert into citys(Code,CityName,PvcName) values('0318','衡水','河北');
insert into citys(Code,CityName,PvcName) values('0316','廊坊','河北');
insert into citys(Code,CityName,PvcName) values('0319','邢台','河北');
insert into citys(Code,CityName,PvcName) values('0313','张家口','河北');
insert into citys(Code,CityName,PvcName) values('0371','郑州','河南');
insert into citys(Code,CityName,PvcName) values('0379','洛阳','河南');
insert into citys(Code,CityName,PvcName) values('0378','开封','河南');
insert into citys(Code,CityName,PvcName) values('0374','许昌','河南');
insert into citys(Code,CityName,PvcName) values('0372','安阳','河南');
insert into citys(Code,CityName,PvcName) values('0375','平顶山','河南');
insert into citys(Code,CityName,PvcName) values('0392','鹤壁','河南');
insert into citys(Code,CityName,PvcName) values('0391','焦作','河南');
insert into citys(Code,CityName,PvcName) values('1391','济源','河南');
insert into citys(Code,CityName,PvcName) values('0395','漯河','河南');
insert into citys(Code,CityName,PvcName) values('0377','南阳','河南');
insert into citys(Code,CityName,PvcName) values('0393','濮阳','河南');
insert into citys(Code,CityName,PvcName) values('0398','三门峡','河南');
insert into citys(Code,CityName,PvcName) values('0370','商丘','河南');
insert into citys(Code,CityName,PvcName) values('0373','新乡','河南');
insert into citys(Code,CityName,PvcName) values('0376','信阳','河南');
insert into citys(Code,CityName,PvcName) values('0396','驻马店','河南');
insert into citys(Code,CityName,PvcName) values('0394','周口','河南');
insert into citys(Code,CityName,PvcName) values('0451','哈尔滨','黑龙江');
insert into citys(Code,CityName,PvcName) values('0459','大庆','黑龙江');
insert into citys(Code,CityName,PvcName) values('0452','齐齐哈尔','黑龙江');
insert into citys(Code,CityName,PvcName) values('0454','佳木斯','黑龙江');
insert into citys(Code,CityName,PvcName) values('0457','大兴安岭','黑龙江');
insert into citys(Code,CityName,PvcName) values('0456','黑河','黑龙江');
insert into citys(Code,CityName,PvcName) values('0468','鹤岗','黑龙江');
insert into citys(Code,CityName,PvcName) values('0467','鸡西','黑龙江');
insert into citys(Code,CityName,PvcName) values('0453','牡丹江','黑龙江');
insert into citys(Code,CityName,PvcName) values('0464','七台河','黑龙江');
insert into citys(Code,CityName,PvcName) values('0455','绥化','黑龙江');
insert into citys(Code,CityName,PvcName) values('0469','双鸭山','黑龙江');
insert into citys(Code,CityName,PvcName) values('0458','伊春','黑龙江');
insert into citys(Code,CityName,PvcName) values('027','武汉','湖北');
insert into citys(Code,CityName,PvcName) values('0710','襄阳','湖北');
insert into citys(Code,CityName,PvcName) values('0719','十堰','湖北');
insert into citys(Code,CityName,PvcName) values('0714','黄石','湖北');
insert into citys(Code,CityName,PvcName) values('0711','鄂州','湖北');
insert into citys(Code,CityName,PvcName) values('0718','恩施','湖北');
insert into citys(Code,CityName,PvcName) values('0713','黄冈','湖北');
insert into citys(Code,CityName,PvcName) values('0716','荆州','湖北');
insert into citys(Code,CityName,PvcName) values('0724','荆门','湖北');
insert into citys(Code,CityName,PvcName) values('0722','随州','湖北');
insert into citys(Code,CityName,PvcName) values('0717','宜昌','湖北');
insert into citys(Code,CityName,PvcName) values('1728','天门','湖北');
insert into citys(Code,CityName,PvcName) values('2728','潜江','湖北');
insert into citys(Code,CityName,PvcName) values('0728','仙桃','湖北');
insert into citys(Code,CityName,PvcName) values('0712','孝感','湖北');
insert into citys(Code,CityName,PvcName) values('0715','咸宁','湖北');
insert into citys(Code,CityName,PvcName) values('1719','神农架','湖北');
insert into citys(Code,CityName,PvcName) values('0731','长沙','湖南');
insert into citys(Code,CityName,PvcName) values('0730','岳阳','湖南');
insert into citys(Code,CityName,PvcName) values('0732','湘潭','湖南');
insert into citys(Code,CityName,PvcName) values('0736','常德','湖南');
insert into citys(Code,CityName,PvcName) values('0735','郴州','湖南');
insert into citys(Code,CityName,PvcName) values('0734','衡阳','湖南');
insert into citys(Code,CityName,PvcName) values('0745','怀化','湖南');
insert into citys(Code,CityName,PvcName) values('0738','娄底','湖南');
insert into citys(Code,CityName,PvcName) values('0739','邵阳','湖南');
insert into citys(Code,CityName,PvcName) values('0737','益阳','湖南');
insert into citys(Code,CityName,PvcName) values('0746','永州','湖南');
insert into citys(Code,CityName,PvcName) values('0733','株洲','湖南');
insert into citys(Code,CityName,PvcName) values('0744','张家界','湖南');
insert into citys(Code,CityName,PvcName) values('0743','湘西','湖南');
insert into citys(Code,CityName,PvcName) values('0431','长春','吉林');
insert into citys(Code,CityName,PvcName) values('0432','吉林','吉林');
insert into citys(Code,CityName,PvcName) values('1433','延边','吉林');
insert into citys(Code,CityName,PvcName) values('0436','白城','吉林');
insert into citys(Code,CityName,PvcName) values('0439','白山','吉林');
insert into citys(Code,CityName,PvcName) values('0437','辽源','吉林');
insert into citys(Code,CityName,PvcName) values('0434','四平','吉林');
insert into citys(Code,CityName,PvcName) values('0438','松原','吉林');
insert into citys(Code,CityName,PvcName) values('0435','通化','吉林');
insert into citys(Code,CityName,PvcName) values('025','南京','江苏');
insert into citys(Code,CityName,PvcName) values('0512','苏州','江苏');
insert into citys(Code,CityName,PvcName) values('0519','常州','江苏');
insert into citys(Code,CityName,PvcName) values('0518','连云港','江苏');
insert into citys(Code,CityName,PvcName) values('0523','泰州','江苏');
insert into citys(Code,CityName,PvcName) values('0510','无锡','江苏');
insert into citys(Code,CityName,PvcName) values('0516','徐州','江苏');
insert into citys(Code,CityName,PvcName) values('0514','扬州','江苏');
insert into citys(Code,CityName,PvcName) values('0511','镇江','江苏');
insert into citys(Code,CityName,PvcName) values('0517','淮安','江苏');
insert into citys(Code,CityName,PvcName) values('0513','南通','江苏');
insert into citys(Code,CityName,PvcName) values('0527','宿迁','江苏');
insert into citys(Code,CityName,PvcName) values('0515','盐城','江苏');
insert into citys(Code,CityName,PvcName) values('0791','南昌','江西');
insert into citys(Code,CityName,PvcName) values('0797','赣州','江西');
insert into citys(Code,CityName,PvcName) values('0792','九江','江西');
insert into citys(Code,CityName,PvcName) values('0798','景德镇','江西');
insert into citys(Code,CityName,PvcName) values('0796','吉安','江西');
insert into citys(Code,CityName,PvcName) values('0799','萍乡','江西');
insert into citys(Code,CityName,PvcName) values('0793','上饶','江西');
insert into citys(Code,CityName,PvcName) values('0790','新余','江西');
insert into citys(Code,CityName,PvcName) values('0795','宜春','江西');
insert into citys(Code,CityName,PvcName) values('0701','鹰潭','江西');
insert into citys(Code,CityName,PvcName) values('0794','抚州','江西');
insert into citys(Code,CityName,PvcName) values('024','沈阳','辽宁');
insert into citys(Code,CityName,PvcName) values('0411','大连','辽宁');
insert into citys(Code,CityName,PvcName) values('0412','鞍山','辽宁');
insert into citys(Code,CityName,PvcName) values('0415','丹东','辽宁');
insert into citys(Code,CityName,PvcName) values('0413','抚顺','辽宁');
insert into citys(Code,CityName,PvcName) values('0416','锦州','辽宁');
insert into citys(Code,CityName,PvcName) values('0417','营口','辽宁');
insert into citys(Code,CityName,PvcName) values('0414','本溪','辽宁');
insert into citys(Code,CityName,PvcName) values('0421','朝阳','辽宁');
insert into citys(Code,CityName,PvcName) values('0418','阜新','辽宁');
insert into citys(Code,CityName,PvcName) values('0429','葫芦岛','辽宁');
insert into citys(Code,CityName,PvcName) values('0419','辽阳','辽宁');
insert into citys(Code,CityName,PvcName) values('0427','盘锦','辽宁');
insert into citys(Code,CityName,PvcName) values('0410','铁岭','辽宁');
insert into citys(Code,CityName,PvcName) values('0471','呼和浩特','内蒙古');
insert into citys(Code,CityName,PvcName) values('0472','包头','内蒙古');
insert into citys(Code,CityName,PvcName) values('0476','赤峰','内蒙古');
insert into citys(Code,CityName,PvcName) values('0477','鄂尔多斯','内蒙古');
insert into citys(Code,CityName,PvcName) values('0474','乌兰察布','内蒙古');
insert into citys(Code,CityName,PvcName) values('0473','乌海','内蒙古');
insert into citys(Code,CityName,PvcName) values('0482','兴安盟','内蒙古');
insert into citys(Code,CityName,PvcName) values('0470','呼伦贝尔','内蒙古');
insert into citys(Code,CityName,PvcName) values('0475','通辽','内蒙古');
insert into citys(Code,CityName,PvcName) values('0483','阿拉善盟','内蒙古');
insert into citys(Code,CityName,PvcName) values('0478','巴彦淖尔','内蒙古');
insert into citys(Code,CityName,PvcName) values('0479','锡林郭勒','内蒙古');
insert into citys(Code,CityName,PvcName) values('0951','银川','宁夏');
insert into citys(Code,CityName,PvcName) values('0952','石嘴山','宁夏');
insert into citys(Code,CityName,PvcName) values('0954','固原','宁夏');
insert into citys(Code,CityName,PvcName) values('0953','吴忠','宁夏');
insert into citys(Code,CityName,PvcName) values('1953','中卫','宁夏');
insert into citys(Code,CityName,PvcName) values('0971','西宁','青海');
insert into citys(Code,CityName,PvcName) values('0973','黄南','青海');
insert into citys(Code,CityName,PvcName) values('0976','玉树','青海');
insert into citys(Code,CityName,PvcName) values('0975','果洛','青海');
insert into citys(Code,CityName,PvcName) values('0972','海东','青海');
insert into citys(Code,CityName,PvcName) values('0977','海西','青海');
insert into citys(Code,CityName,PvcName) values('0974','海南','青海');
insert into citys(Code,CityName,PvcName) values('0970','海北','青海');
insert into citys(Code,CityName,PvcName) values('0531','济南','山东');
insert into citys(Code,CityName,PvcName) values('0532','青岛','山东');
insert into citys(Code,CityName,PvcName) values('0631','威海','山东');
insert into citys(Code,CityName,PvcName) values('0535','烟台','山东');
insert into citys(Code,CityName,PvcName) values('0536','潍坊','山东');
insert into citys(Code,CityName,PvcName) values('0538','泰安','山东');
insert into citys(Code,CityName,PvcName) values('0543','滨州','山东');
insert into citys(Code,CityName,PvcName) values('0534','德州','山东');
insert into citys(Code,CityName,PvcName) values('0546','东营','山东');
insert into citys(Code,CityName,PvcName) values('0530','菏泽','山东');
insert into citys(Code,CityName,PvcName) values('0537','济宁','山东');
insert into citys(Code,CityName,PvcName) values('0635','聊城','山东');
insert into citys(Code,CityName,PvcName) values('0539','临沂','山东');
insert into citys(Code,CityName,PvcName) values('0634','莱芜','山东');
insert into citys(Code,CityName,PvcName) values('0633','日照','山东');
insert into citys(Code,CityName,PvcName) values('0533','淄博','山东');
insert into citys(Code,CityName,PvcName) values('0632','枣庄','山东');
insert into citys(Code,CityName,PvcName) values('0351','太原','山西');
insert into citys(Code,CityName,PvcName) values('0355','长治','山西');
insert into citys(Code,CityName,PvcName) values('0352','大同','山西');
insert into citys(Code,CityName,PvcName) values('0356','晋城','山西');
insert into citys(Code,CityName,PvcName) values('0354','晋中','山西');
insert into citys(Code,CityName,PvcName) values('0357','临汾','山西');
insert into citys(Code,CityName,PvcName) values('0358','吕梁','山西');
insert into citys(Code,CityName,PvcName) values('0349','朔州','山西');
insert into citys(Code,CityName,PvcName) values('0350','忻州','山西');
insert into citys(Code,CityName,PvcName) values('0359','运城','山西');
insert into citys(Code,CityName,PvcName) values('0353','阳泉','山西');
insert into citys(Code,CityName,PvcName) values('029','西安','陕西');
insert into citys(Code,CityName,PvcName) values('0915','安康','陕西');
insert into citys(Code,CityName,PvcName) values('0917','宝鸡','陕西');
insert into citys(Code,CityName,PvcName) values('0916','汉中','陕西');
insert into citys(Code,CityName,PvcName) values('0914','商洛','陕西');
insert into citys(Code,CityName,PvcName) values('0919','铜川','陕西');
insert into citys(Code,CityName,PvcName) values('0913','渭南','陕西');
insert into citys(Code,CityName,PvcName) values('0910','咸阳','陕西');
insert into citys(Code,CityName,PvcName) values('0911','延安','陕西');
insert into citys(Code,CityName,PvcName) values('0912','榆林','陕西');
insert into citys(Code,CityName,PvcName) values('028','成都','四川');
insert into citys(Code,CityName,PvcName) values('0816','绵阳','四川');
insert into citys(Code,CityName,PvcName) values('0832','资阳','四川');
insert into citys(Code,CityName,PvcName) values('0827','巴中','四川');
insert into citys(Code,CityName,PvcName) values('0838','德阳','四川');
insert into citys(Code,CityName,PvcName) values('0818','达州','四川');
insert into citys(Code,CityName,PvcName) values('0826','广安','四川');
insert into citys(Code,CityName,PvcName) values('0839','广元','四川');
insert into citys(Code,CityName,PvcName) values('0833','乐山','四川');
insert into citys(Code,CityName,PvcName) values('0830','泸州','四川');
insert into citys(Code,CityName,PvcName) values('1833','眉山','四川');
insert into citys(Code,CityName,PvcName) values('1832','内江','四川');
insert into citys(Code,CityName,PvcName) values('0817','南充','四川');
insert into citys(Code,CityName,PvcName) values('0812','攀枝花','四川');
insert into citys(Code,CityName,PvcName) values('0825','遂宁','四川');
insert into citys(Code,CityName,PvcName) values('0831','宜宾','四川');
insert into citys(Code,CityName,PvcName) values('0835','雅安','四川');
insert into citys(Code,CityName,PvcName) values('0813','自贡','四川');
insert into citys(Code,CityName,PvcName) values('0837','阿坝','四川');
insert into citys(Code,CityName,PvcName) values('0836','甘孜','四川');
insert into citys(Code,CityName,PvcName) values('0834','凉山','四川');
insert into citys(Code,CityName,PvcName) values('0891','拉萨','西藏');
insert into citys(Code,CityName,PvcName) values('0892','日喀则','西藏');
insert into citys(Code,CityName,PvcName) values('0897','阿里','西藏');
insert into citys(Code,CityName,PvcName) values('0895','昌都','西藏');
insert into citys(Code,CityName,PvcName) values('0894','林芝','西藏');
insert into citys(Code,CityName,PvcName) values('0896','那曲','西藏');
insert into citys(Code,CityName,PvcName) values('0893','山南','西藏');
insert into citys(Code,CityName,PvcName) values('0991','乌鲁木齐','新疆');
insert into citys(Code,CityName,PvcName) values('0993','石河子','新疆');
insert into citys(Code,CityName,PvcName) values('0995','吐鲁番','新疆');
insert into citys(Code,CityName,PvcName) values('0999','伊犁','新疆');
insert into citys(Code,CityName,PvcName) values('0997','阿克苏','新疆');
insert into citys(Code,CityName,PvcName) values('0906','阿勒泰','新疆');
insert into citys(Code,CityName,PvcName) values('0996','巴音','新疆');
insert into citys(Code,CityName,PvcName) values('0909','博尔塔拉','新疆');
insert into citys(Code,CityName,PvcName) values('0994','昌吉','新疆');
insert into citys(Code,CityName,PvcName) values('0902','哈密','新疆');
insert into citys(Code,CityName,PvcName) values('0903','和田','新疆');
insert into citys(Code,CityName,PvcName) values('0998','喀什','新疆');
insert into citys(Code,CityName,PvcName) values('0990','克拉玛依','新疆');
insert into citys(Code,CityName,PvcName) values('0908','克孜勒','新疆');
insert into citys(Code,CityName,PvcName) values('0901','塔城','新疆');
insert into citys(Code,CityName,PvcName) values('0871','昆明','云南');
insert into citys(Code,CityName,PvcName) values('0877','玉溪','云南');
insert into citys(Code,CityName,PvcName) values('0878','楚雄','云南');
insert into citys(Code,CityName,PvcName) values('0872','大理','云南');
insert into citys(Code,CityName,PvcName) values('0873','红河','云南');
insert into citys(Code,CityName,PvcName) values('0874','曲靖','云南');
insert into citys(Code,CityName,PvcName) values('0691','西双版纳','云南');
insert into citys(Code,CityName,PvcName) values('0870','昭通','云南');
insert into citys(Code,CityName,PvcName) values('0875','保山','云南');
insert into citys(Code,CityName,PvcName) values('0692','德宏','云南');
insert into citys(Code,CityName,PvcName) values('0887','迪庆','云南');
insert into citys(Code,CityName,PvcName) values('0888','丽江','云南');
insert into citys(Code,CityName,PvcName) values('0883','临沧','云南');
insert into citys(Code,CityName,PvcName) values('0886','怒江','云南');
insert into citys(Code,CityName,PvcName) values('0879','普洱','云南');
insert into citys(Code,CityName,PvcName) values('0876','文山','云南');
insert into citys(Code,CityName,PvcName) values('0571','杭州','浙江');
insert into citys(Code,CityName,PvcName) values('0574','宁波','浙江');
insert into citys(Code,CityName,PvcName) values('0573','嘉兴','浙江');
insert into citys(Code,CityName,PvcName) values('0575','绍兴','浙江');
insert into citys(Code,CityName,PvcName) values('0577','温州','浙江');
insert into citys(Code,CityName,PvcName) values('0580','舟山','浙江');
insert into citys(Code,CityName,PvcName) values('0572','湖州','浙江');
insert into citys(Code,CityName,PvcName) values('0579','金华','浙江');
insert into citys(Code,CityName,PvcName) values('0578','丽水','浙江');
insert into citys(Code,CityName,PvcName) values('0576','台州','浙江');
insert into citys(Code,CityName,PvcName) values('0570','衢州','浙江');
insert into citys(Code,CityName,PvcName) values('1852','香港','香港');
insert into citys(Code,CityName,PvcName) values('1852','澳门','澳门');


drop table vweibos;
CREATE TABLE `vweibos` (
VWeiboID int(11)  NOT NULL AUTO_INCREMENT COMMENT '视频合拍ID（从100起）',
V_Uid int(11) NOT NULL DEFAULT 0 COMMENT '发表视频合拍的用户id',
ORGVWpicID varchar(64)  NOT NULL DEFAULT ''  COMMENT '原始视频缩略图编号',
ORGVWVideoID varchar(64)  NOT NULL DEFAULT ''  COMMENT '原始视频编号',
ORGVWVideoParam varchar(64)  NOT NULL DEFAULT ''  COMMENT '原始视频编号参数',
VWpicID varchar(64)  NOT NULL DEFAULT ''  COMMENT '视频缩略图编号',
VWVideoID varchar(64)  NOT NULL DEFAULT ''  COMMENT '视频编号',
VWVideoParam varchar(64)  NOT NULL DEFAULT ''  COMMENT '视频编号参数',
ViewNum int(11)  NULL DEFAULT 1     COMMENT '浏览次数',
PubTime timestamp  NULL DEFAULT 0  COMMENT '视频合拍发表时间',
Location varchar(128) NULL DEFAULT ''  COMMENT '发表位置',
Latitude double(10,5) NULL DEFAULT 0 COMMENT '发表纬度',
Longitude double(10,5) NULL DEFAULT 0 COMMENT '发表经度',
ChannelID int(11) NULL DEFAULT 0 COMMENT '频道ID',
Authority int(11) NULL DEFAULT 0 COMMENT '视频权限（点赞、转发、评论、合奏等权限）',
Content varchar(256) NULL DEFAULT '' COMMENT '视频合拍内容',
TopicList varchar(256) NULL DEFAULT '' COMMENT '视频合拍话题列表',
AtList varchar(512) NULL DEFAULT '' COMMENT '视频合拍at列表',
PraiseNum int(11) NULL DEFAULT 0 COMMENT '被点赞次数',
FowardNum int(11) NULL DEFAULT 0 COMMENT '被转发次数',
CommentNum int(11) NULL DEFAULT 0 COMMENT '被评论次数',
EnsembleNum int(11) NULL DEFAULT 0 COMMENT '被合奏次数',
Recommended int(11) NULL DEFAULT 0 COMMENT '是否推荐',
RptNum int(11) NULL DEFAULT 0 COMMENT '被举报次数（超过4则自动隐藏）',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`VWeiboID`),
INDEX `idx_Uid` (`V_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

drop table fellows;
CREATE TABLE `fellows` (
FellowID int(11)  NOT NULL AUTO_INCREMENT COMMENT '关注编号ID（从1000起）',
F_Uid int(11) NOT NULL DEFAULT 0 COMMENT '关注用户ID',
T_Uid int(11)  NULL DEFAULT 0 COMMENT '被关注用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '首次关注时间',
DDate   timestamp  NULL DEFAULT 0     COMMENT '取消关注时间',
FStatus  int(11) NOT NULL DEFAULT 1 COMMENT '当前关注状态(0取消/1关注中)',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`FellowID`),
UNIQUE KEY `idx_Uid` (`F_Uid`,`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

drop table channels;
CREATE TABLE `channels` (
ChannelID int(11)  NOT NULL AUTO_INCREMENT COMMENT '频道ID（从100起）',
ParentID  int(11) NULL DEFAULT 0 COMMENT '父级频道ID',
ChannelChgName varchar(64) NULL DEFAULT '' COMMENT '频道中文名称',
ChannelEngName varchar(64) NULL DEFAULT '' COMMENT '频道英文名称',
Description varchar(128) NULL DEFAULT '' COMMENT '频道描述',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`ChannelID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
alter table channels auto_increment = 100;
insert into channels(ChannelChgName,ChannelEngName) values('摇滚','Rock');
insert into channels(ChannelChgName,ChannelEngName) values('民谣','Folk');
insert into channels(ChannelChgName,ChannelEngName) values('独立','Indie');
insert into channels(ChannelChgName,ChannelEngName) values('英伦','Brit-pop');
insert into channels(ChannelChgName,ChannelEngName) values('朋克','Punk');
insert into channels(ChannelChgName,ChannelEngName) values('金属','Metal');
insert into channels(ChannelChgName,ChannelEngName) values('动漫游戏','ACG');
insert into channels(ChannelChgName,ChannelEngName) values('电子','Electronic');
insert into channels(ChannelChgName,ChannelEngName) values('哥特','Gothic');
insert into channels(ChannelChgName,ChannelEngName) values('爵士','Jazz');
insert into channels(ChannelChgName,ChannelEngName) values('后摇','Post-rock');
insert into channels(ChannelChgName,ChannelEngName) values('古典','Classical');
insert into channels(ChannelChgName,ChannelEngName) values('乡村','Country');
insert into channels(ChannelChgName,ChannelEngName) values('新世纪','NewAge');
insert into channels(ChannelChgName,ChannelEngName) values('说唱','Hiphop');
insert into channels(ChannelChgName,ChannelEngName) values('华语流行','Cpop');
insert into channels(ChannelChgName,ChannelEngName) values('日韩流行','JKpop');
insert into channels(ChannelChgName,ChannelEngName) values('欧美流行','Pop');


CREATE TABLE `praises` (
PraiseID int(11)  NOT NULL AUTO_INCREMENT COMMENT '点赞ID（从100起）',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '视频合拍ID',
P_Uid int(11)  NULL DEFAULT 0 COMMENT '点赞用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '点赞时间',
DDate   timestamp  NULL DEFAULT 0     COMMENT '取消时间',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '状态(0删除/1进行中)',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`PraiseID`),
UNIQUE KEY `idx_VWeiboID_Uid` (`VWeiboID`,`P_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `fowards` (
FowardID int(11)  NOT NULL AUTO_INCREMENT COMMENT '转发ID（从100起）',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '视频合拍ID',
F_Uid int(11)  NULL DEFAULT 0 COMMENT '转发用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '转发时间',
DDate   timestamp  NULL DEFAULT 0     COMMENT '取消时间',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '状态(0删除/1进行中)',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`FowardID`),
INDEX `idx_VWeiboID` (`VWeiboID`),
INDEX `idx_Uid`(`F_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE `comments` (
CommentID int(11)  NOT NULL AUTO_INCREMENT COMMENT '评论ID（从100起）',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '视频合拍ID',
C_Uid int(11)  NULL DEFAULT 0 COMMENT '评论用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '评论时间',
DDate   timestamp  NULL DEFAULT 0     COMMENT '取消时间',
Location varchar(128) NULL DEFAULT ''  COMMENT '发表位置',
Latitude double(10,5) NULL DEFAULT 0 COMMENT '发表纬度',
Longitude double(10,5) NULL DEFAULT 0 COMMENT '发表经度',
Cotent  varchar(128) NULL DEFAULT '' COMMENT '评论内容',
AtList varchar(512) NULL DEFAULT '' COMMENT '视频合拍at列表',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '状态(0删除/1进行中)',
RptCount int(11) NOT NULL DEFAULT 1 COMMENT '被举报次数（超过4则自动隐藏）',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`CommentID`),
INDEX `idx_Uid` (`C_Uid`),
INDEX `idx_VWeiboID` (`VWeiboID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `letters` (
LetterID int(11)  NOT NULL AUTO_INCREMENT COMMENT '私信ID（从100起）',
F_Uid int(11)  NULL DEFAULT 0 COMMENT '发起者',
T_Uid int(11)  NULL DEFAULT 0 COMMENT '接收者',
Content varchar(1024) NULL DEFAULT '' COMMENT '私信内容',
CDate timestamp  NULL DEFAULT 0  COMMENT '发表时间',
Status int(11) NULL DEFAULT 1 COMMENT '阅读状态',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`LetterID`),
INDEX `idx_F_Uid` (`F_Uid`),
INDEX `idx_T_Uid` (`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


drop table topics;
CREATE TABLE `topics` (
TopicID int(11)  NOT NULL AUTO_INCREMENT COMMENT '话题ID（从100起）',
TopicName varchar(128) NULL DEFAULT '' COMMENT '话题名称',
T_Uid int(11)  NOT NULL  COMMENT '创建者的用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '创建时间',
Description varchar(128) NULL DEFAULT '' COMMENT '话题描述',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`TopicID`),
unique key `idx_TopicName` (`TopicName`),
index `idx_T_Uid` (`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

drop table topicrss;
CREATE TABLE `topicrss` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT '自增ID',
TopicName int(11) NOT NULL DEFAULT 0 COMMENT '话题名称',
T_Uid int(11)  NULL DEFAULT 0 COMMENT '用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '订阅时间',
DDate   timestamp  NULL DEFAULT 0     COMMENT '取消时间',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '状态(0删除/1进行中)',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`ID`),
unique KEY `idx_TopicName_Uid` (`TopicName`,`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ensembles` (
EnsembleID int(11)  NOT NULL AUTO_INCREMENT COMMENT '合奏ID（从100起）',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '视频合拍ID',
E_Uid int(11)  NULL DEFAULT 0 COMMENT '合奏用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '合奏时间',
DDate   timestamp  NULL DEFAULT 0     COMMENT '取消时间',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '状态(0删除/1进行中)',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`EnsembleID`),
UNIQUE KEY `idx_VWeiboID_Uid` (`VWeiboID`,`E_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


//异步消息表
CREATE TABLE `messages` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT '消息ID',
OType int(11) NOT NULL DEFAULT 0 COMMENT '操作类型',
Param int(11)  NULL DEFAULT 0 COMMENT '操作参数',
CDate   timestamp  NULL DEFAULT 0    COMMENT '创建时间',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


//用户消息表
drop table userinboxs;
CREATE TABLE `userinboxs` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT '消息ID',
Uid int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
MsgType int(11) NOT NULL DEFAULT 0 COMMENT '我的动态/好友动态',
S_Uid int(11) NOT NULL DEFAULT 0 COMMENT '源用户ID',
OType int(11) NOT NULL DEFAULT 0 COMMENT '操作类型',
D_Uid int(11) NOT NULL DEFAULT 0 COMMENT '目标用户ID',
D_ID int(11) NOT NULL DEFAULT 0 COMMENT '目标ID（依据OType决定存储的数字）',
CDate timestamp  NULL DEFAULT 0  COMMENT '插入时间',
Status int(11)  NULL DEFAULT 0  COMMENT '是否已读',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`ID`),
index (`Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

//用户报告问题
CREATE TABLE `questions` (
Uid int(11) NOT NULL DEFAULT 0 COMMENT 'ID',
Uid int(11) NOT NULL DEFAULT 0 COMMENT '用户ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '最近更新时间',
Msg varchar(256) NULL DEFAULT '' COMMENT '问题描述',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

//用户消息表
drop table VTtables;
CREATE TABLE `VTtables` (
VWeiboID int(11)  NOT NULL AUTO_INCREMENT COMMENT '合拍ID',
TopicID  int(11) NOT NULL DEFAULT 0 COMMENT '话题ID',
Score int(11) NOT NULL DEFAULT 0 COMMENT '合拍评分',
CDate timestamp  NULL DEFAULT 0  COMMENT '创建时间',
Field1 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field2 varchar(64) NULL DEFAULT '' COMMENT '预留',
Field3 int(11) NULL DEFAULT 0 COMMENT '预留',
Field4 int(11) NULL DEFAULT 0 COMMENT '预留',
PRIMARY KEY (`VWeiboID`,`TopicID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

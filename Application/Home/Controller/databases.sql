insert into mysql.user(Host,User,Password) values("localhost","grassu",password("grassu"));
create database grassu;
grant all privileges on grassu.* to grassu@localhost identified by 'grassu';
drop table users;
CREATE TABLE `users` (
Uid     int(11) NOT NULL AUTO_INCREMENT COMMENT '�û�id(��10000��)',
Unick varchar(32)  NOT NULL DEFAULT '' COMMENT '�û��ǳƣ������޸ģ�����ҲҪΨһ',
UPwd varchar(32)  NOT NULL DEFAULT ''  COMMENT '�û����룬MD5ֵ',
UGender int(11) NOT NULL DEFAULT '-1'  COMMENT '�û��Ա�-1���ܣ�0Ů��1��',
UBirthday timestamp  NULL DEFAULT 0   COMMENT '�û�����',
U_CityID int(11)   NOT NULL DEFAULT  0 COMMENT '�û�λ��ID��ʡ��/����ID',
UEmail varchar(64)   NULL DEFAULT  '' COMMENT '�û�����',
UMobile varchar(16)   NULL DEFAULT '' COMMENT '�û��ֻ�',
U_ChannelID int(11)   NOT NULL DEFAULT 0 COMMENT '�û���ȤƵ��',
UAvatar varchar(64)  NOT NULL DEFAULT ''  COMMENT 'ͷ���ַ',
URemarks varchar(128)  NOT NULL DEFAULT ''  COMMENT '�û�һ�仰����',
USource varchar(64)   NOT NULL DEFAULT ''  COMMENT '�û�ע����Դ��Ϣ',
UToken varchar(64)  NOT NULL DEFAULT ''  COMMENT '��¼token',
URegDate timestamp  NOT NULL DEFAULT 0  COMMENT 'ע������',
ULLoginDate timestamp  NOT NULL DEFAULT 0  COMMENT '�ϴε�¼ʱ���¼ʱ��',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`Uid`),
UNIQUE KEY `idx_UMobile` (`UMobile`),
KEY `idx_Unick` (`Unick`),
KEY `idx_UEmail` (`UEmail`),
KEY `idx_UToken` (`UToken`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
//alter table users auto_increment = 10000;

//�û����ñ�
CREATE TABLE `usersets` (
Uid     int(11) NOT NULL AUTO_INCREMENT COMMENT '�û�id',
Sets  varchar(1024)  NOT NULL DEFAULT '' COMMENT '�û����ô�',
UDate timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP  COMMENT '�޸�����',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;




CREATE TABLE `devices` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT 'ID',
U_Uid int(11) NOT NULL DEFAULT 0 COMMENT '��ϵͳ���û�ID',
AppID varchar(64) NOT NULL DEFAULT '' COMMENT 'appid',
UserID varchar(64) NULL DEFAULT ''  COMMENT '�ٶ����ɵ�userid',
Utime timestamp  NULL DEFAULT 0     COMMENT '����ʱ��',
Source int(11) NULL DEFAULT 0  COMMENT 'IOS:�豸�ı��� Android:channel_id�豸����',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`ID`),
UNIQUE KEY `idx_Uid` (`U_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `citys` (
CityID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID����1000��',
Code varchar(8) NOT NULL DEFAULT '' COMMENT '��������',
CityName varchar(64) NOT NULL DEFAULT '' COMMENT '������������',
PvcName varchar(64)  NULL DEFAULT '' COMMENT 'ʡ����������',
Latitude   double(10,5) NULL DEFAULT 0     COMMENT '����γ��',
Longitude double(10,5)  NULL DEFAULT 0     COMMENT '���о���',
Type int(11) NULL DEFAULT 0  COMMENT '����0/ʡ��1',
Status int(11) NULL DEFAULT 1  COMMENT '״̬0:����1:����',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`CityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
insert into citys(Code,CityName,PvcName) values('010','����','����');
insert into citys(Code,CityName,PvcName) values('021','�Ϻ�','�Ϻ�');
insert into citys(Code,CityName,PvcName) values('022','���','���');
insert into citys(Code,CityName,PvcName) values('023','����','����');
insert into citys(Code,CityName,PvcName) values('0551','�Ϸ�','����');
insert into citys(Code,CityName,PvcName) values('0553','�ߺ�','����');
insert into citys(Code,CityName,PvcName) values('0556','����','����');
insert into citys(Code,CityName,PvcName) values('0552','����','����');
insert into citys(Code,CityName,PvcName) values('0558','����','����');
insert into citys(Code,CityName,PvcName) values('0565','����','����');
insert into citys(Code,CityName,PvcName) values('0566','����','����');
insert into citys(Code,CityName,PvcName) values('0550','����','����');
insert into citys(Code,CityName,PvcName) values('1558','����','����');
insert into citys(Code,CityName,PvcName) values('0559','��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0561','����','����');
insert into citys(Code,CityName,PvcName) values('0554','����','����');
insert into citys(Code,CityName,PvcName) values('0564','����','����');
insert into citys(Code,CityName,PvcName) values('0555','��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0557','����','����');
insert into citys(Code,CityName,PvcName) values('0562','ͭ��','����');
insert into citys(Code,CityName,PvcName) values('0563','����','����');
insert into citys(Code,CityName,PvcName) values('0591','����','����');
insert into citys(Code,CityName,PvcName) values('0592','����','����');
insert into citys(Code,CityName,PvcName) values('0595','Ȫ��','����');
insert into citys(Code,CityName,PvcName) values('0597','����','����');
insert into citys(Code,CityName,PvcName) values('0593','����','����');
insert into citys(Code,CityName,PvcName) values('0599','��ƽ','����');
insert into citys(Code,CityName,PvcName) values('0594','����','����');
insert into citys(Code,CityName,PvcName) values('0598','����','����');
insert into citys(Code,CityName,PvcName) values('0596','����','����');
insert into citys(Code,CityName,PvcName) values('0931','����','����');
insert into citys(Code,CityName,PvcName) values('0943','����','����');
insert into citys(Code,CityName,PvcName) values('0932','����','����');
insert into citys(Code,CityName,PvcName) values('0935','���','����');
insert into citys(Code,CityName,PvcName) values('0937','��Ȫ','����');
insert into citys(Code,CityName,PvcName) values('0933','ƽ��','����');
insert into citys(Code,CityName,PvcName) values('0934','����','����');
insert into citys(Code,CityName,PvcName) values('1935','����','����');
insert into citys(Code,CityName,PvcName) values('0938','��ˮ','����');
insert into citys(Code,CityName,PvcName) values('0936','��Ҵ','����');
insert into citys(Code,CityName,PvcName) values('0941','����','����');
insert into citys(Code,CityName,PvcName) values('1937','������','����');
insert into citys(Code,CityName,PvcName) values('0930','����','����');
insert into citys(Code,CityName,PvcName) values('2935','¤��','����');
insert into citys(Code,CityName,PvcName) values('020','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0755','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0756','�麣','�㶫');
insert into citys(Code,CityName,PvcName) values('0769','��ݸ','�㶫');
insert into citys(Code,CityName,PvcName) values('0757','��ɽ','�㶫');
insert into citys(Code,CityName,PvcName) values('0752','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0750','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0760','��ɽ','�㶫');
insert into citys(Code,CityName,PvcName) values('0754','��ͷ','�㶫');
insert into citys(Code,CityName,PvcName) values('0759','տ��','�㶫');
insert into citys(Code,CityName,PvcName) values('0768','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0762','��Դ','�㶫');
insert into citys(Code,CityName,PvcName) values('0663','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0668','ï��','�㶫');
insert into citys(Code,CityName,PvcName) values('0753','÷��','�㶫');
insert into citys(Code,CityName,PvcName) values('0763','��Զ','�㶫');
insert into citys(Code,CityName,PvcName) values('0751','�ع�','�㶫');
insert into citys(Code,CityName,PvcName) values('0660','��β','�㶫');
insert into citys(Code,CityName,PvcName) values('0662','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0766','�Ƹ�','�㶫');
insert into citys(Code,CityName,PvcName) values('0758','����','�㶫');
insert into citys(Code,CityName,PvcName) values('0771','����','����');
insert into citys(Code,CityName,PvcName) values('0779','����','����');
insert into citys(Code,CityName,PvcName) values('0770','���Ǹ�','����');
insert into citys(Code,CityName,PvcName) values('0773','����','����');
insert into citys(Code,CityName,PvcName) values('0772','����','����');
insert into citys(Code,CityName,PvcName) values('1771','����','����');
insert into citys(Code,CityName,PvcName) values('1772','����','����');
insert into citys(Code,CityName,PvcName) values('0774','����','����');
insert into citys(Code,CityName,PvcName) values('0778','�ӳ�','����');
insert into citys(Code,CityName,PvcName) values('0775','����','����');
insert into citys(Code,CityName,PvcName) values('1755','���','����');
insert into citys(Code,CityName,PvcName) values('1774','����','����');
insert into citys(Code,CityName,PvcName) values('0777','����','����');
insert into citys(Code,CityName,PvcName) values('0776','��ɫ','����');
insert into citys(Code,CityName,PvcName) values('0851','����','����');
insert into citys(Code,CityName,PvcName) values('0853','��˳','����');
insert into citys(Code,CityName,PvcName) values('0852','����','����');
insert into citys(Code,CityName,PvcName) values('0858','����ˮ','����');
insert into citys(Code,CityName,PvcName) values('0857','�Ͻ�','����');
insert into citys(Code,CityName,PvcName) values('0855','ǭ����','����');
insert into citys(Code,CityName,PvcName) values('0859','ǭ����','����');
insert into citys(Code,CityName,PvcName) values('0854','ǭ��','����');
insert into citys(Code,CityName,PvcName) values('0856','ͭ��','����');
insert into citys(Code,CityName,PvcName) values('0898','����','����');
insert into citys(Code,CityName,PvcName) values('0899','����','����');
insert into citys(Code,CityName,PvcName) values('0802','��ɳ��','����');
insert into citys(Code,CityName,PvcName) values('0801','��ͤ��','����');
insert into citys(Code,CityName,PvcName) values('0803','������','����');
insert into citys(Code,CityName,PvcName) values('0804','������','����');
insert into citys(Code,CityName,PvcName) values('0806','������','����');
insert into citys(Code,CityName,PvcName) values('0807','����','����');
insert into citys(Code,CityName,PvcName) values('2802','�ֶ���','����');
insert into citys(Code,CityName,PvcName) values('1896','�ٸ���','����');
insert into citys(Code,CityName,PvcName) values('0809','��ˮ��','����');
insert into citys(Code,CityName,PvcName) values('1894','��','����');
insert into citys(Code,CityName,PvcName) values('1899','������','����');
insert into citys(Code,CityName,PvcName) values('1892','�Ͳ���','����');
insert into citys(Code,CityName,PvcName) values('1898','����','����');
insert into citys(Code,CityName,PvcName) values('1893','�Ĳ�','����');
insert into citys(Code,CityName,PvcName) values('1897','��ָɽ','����');
insert into citys(Code,CityName,PvcName) values('0805','����','����');
insert into citys(Code,CityName,PvcName) values('0311','ʯ��ׯ','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0312','����','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0314','�е�','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0310','����','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0315','��ɽ','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0335','�ػʵ�','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0317','����','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0318','��ˮ','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0316','�ȷ�','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0319','��̨','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0313','�żҿ�','�ӱ�');
insert into citys(Code,CityName,PvcName) values('0371','֣��','����');
insert into citys(Code,CityName,PvcName) values('0379','����','����');
insert into citys(Code,CityName,PvcName) values('0378','����','����');
insert into citys(Code,CityName,PvcName) values('0374','���','����');
insert into citys(Code,CityName,PvcName) values('0372','����','����');
insert into citys(Code,CityName,PvcName) values('0375','ƽ��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0392','�ױ�','����');
insert into citys(Code,CityName,PvcName) values('0391','����','����');
insert into citys(Code,CityName,PvcName) values('1391','��Դ','����');
insert into citys(Code,CityName,PvcName) values('0395','���','����');
insert into citys(Code,CityName,PvcName) values('0377','����','����');
insert into citys(Code,CityName,PvcName) values('0393','���','����');
insert into citys(Code,CityName,PvcName) values('0398','����Ͽ','����');
insert into citys(Code,CityName,PvcName) values('0370','����','����');
insert into citys(Code,CityName,PvcName) values('0373','����','����');
insert into citys(Code,CityName,PvcName) values('0376','����','����');
insert into citys(Code,CityName,PvcName) values('0396','פ���','����');
insert into citys(Code,CityName,PvcName) values('0394','�ܿ�','����');
insert into citys(Code,CityName,PvcName) values('0451','������','������');
insert into citys(Code,CityName,PvcName) values('0459','����','������');
insert into citys(Code,CityName,PvcName) values('0452','�������','������');
insert into citys(Code,CityName,PvcName) values('0454','��ľ˹','������');
insert into citys(Code,CityName,PvcName) values('0457','���˰���','������');
insert into citys(Code,CityName,PvcName) values('0456','�ں�','������');
insert into citys(Code,CityName,PvcName) values('0468','�׸�','������');
insert into citys(Code,CityName,PvcName) values('0467','����','������');
insert into citys(Code,CityName,PvcName) values('0453','ĵ����','������');
insert into citys(Code,CityName,PvcName) values('0464','��̨��','������');
insert into citys(Code,CityName,PvcName) values('0455','�绯','������');
insert into citys(Code,CityName,PvcName) values('0469','˫Ѽɽ','������');
insert into citys(Code,CityName,PvcName) values('0458','����','������');
insert into citys(Code,CityName,PvcName) values('027','�人','����');
insert into citys(Code,CityName,PvcName) values('0710','����','����');
insert into citys(Code,CityName,PvcName) values('0719','ʮ��','����');
insert into citys(Code,CityName,PvcName) values('0714','��ʯ','����');
insert into citys(Code,CityName,PvcName) values('0711','����','����');
insert into citys(Code,CityName,PvcName) values('0718','��ʩ','����');
insert into citys(Code,CityName,PvcName) values('0713','�Ƹ�','����');
insert into citys(Code,CityName,PvcName) values('0716','����','����');
insert into citys(Code,CityName,PvcName) values('0724','����','����');
insert into citys(Code,CityName,PvcName) values('0722','����','����');
insert into citys(Code,CityName,PvcName) values('0717','�˲�','����');
insert into citys(Code,CityName,PvcName) values('1728','����','����');
insert into citys(Code,CityName,PvcName) values('2728','Ǳ��','����');
insert into citys(Code,CityName,PvcName) values('0728','����','����');
insert into citys(Code,CityName,PvcName) values('0712','Т��','����');
insert into citys(Code,CityName,PvcName) values('0715','����','����');
insert into citys(Code,CityName,PvcName) values('1719','��ũ��','����');
insert into citys(Code,CityName,PvcName) values('0731','��ɳ','����');
insert into citys(Code,CityName,PvcName) values('0730','����','����');
insert into citys(Code,CityName,PvcName) values('0732','��̶','����');
insert into citys(Code,CityName,PvcName) values('0736','����','����');
insert into citys(Code,CityName,PvcName) values('0735','����','����');
insert into citys(Code,CityName,PvcName) values('0734','����','����');
insert into citys(Code,CityName,PvcName) values('0745','����','����');
insert into citys(Code,CityName,PvcName) values('0738','¦��','����');
insert into citys(Code,CityName,PvcName) values('0739','����','����');
insert into citys(Code,CityName,PvcName) values('0737','����','����');
insert into citys(Code,CityName,PvcName) values('0746','����','����');
insert into citys(Code,CityName,PvcName) values('0733','����','����');
insert into citys(Code,CityName,PvcName) values('0744','�żҽ�','����');
insert into citys(Code,CityName,PvcName) values('0743','����','����');
insert into citys(Code,CityName,PvcName) values('0431','����','����');
insert into citys(Code,CityName,PvcName) values('0432','����','����');
insert into citys(Code,CityName,PvcName) values('1433','�ӱ�','����');
insert into citys(Code,CityName,PvcName) values('0436','�׳�','����');
insert into citys(Code,CityName,PvcName) values('0439','��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0437','��Դ','����');
insert into citys(Code,CityName,PvcName) values('0434','��ƽ','����');
insert into citys(Code,CityName,PvcName) values('0438','��ԭ','����');
insert into citys(Code,CityName,PvcName) values('0435','ͨ��','����');
insert into citys(Code,CityName,PvcName) values('025','�Ͼ�','����');
insert into citys(Code,CityName,PvcName) values('0512','����','����');
insert into citys(Code,CityName,PvcName) values('0519','����','����');
insert into citys(Code,CityName,PvcName) values('0518','���Ƹ�','����');
insert into citys(Code,CityName,PvcName) values('0523','̩��','����');
insert into citys(Code,CityName,PvcName) values('0510','����','����');
insert into citys(Code,CityName,PvcName) values('0516','����','����');
insert into citys(Code,CityName,PvcName) values('0514','����','����');
insert into citys(Code,CityName,PvcName) values('0511','��','����');
insert into citys(Code,CityName,PvcName) values('0517','����','����');
insert into citys(Code,CityName,PvcName) values('0513','��ͨ','����');
insert into citys(Code,CityName,PvcName) values('0527','��Ǩ','����');
insert into citys(Code,CityName,PvcName) values('0515','�γ�','����');
insert into citys(Code,CityName,PvcName) values('0791','�ϲ�','����');
insert into citys(Code,CityName,PvcName) values('0797','����','����');
insert into citys(Code,CityName,PvcName) values('0792','�Ž�','����');
insert into citys(Code,CityName,PvcName) values('0798','������','����');
insert into citys(Code,CityName,PvcName) values('0796','����','����');
insert into citys(Code,CityName,PvcName) values('0799','Ƽ��','����');
insert into citys(Code,CityName,PvcName) values('0793','����','����');
insert into citys(Code,CityName,PvcName) values('0790','����','����');
insert into citys(Code,CityName,PvcName) values('0795','�˴�','����');
insert into citys(Code,CityName,PvcName) values('0701','ӥ̶','����');
insert into citys(Code,CityName,PvcName) values('0794','����','����');
insert into citys(Code,CityName,PvcName) values('024','����','����');
insert into citys(Code,CityName,PvcName) values('0411','����','����');
insert into citys(Code,CityName,PvcName) values('0412','��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0415','����','����');
insert into citys(Code,CityName,PvcName) values('0413','��˳','����');
insert into citys(Code,CityName,PvcName) values('0416','����','����');
insert into citys(Code,CityName,PvcName) values('0417','Ӫ��','����');
insert into citys(Code,CityName,PvcName) values('0414','��Ϫ','����');
insert into citys(Code,CityName,PvcName) values('0421','����','����');
insert into citys(Code,CityName,PvcName) values('0418','����','����');
insert into citys(Code,CityName,PvcName) values('0429','��«��','����');
insert into citys(Code,CityName,PvcName) values('0419','����','����');
insert into citys(Code,CityName,PvcName) values('0427','�̽�','����');
insert into citys(Code,CityName,PvcName) values('0410','����','����');
insert into citys(Code,CityName,PvcName) values('0471','���ͺ���','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0472','��ͷ','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0476','���','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0477','������˹','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0474','�����첼','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0473','�ں�','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0482','�˰���','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0470','���ױ���','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0475','ͨ��','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0483','��������','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0478','�����׶�','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0479','���ֹ���','���ɹ�');
insert into citys(Code,CityName,PvcName) values('0951','����','����');
insert into citys(Code,CityName,PvcName) values('0952','ʯ��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0954','��ԭ','����');
insert into citys(Code,CityName,PvcName) values('0953','����','����');
insert into citys(Code,CityName,PvcName) values('1953','����','����');
insert into citys(Code,CityName,PvcName) values('0971','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0973','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0976','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0975','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0972','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0977','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0974','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0970','����','�ຣ');
insert into citys(Code,CityName,PvcName) values('0531','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0532','�ൺ','ɽ��');
insert into citys(Code,CityName,PvcName) values('0631','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0535','��̨','ɽ��');
insert into citys(Code,CityName,PvcName) values('0536','Ϋ��','ɽ��');
insert into citys(Code,CityName,PvcName) values('0538','̩��','ɽ��');
insert into citys(Code,CityName,PvcName) values('0543','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0534','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0546','��Ӫ','ɽ��');
insert into citys(Code,CityName,PvcName) values('0530','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0537','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0635','�ĳ�','ɽ��');
insert into citys(Code,CityName,PvcName) values('0539','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0634','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0633','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0533','�Ͳ�','ɽ��');
insert into citys(Code,CityName,PvcName) values('0632','��ׯ','ɽ��');
insert into citys(Code,CityName,PvcName) values('0351','̫ԭ','ɽ��');
insert into citys(Code,CityName,PvcName) values('0355','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0352','��ͬ','ɽ��');
insert into citys(Code,CityName,PvcName) values('0356','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0354','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0357','�ٷ�','ɽ��');
insert into citys(Code,CityName,PvcName) values('0358','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0349','˷��','ɽ��');
insert into citys(Code,CityName,PvcName) values('0350','����','ɽ��');
insert into citys(Code,CityName,PvcName) values('0359','�˳�','ɽ��');
insert into citys(Code,CityName,PvcName) values('0353','��Ȫ','ɽ��');
insert into citys(Code,CityName,PvcName) values('029','����','����');
insert into citys(Code,CityName,PvcName) values('0915','����','����');
insert into citys(Code,CityName,PvcName) values('0917','����','����');
insert into citys(Code,CityName,PvcName) values('0916','����','����');
insert into citys(Code,CityName,PvcName) values('0914','����','����');
insert into citys(Code,CityName,PvcName) values('0919','ͭ��','����');
insert into citys(Code,CityName,PvcName) values('0913','μ��','����');
insert into citys(Code,CityName,PvcName) values('0910','����','����');
insert into citys(Code,CityName,PvcName) values('0911','�Ӱ�','����');
insert into citys(Code,CityName,PvcName) values('0912','����','����');
insert into citys(Code,CityName,PvcName) values('028','�ɶ�','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0816','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0832','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0827','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0838','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0818','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0826','�㰲','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0839','��Ԫ','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0833','��ɽ','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0830','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('1833','üɽ','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('1832','�ڽ�','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0817','�ϳ�','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0812','��֦��','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0825','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0831','�˱�','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0835','�Ű�','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0813','�Թ�','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0837','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0836','����','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0834','��ɽ','�Ĵ�');
insert into citys(Code,CityName,PvcName) values('0891','����','����');
insert into citys(Code,CityName,PvcName) values('0892','�տ���','����');
insert into citys(Code,CityName,PvcName) values('0897','����','����');
insert into citys(Code,CityName,PvcName) values('0895','����','����');
insert into citys(Code,CityName,PvcName) values('0894','��֥','����');
insert into citys(Code,CityName,PvcName) values('0896','����','����');
insert into citys(Code,CityName,PvcName) values('0893','ɽ��','����');
insert into citys(Code,CityName,PvcName) values('0991','��³ľ��','�½�');
insert into citys(Code,CityName,PvcName) values('0993','ʯ����','�½�');
insert into citys(Code,CityName,PvcName) values('0995','��³��','�½�');
insert into citys(Code,CityName,PvcName) values('0999','����','�½�');
insert into citys(Code,CityName,PvcName) values('0997','������','�½�');
insert into citys(Code,CityName,PvcName) values('0906','����̩','�½�');
insert into citys(Code,CityName,PvcName) values('0996','����','�½�');
insert into citys(Code,CityName,PvcName) values('0909','��������','�½�');
insert into citys(Code,CityName,PvcName) values('0994','����','�½�');
insert into citys(Code,CityName,PvcName) values('0902','����','�½�');
insert into citys(Code,CityName,PvcName) values('0903','����','�½�');
insert into citys(Code,CityName,PvcName) values('0998','��ʲ','�½�');
insert into citys(Code,CityName,PvcName) values('0990','��������','�½�');
insert into citys(Code,CityName,PvcName) values('0908','������','�½�');
insert into citys(Code,CityName,PvcName) values('0901','����','�½�');
insert into citys(Code,CityName,PvcName) values('0871','����','����');
insert into citys(Code,CityName,PvcName) values('0877','��Ϫ','����');
insert into citys(Code,CityName,PvcName) values('0878','����','����');
insert into citys(Code,CityName,PvcName) values('0872','����','����');
insert into citys(Code,CityName,PvcName) values('0873','���','����');
insert into citys(Code,CityName,PvcName) values('0874','����','����');
insert into citys(Code,CityName,PvcName) values('0691','��˫����','����');
insert into citys(Code,CityName,PvcName) values('0870','��ͨ','����');
insert into citys(Code,CityName,PvcName) values('0875','��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0692','�º�','����');
insert into citys(Code,CityName,PvcName) values('0887','����','����');
insert into citys(Code,CityName,PvcName) values('0888','����','����');
insert into citys(Code,CityName,PvcName) values('0883','�ٲ�','����');
insert into citys(Code,CityName,PvcName) values('0886','ŭ��','����');
insert into citys(Code,CityName,PvcName) values('0879','�ն�','����');
insert into citys(Code,CityName,PvcName) values('0876','��ɽ','����');
insert into citys(Code,CityName,PvcName) values('0571','����','�㽭');
insert into citys(Code,CityName,PvcName) values('0574','����','�㽭');
insert into citys(Code,CityName,PvcName) values('0573','����','�㽭');
insert into citys(Code,CityName,PvcName) values('0575','����','�㽭');
insert into citys(Code,CityName,PvcName) values('0577','����','�㽭');
insert into citys(Code,CityName,PvcName) values('0580','��ɽ','�㽭');
insert into citys(Code,CityName,PvcName) values('0572','����','�㽭');
insert into citys(Code,CityName,PvcName) values('0579','��','�㽭');
insert into citys(Code,CityName,PvcName) values('0578','��ˮ','�㽭');
insert into citys(Code,CityName,PvcName) values('0576','̨��','�㽭');
insert into citys(Code,CityName,PvcName) values('0570','����','�㽭');
insert into citys(Code,CityName,PvcName) values('1852','���','���');
insert into citys(Code,CityName,PvcName) values('1852','����','����');


drop table vweibos;
CREATE TABLE `vweibos` (
VWeiboID int(11)  NOT NULL AUTO_INCREMENT COMMENT '��Ƶ����ID����100��',
V_Uid int(11) NOT NULL DEFAULT 0 COMMENT '������Ƶ���ĵ��û�id',
ORGVWpicID varchar(64)  NOT NULL DEFAULT ''  COMMENT 'ԭʼ��Ƶ����ͼ���',
ORGVWVideoID varchar(64)  NOT NULL DEFAULT ''  COMMENT 'ԭʼ��Ƶ���',
ORGVWVideoParam varchar(64)  NOT NULL DEFAULT ''  COMMENT 'ԭʼ��Ƶ��Ų���',
VWpicID varchar(64)  NOT NULL DEFAULT ''  COMMENT '��Ƶ����ͼ���',
VWVideoID varchar(64)  NOT NULL DEFAULT ''  COMMENT '��Ƶ���',
VWVideoParam varchar(64)  NOT NULL DEFAULT ''  COMMENT '��Ƶ��Ų���',
ViewNum int(11)  NULL DEFAULT 1     COMMENT '�������',
PubTime timestamp  NULL DEFAULT 0  COMMENT '��Ƶ���ķ���ʱ��',
Location varchar(128) NULL DEFAULT ''  COMMENT '����λ��',
Latitude double(10,5) NULL DEFAULT 0 COMMENT '����γ��',
Longitude double(10,5) NULL DEFAULT 0 COMMENT '������',
ChannelID int(11) NULL DEFAULT 0 COMMENT 'Ƶ��ID',
Authority int(11) NULL DEFAULT 0 COMMENT '��ƵȨ�ޣ����ޡ�ת�������ۡ������Ȩ�ޣ�',
Content varchar(256) NULL DEFAULT '' COMMENT '��Ƶ��������',
TopicList varchar(256) NULL DEFAULT '' COMMENT '��Ƶ���Ļ����б�',
AtList varchar(512) NULL DEFAULT '' COMMENT '��Ƶ����at�б�',
PraiseNum int(11) NULL DEFAULT 0 COMMENT '�����޴���',
FowardNum int(11) NULL DEFAULT 0 COMMENT '��ת������',
CommentNum int(11) NULL DEFAULT 0 COMMENT '�����۴���',
EnsembleNum int(11) NULL DEFAULT 0 COMMENT '���������',
Recommended int(11) NULL DEFAULT 0 COMMENT '�Ƿ��Ƽ�',
RptNum int(11) NULL DEFAULT 0 COMMENT '���ٱ�����������4���Զ����أ�',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`VWeiboID`),
INDEX `idx_Uid` (`V_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

drop table fellows;
CREATE TABLE `fellows` (
FellowID int(11)  NOT NULL AUTO_INCREMENT COMMENT '��ע���ID����1000��',
F_Uid int(11) NOT NULL DEFAULT 0 COMMENT '��ע�û�ID',
T_Uid int(11)  NULL DEFAULT 0 COMMENT '����ע�û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '�״ι�עʱ��',
DDate   timestamp  NULL DEFAULT 0     COMMENT 'ȡ����עʱ��',
FStatus  int(11) NOT NULL DEFAULT 1 COMMENT '��ǰ��ע״̬(0ȡ��/1��ע��)',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`FellowID`),
UNIQUE KEY `idx_Uid` (`F_Uid`,`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

drop table channels;
CREATE TABLE `channels` (
ChannelID int(11)  NOT NULL AUTO_INCREMENT COMMENT 'Ƶ��ID����100��',
ParentID  int(11) NULL DEFAULT 0 COMMENT '����Ƶ��ID',
ChannelChgName varchar(64) NULL DEFAULT '' COMMENT 'Ƶ����������',
ChannelEngName varchar(64) NULL DEFAULT '' COMMENT 'Ƶ��Ӣ������',
Description varchar(128) NULL DEFAULT '' COMMENT 'Ƶ������',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`ChannelID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
alter table channels auto_increment = 100;
insert into channels(ChannelChgName,ChannelEngName) values('ҡ��','Rock');
insert into channels(ChannelChgName,ChannelEngName) values('��ҥ','Folk');
insert into channels(ChannelChgName,ChannelEngName) values('����','Indie');
insert into channels(ChannelChgName,ChannelEngName) values('Ӣ��','Brit-pop');
insert into channels(ChannelChgName,ChannelEngName) values('���','Punk');
insert into channels(ChannelChgName,ChannelEngName) values('����','Metal');
insert into channels(ChannelChgName,ChannelEngName) values('������Ϸ','ACG');
insert into channels(ChannelChgName,ChannelEngName) values('����','Electronic');
insert into channels(ChannelChgName,ChannelEngName) values('����','Gothic');
insert into channels(ChannelChgName,ChannelEngName) values('��ʿ','Jazz');
insert into channels(ChannelChgName,ChannelEngName) values('��ҡ','Post-rock');
insert into channels(ChannelChgName,ChannelEngName) values('�ŵ�','Classical');
insert into channels(ChannelChgName,ChannelEngName) values('���','Country');
insert into channels(ChannelChgName,ChannelEngName) values('������','NewAge');
insert into channels(ChannelChgName,ChannelEngName) values('˵��','Hiphop');
insert into channels(ChannelChgName,ChannelEngName) values('��������','Cpop');
insert into channels(ChannelChgName,ChannelEngName) values('�պ�����','JKpop');
insert into channels(ChannelChgName,ChannelEngName) values('ŷ������','Pop');


CREATE TABLE `praises` (
PraiseID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID����100��',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '��Ƶ����ID',
P_Uid int(11)  NULL DEFAULT 0 COMMENT '�����û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '����ʱ��',
DDate   timestamp  NULL DEFAULT 0     COMMENT 'ȡ��ʱ��',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '״̬(0ɾ��/1������)',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`PraiseID`),
UNIQUE KEY `idx_VWeiboID_Uid` (`VWeiboID`,`P_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `fowards` (
FowardID int(11)  NOT NULL AUTO_INCREMENT COMMENT 'ת��ID����100��',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '��Ƶ����ID',
F_Uid int(11)  NULL DEFAULT 0 COMMENT 'ת���û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT 'ת��ʱ��',
DDate   timestamp  NULL DEFAULT 0     COMMENT 'ȡ��ʱ��',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '״̬(0ɾ��/1������)',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`FowardID`),
INDEX `idx_VWeiboID` (`VWeiboID`),
INDEX `idx_Uid`(`F_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE `comments` (
CommentID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID����100��',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '��Ƶ����ID',
C_Uid int(11)  NULL DEFAULT 0 COMMENT '�����û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '����ʱ��',
DDate   timestamp  NULL DEFAULT 0     COMMENT 'ȡ��ʱ��',
Location varchar(128) NULL DEFAULT ''  COMMENT '����λ��',
Latitude double(10,5) NULL DEFAULT 0 COMMENT '����γ��',
Longitude double(10,5) NULL DEFAULT 0 COMMENT '������',
Cotent  varchar(128) NULL DEFAULT '' COMMENT '��������',
AtList varchar(512) NULL DEFAULT '' COMMENT '��Ƶ����at�б�',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '״̬(0ɾ��/1������)',
RptCount int(11) NOT NULL DEFAULT 1 COMMENT '���ٱ�����������4���Զ����أ�',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`CommentID`),
INDEX `idx_Uid` (`C_Uid`),
INDEX `idx_VWeiboID` (`VWeiboID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `letters` (
LetterID int(11)  NOT NULL AUTO_INCREMENT COMMENT '˽��ID����100��',
F_Uid int(11)  NULL DEFAULT 0 COMMENT '������',
T_Uid int(11)  NULL DEFAULT 0 COMMENT '������',
Content varchar(1024) NULL DEFAULT '' COMMENT '˽������',
CDate timestamp  NULL DEFAULT 0  COMMENT '����ʱ��',
Status int(11) NULL DEFAULT 1 COMMENT '�Ķ�״̬',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`LetterID`),
INDEX `idx_F_Uid` (`F_Uid`),
INDEX `idx_T_Uid` (`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


drop table topics;
CREATE TABLE `topics` (
TopicID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID����100��',
TopicName varchar(128) NULL DEFAULT '' COMMENT '��������',
T_Uid int(11)  NOT NULL  COMMENT '�����ߵ��û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '����ʱ��',
Description varchar(128) NULL DEFAULT '' COMMENT '��������',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`TopicID`),
unique key `idx_TopicName` (`TopicName`),
index `idx_T_Uid` (`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

drop table topicrss;
CREATE TABLE `topicrss` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID',
TopicName int(11) NOT NULL DEFAULT 0 COMMENT '��������',
T_Uid int(11)  NULL DEFAULT 0 COMMENT '�û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '����ʱ��',
DDate   timestamp  NULL DEFAULT 0     COMMENT 'ȡ��ʱ��',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '״̬(0ɾ��/1������)',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`ID`),
unique KEY `idx_TopicName_Uid` (`TopicName`,`T_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `ensembles` (
EnsembleID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID����100��',
VWeiboID int(11) NOT NULL DEFAULT 0 COMMENT '��Ƶ����ID',
E_Uid int(11)  NULL DEFAULT 0 COMMENT '�����û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '����ʱ��',
DDate   timestamp  NULL DEFAULT 0     COMMENT 'ȡ��ʱ��',
CStatus  int(11) NOT NULL DEFAULT 1 COMMENT '״̬(0ɾ��/1������)',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`EnsembleID`),
UNIQUE KEY `idx_VWeiboID_Uid` (`VWeiboID`,`E_Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


//�첽��Ϣ��
CREATE TABLE `messages` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT '��ϢID',
OType int(11) NOT NULL DEFAULT 0 COMMENT '��������',
Param int(11)  NULL DEFAULT 0 COMMENT '��������',
CDate   timestamp  NULL DEFAULT 0    COMMENT '����ʱ��',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


//�û���Ϣ��
drop table userinboxs;
CREATE TABLE `userinboxs` (
ID int(11)  NOT NULL AUTO_INCREMENT COMMENT '��ϢID',
Uid int(11) NOT NULL DEFAULT 0 COMMENT '�û�ID',
MsgType int(11) NOT NULL DEFAULT 0 COMMENT '�ҵĶ�̬/���Ѷ�̬',
S_Uid int(11) NOT NULL DEFAULT 0 COMMENT 'Դ�û�ID',
OType int(11) NOT NULL DEFAULT 0 COMMENT '��������',
D_Uid int(11) NOT NULL DEFAULT 0 COMMENT 'Ŀ���û�ID',
D_ID int(11) NOT NULL DEFAULT 0 COMMENT 'Ŀ��ID������OType�����洢�����֣�',
CDate timestamp  NULL DEFAULT 0  COMMENT '����ʱ��',
Status int(11)  NULL DEFAULT 0  COMMENT '�Ƿ��Ѷ�',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`ID`),
index (`Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

//�û���������
CREATE TABLE `questions` (
Uid int(11) NOT NULL DEFAULT 0 COMMENT 'ID',
Uid int(11) NOT NULL DEFAULT 0 COMMENT '�û�ID',
CDate   timestamp  NULL DEFAULT 0    COMMENT '�������ʱ��',
Msg varchar(256) NULL DEFAULT '' COMMENT '��������',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`Uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

//�û���Ϣ��
drop table VTtables;
CREATE TABLE `VTtables` (
VWeiboID int(11)  NOT NULL AUTO_INCREMENT COMMENT '����ID',
TopicID  int(11) NOT NULL DEFAULT 0 COMMENT '����ID',
Score int(11) NOT NULL DEFAULT 0 COMMENT '��������',
CDate timestamp  NULL DEFAULT 0  COMMENT '����ʱ��',
Field1 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field2 varchar(64) NULL DEFAULT '' COMMENT 'Ԥ��',
Field3 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
Field4 int(11) NULL DEFAULT 0 COMMENT 'Ԥ��',
PRIMARY KEY (`VWeiboID`,`TopicID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

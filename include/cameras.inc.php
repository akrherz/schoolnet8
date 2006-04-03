<?php
$cameras = Array(
  "SAMI4" => Array("sts" => mktime(16,0,0,4,14,2005), "ets" => time(),
                "name" => "Ames", "active" => true,
                "ip" => "129.186.185.13", "county" => "Story"),
  "SBZI4" => Array("sts" => mktime(19,0,0,5,13,2004), "ets" => time(),
		"name" => "Blank Park Zoo", "active" => true,
		"ip" => "63.227.72.138", "county" => "Polk"),
  "SCAI4" => Array("sts" => mktime(0,0,0,10,25,2003), "ets" => mktime(4,30,0,5,11,2005),
		"name" => "Carroll", "active" => false, "removed" => true,
		"iservice" => "Glidden REC", 
		"iserviceurl" => "http://www.gliddenrec.com",
		"ip" => "216.203.109.40", "county" => "Carroll"),
  "SCSI4" => Array("sts" => mktime(0,0,0, 9,20,2003), 
					"ets" => time(),
		"name" => "Creston", "active" => true,
		"iservice" => "Iowa Telecom.", 
		"iserviceurl" => "http://www.iowatelecom.com/",
		"ip" => "69.66.29.70", "county" => "Union"),
  "SGLI4" => Array("sts" => mktime(0,0,0,1,19,2006), "ets" => time(),
        "name" => "Glidden", "active" => true,
        "ip" => "67.54.189.108", "county" => "Carroll"),
  "SHUI4" => Array("sts" => mktime(0,0,0,12, 4,2003), "ets" => time(),
		"name" => "Humboldt", "active" => true,
		"iservice" => "TRV Communications",
		"iserviceurl" => "http://www.trvnet.net/",
		"ip" => "64.71.70.33", "county" => "Humboldt"),
  "SINI4" => Array("sts" => mktime(0,0,0, 2,25,2004), "ets" => time(),
		"name" => "Indianola", "active" => true,
		"iservice" => "Clarke Electric Cooperative, Inc", 
		"iserviceurl" => "http://www.clarkeelectric.org/",
		"hosted" => "Indianola Municipal Utilities",
		"hostedurl" => "http://www.i-m-u.com",
		"ip" => "216.203.126.2", "county" => "Warren"),
  "S03I4" => Array("sts" => mktime(0,0,0, 8, 5,2003),
                   "ets" => mktime(0,0,0, 8,21,2003),
		"name" => "Iowa State Fair 2003", "active" => false, "removed" => true,
		"ip" => "129.186.26.63"),
  "SJEI4" => Array("sts" => mktime(0,0,0, 7,23,2003), "ets" => time(),
		"name" => "Jefferson", "active" => true,
		"iservice" => "Jefferson Telephone Co.", 
		"iserviceurl" => "http://showcase.netins.net/web/jtco/",
		"ip" => "216.248.72.225", "county" => "Greene"), 
  "SCEI4" => Array("sts" => mktime(23,0,0,6, 18,2004), "ets" => time(),
		"name" => "Lake Rathbun", "active" => true,
		"hosted" => "Rathbun Lake Army Corps of Engineers",
		"hostedurl" => "http://www.nwk.usace.army.mil/rathbun/rathbun_home.htm",
		"iservice" => "SIRIS, A KeyOn Company",
		"iserviceurl" => "http://www.keyon.com/",
		"ip" => "69.66.225.18", "county" => "Appanoose"),
  "SKNI4" => Array("sts" => mktime(17,0,0,9, 26,2005), "ets" => time(),
		"name" => "Knoxville", "active" => true,
		"ip" => "69.66.33.2", "county" => "Marion"),
  "SNEI4" => Array("sts" => mktime(19,0,0, 4,16,2004), "ets" => time(),
		"name" => "Newton", "active" => true,
		"iservice" => "Consumers Energy", 
		"iserviceurl" => "http://www.consumersenergy.net/",
		"ip" => "216.203.109.40", "county" => "Jasper"),
  "SMAI4" => Array("sts" => mktime(0,0,0,10, 1,2003), 
         "ets" => mktime(14,0,0,10, 26,2004),
		"name" => "Marshalltown", "active" => false, "removed" => true,
		"iservice" => "Consumers Energy", 
		"iserviceurl" => "http://www.consumersenergy.net/",
		"ip" => "216.203.127.250", "county" => "Marshall"),
  "SMDI4" => Array("sts" => mktime(0,0,0,9, 16,2005),
         "ets" => time(),
        "name" => "Madrid", "active" => true,
        "ip" => "69.66.30.247", "county" => "Boone"),
  "SPAI4" => Array("sts" => mktime(22,0,0,5,19,2004), "ets" => time(),
		"name" => "Panora", "active" => true,
		"iservice" => "Panora Coop Telephone Assoc.",
		"iserviceurl" => "http://www.panoratelco.com",
		"ip" => "66.43.253.165", "county" => "Guthrie"),
  "SPEI4" => Array("sts" => mktime(0,0,0,10,16,2003), "ets" => time(),
		"name" => "Pella", "active" => true,
		"iservice" => "Iowa Telecom.", 
		"iserviceurl" => "http://www.iowatelecom.com/",
		"ip" => "69.66.44.147", "county" => "Marion"),
  "SROI4" => Array("sts" => mktime(0,0,0, 2,25,2004), "ets" => time(),
		"name" => "Rockwell City", "active" => true,
		"iservice" => "Iowa Telecom.", 
		"iserviceurl" => "http://www.iowatelecom.com/",
		"ip" => "69.66.29.140", "county" => "Calhoun"),
  "SPKI4" => Array("sts" => mktime(11,0,0, 7,2,2005), "ets" => time(),
		"name" => "Saylorville Lake", "active" => true,
		"ip" => "67.41.111.192", "county" => "Polk"),
  "STQI4" => Array("sts" => mktime(0,0,0, 5,18,2005), "ets" => time(),
		"name" => "Tama", "active" => true,
		"ip" => "169.203.116.12", "county" => "Tama"),
  "SWBI4" => Array("sts" => mktime(0,0,0, 8,28,2003), "ets" => time(),
		"name" => "Webster City", "active" => true,
		"iservice" => "WMTEL.NET", "iserviceurl" => "http://www.wmtel.net",
		"ip" => "216.51.195.93", "county" => "Hamilton"),
  "SWII4" => Array("sts" => mktime(17,30,0, 5,14,2004), "ets" => time(),
		"name" => "Winterset", "active" => true,
		"iservice" => "I-rule.net", "iserviceurl" => "http://www.i-rule.net/",
        "hosted" => "Madison County Chamber and Development Group", 
        "hostedurl" => "http://www.madisoncounty.com",
		"ip" => "209.234.86.215", "county" => "Madison"),
 );

?>

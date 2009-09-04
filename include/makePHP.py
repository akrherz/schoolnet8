#!/mesonet/python/bin/python

from pyIEM import iemdb, stationTable
import mx.DateTime
i = iemdb.iemdb()
mesosite = i['mesosite']
st = stationTable.stationTable("/mesonet/TABLES/kcci.stns")

o = open('cameras.inc.php', 'w')
o.write("""<?php
$cxref = Array(
""")
for stid in st.ids:
  sql = "select id, distance(geom,(select geom from stations WHERE id = '%s'))\
    as distance  from webcams \
    WHERE online = 't' and network = 'KCCI' ORDER by distance" % (stid, )

  rs = mesosite.query(sql).dictresult()
  if stid == "SAKI4":
    rs[0]['id'] = 'KCCI-034'
    rs[1]['id'] = 'KCCI-018'

  o.write("'%s' => Array('%s', '%s', '%s', '%s', '%s'),\n" % (stid, \
    rs[0]['id'], rs[1]['id'], rs[2]['id'], rs[3]['id'], rs[4]['id']) )


o.write("""); """)

rs = mesosite.query("SELECT *, x(geom), y(geom), case when removed then 'True' else 'False' end as r, case when online then 'True' else 'False' end as c from webcams WHERE network = 'KCCI' ORDER by name ASC").dictresult()

o.write("""
$cameras = Array(
""");

for i in range(len(rs)):
  sts = mx.DateTime.strptime(rs[i]['sts'][:16], '%Y-%m-%d %H:%M')
  estr = "time()"
  if (rs[i]['ets'] is not None):
    ets = mx.DateTime.strptime(rs[i]['ets'][:16], '%Y-%m-%d %H:%M')
    estr = "mktime(%s,0,0,%s, %s,%s)" % (ets.hour, ets.month, ets.day, ets.year)

  o.write("""
"%s" => Array("sts" => mktime(%s,0,0,%s, %s,%s), "ets" => %s,
    "name" => "%s", "removed" => %s, "active" => %s, "lat" => %s, "lon" => %s,
    "hosted" => "%s",
    "hostedurl" => "%s",
    "moviebase" => "%s",
    "iservice" => "%s",
    "iserviceurl" => "%s", "network" => "%s",
    "sponsor" => "%s", "sponsorurl" => "%s",
    "ip" => "%s", "county" => "%s", "port" => "%s"),""" \
   % (rs[i]['id'], sts.hour, sts.month, sts.day, sts.year, estr, \
      rs[i]['name'], rs[i]['r'], rs[i]['c'], rs[i]['y'], rs[i]['x'], \
      rs[i]['hosted'], rs[i]['hostedurl'], rs[i]['moviebase'], \
      rs[i]['iservice'], rs[i]['iserviceurl'], rs[i]['network'], \
      rs[i]['sponsor'], rs[i]['sponsorurl'], \
      rs[i]['ip'], rs[i]['county'], \
      rs[i]['port']) )

o.write("""); ?>""")
o.close()

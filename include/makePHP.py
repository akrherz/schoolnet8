import mx.DateTime
import psycopg2.extras
from pyiem.network import Table as NetworkTable
MESOSITE = psycopg2.connect(database="mesosite", host='iemdb', user='nobody')
mcursor = MESOSITE.cursor(cursor_factory=psycopg2.extras.DictCursor)

nt = NetworkTable("KCCI")

o = open('cameras.inc.php', 'w')
o.write("""<?php
$cxref = Array(
""")
for stid in nt.sts.keys():
  sql = """select id, ST_distance(geom,(select geom from stations 
      WHERE id = '%s' and network = 'KCCI'))
    as distance  from webcams 
    WHERE online = 't' and network = 'KCCI' ORDER by distance ASC LIMIT 5""" % (stid, )

  mcursor.execute(sql)
  rs = mcursor.fetchall()
  if stid == "SAKI4":
    rs[0][0] = 'KCCI-034'
    rs[1][0] = 'KCCI-018'

  o.write("'%s' => Array('%s', '%s', '%s', '%s', '%s'),\n" % (stid, \
    rs[0][0], rs[1][0], rs[2][0], rs[3][0], rs[4][0]) )


o.write("""); """)

mcursor.execute("""SELECT *, ST_x(geom), ST_y(geom), 
    case when removed then 'True' else 'False' end as r, 
    case when online then 'True' else 'False' end as c from webcams 
    WHERE network = 'KCCI' ORDER by name ASC""")

o.write("""
$cameras = Array(
""");

for row in mcursor:
  estr = "time()"
  if (row['ets'] is not None):
    ets =row['ets']
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
   % (row['id'], row['sts'].hour, row['sts'].month, row['sts'].day, row['sts'].year, estr, \
      row['name'], row['r'], row['c'], row['st_y'], row['st_x'], \
      row['hosted'], row['hostedurl'], row['moviebase'], \
      row['iservice'], row['iserviceurl'], row['network'], \
      row['sponsor'], row['sponsorurl'], \
      row['ip'], row['county'], \
      row['port']) )

o.write("""); ?>""")
o.close()

#!/mesonet/python/bin/python
# Something to dump current warnings to a shapefile

import shapelib, dbflib, wellknowntext, mx.DateTime, pg, zipfile, os
mydb = pg.connect("postgis")
mydb.query("SET TIME ZONE 'GMT'")

os.chdir("/tmp")

# We set one minute into the future, so to get expiring warnings
# out of the shapefile
eTS = mx.DateTime.gmt() + mx.DateTime.RelativeDateTime(minutes=+1)

shp = shapelib.create("current_ww", shapelib.SHPT_POLYGON)

dbf = dbflib.create("current_ww")
dbf.add_field("ISSUED", dbflib.FTString, 12, 0)
dbf.add_field("EXPIRED", dbflib.FTString, 12, 0)
dbf.add_field("TYPE", dbflib.FTString, 3, 0)
dbf.add_field("GTYPE", dbflib.FTString, 1, 0)

rs = mydb.query("SELECT * from warnings WHERE issue < '%s' and \
	expire > '%s' " % (eTS.strftime("%Y-%m-%d %H:%M"), \
	eTS.strftime("%Y-%m-%d %H:%M")) ).dictresult()

cnt = 0
for i in range(len(rs)):
	s = rs[i]["geom"]
	if (s == None or s == ""):
		continue
	f = wellknowntext.convert_well_known_text(s)

	g = rs[i]["gtype"]
	t = rs[i]["type"]
	issue = mx.DateTime.strptime(rs[i]["issue"][:16], "%Y-%m-%d %H:%M")
	expire = mx.DateTime.strptime(rs[i]["expire"][:16],"%Y-%m-%d %H:%M")
	d = {}
	d["ISSUED"] = issue.strftime("%Y%m%d%H%M")
	d["EXPIRED"] = expire.strftime("%Y%m%d%H%M")
	d["TYPE"] = t
	d["GTYPE"] = g

	obj = shapelib.SHPObject(shapelib.SHPT_POLYGON, 1, f )
	shp.write_object(-1, obj)
	dbf.write_record(cnt, d)
	del(obj)
	cnt += 1

if (cnt == 0):
	obj = shapelib.SHPObject(shapelib.SHPT_POLYGON, 1, [[(0.1, 0.1), (0.2, 0.2), (0.3, 0.1), (0.1, 0.1)]])
	d = {}
	d["ISSUED"] = "200000000000"
	d["EXPIRED"] = "200000000000"
	d["TYPE"] = "ZZZ"
	d["GTYPE"] = "Z"
	shp.write_object(-1, obj)
	dbf.write_record(0, d)

del(shp)
del(dbf)
z = zipfile.ZipFile("current_ww.zip", 'w')
z.write("current_ww.shp")
z.write("current_ww.shx")
z.write("current_ww.dbf")
z.close()

os.system("/home/ldm/bin/pqinsert current_ww.zip")

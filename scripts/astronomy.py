# Need something to update cache of moon information

import ephem
import pg
import moon
dbconn = pg.connect("kcci")

phase = moon.MoonPhase()
pt = phase.phase_text

moon = ephem.Moon()

rs = dbconn.query("SELECT x(geom) as lon, y(geom) as lat, id from stations").dictresult()
for i in range(len(rs)):
  ob = ephem.Observer()
  ob.lat = "%s" % (rs[i]['lat'],)
  ob.long = "%s" % (rs[i]['lon'],)
  rise = ob.next_rising(moon) 
  setting = ob.next_setting(moon)
  dbconn.query("""UPDATE stations SET moonphase = '%s', moonrise = '%s',
      moonset = '%s' WHERE id = '%s'""" % (pt, rise, setting, rs[i]['id']))

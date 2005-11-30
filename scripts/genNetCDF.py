#!/mesonet/python/bin/python
# Something to generate a NetCDF

from Scientific.IO import NetCDF
import Numeric, iemAccess

#tmpf,dwpf,relh,feel,alti,altiTend,drctTxt,sped,sknt,drct,20gu,gmph,gtim,pday,pmonth,tmpf_min,tmpf_max,max_sknt,drct_max,max_sped,max_drctTxt,max_srad,

def Main():
  nc = NetCDF.NetCDFFile("test.nc", 'w')
  nc.createDimension("recnum", None)
  nc.createDimension("stidlen", 5)
  stid = nc.createVariable("stid", Numeric.Character, ("recnum", "stidlen") )
  lat = nc.createVariable("latitude", Numeric.Float8, ("recnum") )
  lon = nc.createVariable("longitude", Numeric.Float8, ("recnum") )
  tmpf = nc.createVariable("tmpf", Numeric.Int, ("recnum",) )
  dwpf = nc.createVariable("dwpf", Numeric.Int, ("recnum",) )
  relh = nc.createVariable("relh", Numeric.Int, ("recnum",) )
  feel = nc.createVariable("feel", Numeric.Int, ("recnum",) )
  pres = nc.createVariable("pres", Numeric.Float8, ("recnum",) )
  presTend = nc.createVariable("presTend", Numeric.Character, ("recnum",) )
  drct = nc.createVariable("drct", Numeric.Int, ("recnum",) )
  sknt = nc.createVariable("sknt", Numeric.Int, ("recnum",) )
  gust = nc.createVariable("gust", Numeric.Int, ("recnum",) )
  pday = nc.createVariable("pday", Numeric.Float8, ("recnum",) )
  pmonth = nc.createVariable("pmonth", Numeric.Float8, ("recnum",) )
  tmpf_min = nc.createVariable("tmpf_min", Numeric.Int, ("recnum",) )
  tmpf_max = nc.createVariable("tmpf_max", Numeric.Int, ("recnum",) )
  srad_max = nc.createVariable("srad_max", Numeric.Int, ("recnum",) )

  nc.sync()

  rs = iemAccess.iemdb.query("SELECT *, x(c.geom) as lon, y(c.geom) as lat, \
   from current c LEFT JOIN summary s \
   USING (station) WHERE c.network = 'KCCI' and s.day = 'TODAY' ").dictresult()

  for i in range(len(rs)):
    stid[i] = Numeric.array(rs[i]['station'], 'c')
    lat[i] = rs[i]['lat']
    lon[i] = rs[i]['lon']
    tmpf[i] = rs[i]['tmpf']
    dwpf[i] = rs[i]['dwpf']

  nc.close()
Main()

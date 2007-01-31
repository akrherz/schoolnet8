#!/usr/bin/env python

from xml.dom.ext.reader import Sax2
from xml.dom.ext import StripXml
from xml import xpath
import re, pdb, datetime, urllib, time

now = datetime.datetime.now()
rest_uri = "http://www.weather.gov/forecasts/xml/SOAP_server/ndfdSOAPclientByDay.php?lat=%s&lon=%s&format=12+hourly&startDate=%s&numDays=7&Submit=Submit" % (42.0, -95.0, now.strftime("%Y-%m-%d") )

urllib.urlretrieve(rest_uri, '/tmp/test.xml')

x = open('/tmp/test.xml').read()
x = re.sub(">(\W){2,}<", "><", x.replace("\n", "") )

o = open('/tmp/test2.xml', 'w')
o.write( x )
o.close()

reader = Sax2.Reader()
doc = StripXml(reader.fromString(x))


# Load up timearrays
ta = {}
tnames = {}
a = xpath.Evaluate('dwml/data/time-layout', doc)
for elem in a:
  q = xpath.Evaluate('layout-key', elem)
  lk = q[0]._get_childNodes()[0].data

  sts = xpath.Evaluate('start-valid-time', elem)
  ta[lk] = [0] * len(sts)
  for i in range(len(sts)):
    dt = datetime.datetime.fromtimestamp(time.mktime(time.strptime(sts[i]._get_childNodes()[0].data[:16], '%Y-%m-%dT%H:%M'))) 
    ta[lk][i] = dt
    tnames[dt] = sts[i].getAttribute("period-name")

# Max Temps
#  maximum, minimum
temps = {}
a = xpath.Evaluate('dwml/data/parameters/temperature', doc)
for elem in a:
  thisTemp = elem.getAttribute("type")
  temps[ thisTemp ] = {}
  thisTimeAxis = elem.getAttribute("time-layout")
  print thisTemp
  nodelist = elem._get_childNodes()
  for i in range(1, len(nodelist)):
    temps[thisTemp][ ta[thisTimeAxis][i-1] ] = nodelist[i]._get_childNodes()[0].data 


# weather
#  wind
weather = {}
a = xpath.Evaluate('dwml/data/parameters/weather', doc)
for elem in a:
  nodelist = elem._get_childNodes()
  thisTimeAxis = elem.getAttribute("time-layout")
  for i in range(1, len(nodelist)):
    weather[ ta[thisTimeAxis][i-1] ] = (nodelist[i]).getAttribute("weather-summary")

# Conditions
#  forecast-NWS
icons = {}
a = xpath.Evaluate('dwml/data/parameters/conditions-icon', doc)
for elem in a:
  thisTemp = elem.getAttribute("type")
  thisTimeAxis = elem.getAttribute("time-layout")
  print thisTemp
  nodelist = elem._get_childNodes()
  for i in range(1, len(nodelist)):
    l = nodelist[i]._get_childNodes()
    if (len(l) == 0):
      print 'nill', ta[thisTimeAxis][i-1]
    else:
      icons[ ta[thisTimeAxis][i-1] ] = l[0].data

#print temps
#print weather
#print icons

#for i in range(len(ta['k-p12h-n14-3'])):
o = open('/tmp/test.html', 'w')
o.write("<table cellspacing=0 cellpadding=1 width=\"670\">")
o.write("<tr>")
for i in range(9):
  ts = ta['k-p12h-n14-3'][i]
  o.write("<th width=\"11%%\">%s</th>" % (tnames[ts],))
o.write("</tr>\n")

o.write("<tr>")
for i in range(9):
  ts = ta['k-p12h-n14-3'][i]
  o.write("<td><img src=\"%s\"</td>" % (icons[ts],))
o.write("</tr>\n")

o.write("<tr>")
for i in range(9):
  ts = ta['k-p12h-n14-3'][i]
  o.write("<td>%s</td>" % (weather[ts],))
o.write("</tr>\n")

o.write("<tr>")
for i in range(9):
  ts = ta['k-p12h-n14-3'][i]
  if (temps['maximum'].has_key(ts)):
    o.write("<td>Hi %s&deg;F</td>" % (temps['maximum'][ts],) )
  else:
    o.write("<td>Lo %s&deg;F</td>" % (temps['minimum'][ts],) )
o.write("</tr>\n")

o.write("</table>")
o.close()


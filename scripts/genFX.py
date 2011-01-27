#!/mesonet/python/bin/python

import re, pdb, datetime, urllib, time, sys, shutil, traceback
import mx.DateTime
from xml.etree import ElementTree

ENDPOINT = "http://www.weather.gov/forecasts/xml/SOAP_server/ndfdSOAPclientByDay.php?"

def generator(sid, lat, lon):

    now = datetime.datetime.now()
    rest_uri = "%slat=%s&lon=%s&format=12+hourly&startDate=%s&numDays=7&Submit=Submit" % (
                                                 ENDPOINT, lat, lon, now.strftime("%Y-%m-%d") )
    doc = ElementTree.XML( urllib.urlopen(rest_uri).read() )

    taxis = {}
    tnames = {}
    for elem in doc.findall("./data/time-layout"):
        key = elem.find("layout-key").text
        taxis[key] = []
        tnames[key] = []
        for elem2 in elem.findall("./start-valid-time"):
            ts = mx.DateTime.strptime( elem2.text[:16], '%Y-%m-%dT%H:%M') 
            taxis[key].append( ts )
            tnames[key].append( elem2.attrib.get("period-name",None) )
    
    temps = {}
    for elem in doc.findall("./data/parameters/temperature"):
        name = elem.find("./name")
        temps[ name.text ] = {'taxis': elem.attrib['time-layout'], 
                              'vals': []}
        for v in elem.findall("./value"):
            temps[ name.text ]['vals'].append( v.text )
    
    
    for elem in doc.findall("./data/parameters/weather"):
        weather = {'taxis': elem.attrib['time-layout'], 
                              'vals': []}
        for v in elem.findall("./weather-conditions"):
            weather['vals'].append( v.attrib.get('weather-summary',None) )
    
    for elem in doc.findall("./data/parameters/conditions-icon"):
        icons = {'taxis': elem.attrib['time-layout'], 
                              'vals': []}
        for v in elem.findall("./icon-link"):
            icons['vals'].append( v.text )
    
    if not temps.has_key('Daily Maximum Temperature'):
        print '--------------------------------------------------'
        print 'Whoa, could not find daily maximum temperature key'
        print doc
        return
    
    data = {}
    for val, tm in zip( temps['Daily Maximum Temperature']['vals'],
                tnames[ temps['Daily Maximum Temperature']['taxis'] ] ):
        if not data.has_key(tm):
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['high'] = val
    
    for val, tm in zip( temps['Daily Minimum Temperature']['vals'],
                tnames[ temps['Daily Minimum Temperature']['taxis'] ] ):
        if not data.has_key(tm):
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['low'] = val
    
    for val, tm in zip( icons['vals'],
                tnames[ icons['taxis'] ] ):
        if not data.has_key(tm):
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['icon'] = val
    
    for val, tm in zip( weather['vals'],
                tnames[ weather['taxis'] ] ):
        if not data.has_key(tm):
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['wather'] = val
    
    
    #print "MAX TEMPS", tnames[ temps['Daily Maximum Temperature']['taxis'] ]
    #print "MIN TEMPS", tnames[ temps['Daily Minimum Temperature']['taxis'] ]
    #print "WEATHER", tnames[ weather['taxis'] ]
    #print "ICONS", tnames[ icons['taxis'] ]
    
    o = open('%s.html' % (sid,), 'w')
    o.write("<!-- %s -->" % (sid,))
    o.write("<table cellspacing=\"0\" cellpadding=\"1\" width=\"640\">")
    
    o.write("<tr>")
    for tm in tnames[ icons['taxis'] ][:9]:
        o.write("<th width=\"11%%\">%s</th>" % (tm, ) )
    o.write("</tr>")
    
    o.write("<tr>")
    for tm in tnames[ icons['taxis'] ][:9]:
        o.write("<td><img src=\"%s\" alt=\"fx\"/></td>" % (data[tm]['icon'], ) )
    o.write("</tr>")
    
    o.write("<tr>")
    for tm in tnames[ icons['taxis'] ][:9]:
        if data[tm]['high'] is not None:
            d = data[tm]['high']
            l = 'Hi'
        else:
            d = data[tm]['low']
            l = 'Lo'
        o.write("<td>%s %s&deg;F</td>" % (l, d) )
    o.write("</tr>")
    
    o.write("</table>")
    o.close()

if (__name__ == "__main__"):
  generator(sys.argv[1], sys.argv[2], sys.argv[3])

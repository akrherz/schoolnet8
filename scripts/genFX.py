"""
Create cached forecast html from the NDFD
"""
import re
import pdb
import datetime
import urllib2
import time
import sys
import shutil
import traceback
from xml.etree import ElementTree

ENDPOINT = "http://www.weather.gov/forecasts/xml/SOAP_server/ndfdSOAPclientByDay.php?"

def generator(sid, lat, lon, rerun=False):

    now = datetime.datetime.now()
    rest_uri = "%slat=%s&lon=%s&format=12+hourly&startDate=%s&numDays=7&Submit=Submit" % (
                                                 ENDPOINT, lat, lon, now.strftime("%Y-%m-%d") )
    try:
        data = urllib2.urlopen(rest_uri, timeout=30).read()
    except:
        traceback.print_exc()
        return False
    try:
        doc = ElementTree.XML( data )
    except Exception, exp:
        print "%s got exception: %s sample: |%s|" % (sid, exp, data[:100])
        return False

    taxis = {}
    tnames = {}
    for elem in doc.findall("./data/time-layout"):
        key = elem.find("layout-key").text
        taxis[key] = []
        tnames[key] = []
        for elem2 in elem.findall("./start-valid-time"):
            ts = datetime.datetime.strptime( elem2.text[:16], '%Y-%m-%dT%H:%M') 
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
        if rerun:
            print '--------------------------------------------------'
            print 'Whoa, could not find daily maximum temperature key'
            print sid
            print doc
        return False
    
    data = {}
    for val, tm in zip( temps['Daily Maximum Temperature']['vals'],
                tnames[ temps['Daily Maximum Temperature']['taxis'] ] ):
        if not data.has_key(tm):
            #print 'Adding tm for high', tm, data.keys()
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['high'] = val
    
    for val, tm in zip( temps['Daily Minimum Temperature']['vals'],
                tnames[ temps['Daily Minimum Temperature']['taxis'] ] ):
        if not data.has_key(tm):
            #print 'Adding tm for mintmp', tm, data.keys()
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['low'] = val
    
    for val, tm in zip( icons['vals'],
                tnames[ icons['taxis'] ] ):
        if not data.has_key(tm):
            #print 'Adding tm for icons', tm
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['icon'] = val
    
    for val, tm in zip( weather['vals'], tnames[ weather['taxis'] ] ):
        #print 'Wx Axis', tm, val
        if not data.has_key(tm):
            #print 'Adding tm for weather', tm
            data[tm] = {'high': None, 'low': None, 'weather': None, 'icon': None}
        data[tm]['weather'] = val
    
    
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
        if data[tm]['icon'] is not None:
            o.write("<td><img src=\"%s\" alt=\"fx\"/></td>" % (data[tm]['icon'], ) )
        else:
            o.write("<td></td>")
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
    return True
    

if (__name__ == "__main__"):
  if not generator(sys.argv[1], sys.argv[2], sys.argv[3]):
      time.sleep(60)
      generator(sys.argv[1], float(sys.argv[2]) + 0.01, 
                float(sys.argv[3]) + 0.01, rerun=True)

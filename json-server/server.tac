# Create JSON server for SNET data

from nwnserver import hubclient

from twisted.protocols.basic import LineReceiver
from twisted.internet import reactor
from twisted.protocols import policies, basic
from twisted.internet import protocol
from twisted.python.logfile import DailyLogFile
#from twisted.internet.app import Application
from twisted.application import service, internet
from twisted.enterprise import adbapi
from twisted.web import server, xmlrpc, resource

import simplejson

from sys import stdout
import traceback
import re, mx.DateTime, sys, os
from pyIEM import mesonet, nwnformat

db = {}                 

class NWNClientFactory(hubclient.HubClientProtocolBaseFactory):
    maxDelay = 60.0
    factor = 1.0
    initialDelay = 60.0

    def processData(self, data):
        if (data == None or data == ""):
            return

        tokens = re.split("\s+", data)
        if (len(tokens) != 14): 
             return

        siteID = int(tokens[1])
        if not mesonet.snetConv.has_key( siteID ):
            return
        nwsli = mesonet.snetConv[ siteID ]
        if not db.has_key(nwsli):
            db[nwsli] = nwnformat.nwnformat(do_avg_winds=False)
            db[nwsli].sid = siteID

        db[nwsli].parseLineRT(tokens)  


class GetJSON(resource.Resource):

    def __init__(self):
        resource.Resource.__init__(self)

    def render(self, request):
        res = {'data': [], }
        for key in db.keys():
          if (db[key].ts is None):
            continue
          res['data'].append( {'t': db[key].tmpf, 'd': db[key].dwpf, 's': db[key].sped, 'r': db[key].drctTxt, 'm': db[key].ts.strftime("%d %b %I:%M:%S %p"), 'p':db[key].pDay, 'x':db[key].xsped, 'h':db[key].xtmpf, 'l':db[key].ntmpf } )

        return simplejson.dumps( res )

class SiteJson(resource.Resource):
    log = DailyLogFile('jsonlog', 'logs/')
    def __init__(self):
        resource.Resource.__init__(self)

    def render(self, request):
        res = {'data': [], }
        sid = request.args['site'][0]
        if not db.has_key(sid):
            request.write( simplejson.dumps("ERROR") )
            request.finish()
            return server.NOT_DONE_YET
        res['data'].append( {
          'ts': db[sid].ts.strftime("%d %b %I:%M:%S %p"), 
          'tmpf': db[sid].tmpf, 
          'dwpf': db[sid].dwpf, 
          'relh': db[sid].humid, 
          'feel': "%.0f" % (db[sid].feel,), 
          'xtmpf': db[sid].xtmpf, 
          'ntmpf': db[sid].ntmpf,
          'sped': db[sid].sped, 
          'drct': db[sid].drctTxt, 
          'xsped': db[sid].xsped, 
          'xdrct': db[sid].xdrctTxt, 
          'pres': db[sid].pres, 
          'pmonth': db[sid].pMonth,
          'srad': db[sid].rad,
          'xsrad': db[sid].xsrad,
          'pday': db[sid].pDay })
        request.write( simplejson.dumps(res) )
        request.finish()
        return server.NOT_DONE_YET



class RootResource(resource.Resource):
    def __init__(self):
        resource.Resource.__init__(self)
        self.putChild('get-json', GetJSON())
        self.putChild('get-site', SiteJson())



application = service.Application("NWN 2 JSON")
serviceCollection = service.IServiceCollection(application)

remoteServerUser = 'kcci999'
remoteServerPass = 'kcci999'
clientFactory = NWNClientFactory(remoteServerUser,
                                 remoteServerPass)

client = internet.TCPClient('129.186.185.33', 14996, clientFactory)
client.setServiceParent( serviceCollection )

web = server.Site( RootResource() )
r = internet.TCPServer(8005, web)
r.setServiceParent(serviceCollection)


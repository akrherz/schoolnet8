# Create JSON server for SNET data

from nwnserver import hubclient

from twisted.protocols.basic import LineReceiver
from twisted.internet import reactor
from twisted.protocols import policies, basic
from twisted.internet import protocol
#from twisted.internet.app import Application
from twisted.application import service, internet
from twisted.enterprise import adbapi
from twisted.web import server, xmlrpc, resource

import simplejson

from sys import stdout
import traceback
import re, mx.DateTime, sys, os
from pyIEM import mesonet, nwnformat


class NWNClientFactory(hubclient.HubClientProtocolBaseFactory):
    maxDelay = 60.0
    factor = 1.0
    initialDelay = 60.0

    def processData(self, data):
        reactor.callLater(0, ingestData, data)
                   

db = {}                 

def ingestData(data):
  if (data == None or data == ""):
    return

  tokens = re.split("\s+", data)
  if (len(tokens) != 14): 
    return

  siteID = int(tokens[1])
  if (siteID > 599):
    return
  if (not db.has_key(siteID) ):
    db[siteID] = nwnformat.nwnformat(do_avg_winds=False)
    db[siteID].sid = int(siteID)
    if (mesonet.snetConv.has_key( int(siteID) )):
      db[siteID].nwsli = mesonet.snetConv[int(siteID)]
    else:
      db[siteID].nwsli = siteID

  db[siteID].parseLineRT(tokens)  


class GetJSON(resource.Resource):

    def __init__(self):
        resource.Resource.__init__(self)

    def render(self, request):
        res = {'data': [], }
        for key in db.keys():
          if (db[key].ts is None):
            continue
          res['data'].append( {'i': db[key].nwsli, 't': db[key].tmpf, 'd': db[key].dwpf, 's': db[key].sped, 'r': db[key].drctTxt, 'm': db[key].ts.strftime("%d %b %I:%M:%S %p"), 'p':db[key].pDay, 'x':db[key].xsped, 'h':db[key].xtmpf, 'l':db[key].ntmpf } )

        return simplejson.dumps( res )


class RootResource(resource.Resource):
    def __init__(self):
        resource.Resource.__init__(self)
        self.putChild('get-json', GetJSON())



application = service.Application("NWN 2 JSON")
serviceCollection = service.IServiceCollection(application)

remoteServerUser = 'kcci999'
remoteServerPass = 'kcci999'
clientFactory = NWNClientFactory(remoteServerUser,
                                 remoteServerPass)

client = internet.TCPClient('129.186.185.33', 14998, clientFactory)
client.setServiceParent( serviceCollection )

web = server.Site( RootResource() )
r = internet.TCPServer(8005, web)
r.setServiceParent(serviceCollection)


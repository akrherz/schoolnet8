#!/usr/bin/env python

import xmlrpclib
p = xmlrpclib.ServerProxy('http://rpc.geocoder.us/service/xmlrpc')
res = p.geocode("407 10th Avenue, Slater, IA")
print res

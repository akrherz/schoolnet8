#!/bin/sh

export PATH=/mesonet/python-2.4/bin:$PATH

kill -9 `cat twistd.pid`
sleep 1
twistd --logfile=server.log -y server.tac

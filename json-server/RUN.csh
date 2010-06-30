#!/bin/sh

export PATH=/mesonet/python-2.5/bin:$PATH

kill -9 `cat twistd.pid`
sleep 1
twistd --logfile=logs/server.log -y server.tac

# Startup JSON server, called from crontab @reboot

kill -9 `cat twistd.pid`
sleep 1
twistd --logfile=logs/server.log -y server.tac

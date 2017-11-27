#!/bin/sh
 
if  ps aux | grep -v 'grep' | grep  'read_usb'; then
        sudo kill $(ps ax | grep read_usb | grep -v grep | awk '{ print $1 }')
fi

#if  ps aux | grep -v 'grep' | grep  'moyenne_temp'; then
#        sudo kill $(ps ax | grep moyenne_temp | grep -v grep | awk '{ print $1 }')
#fi

exit 0

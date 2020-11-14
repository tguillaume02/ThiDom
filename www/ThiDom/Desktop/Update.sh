#!/bin/bash

shell_exec('sudo a2enmod expires');
shell_exec('sudo a2enmod http2');
shell_exec('sudo a2enmod headers');


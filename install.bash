#!/bin/bash

# Interface Setup Script
#
# Author: Russell Toris - rctoris@wpi.edu
echo

echo
echo "Interactive World Interface Setup"
echo "Author: Russell Toris - rctoris@wpi.edu"
echo

# check the directory we are working in
DIR=`pwd`
if [[ $DIR != *InteractiveWorldInterface ]]
then
	echo "ERROR: Please run this script in the 'InteractiveWorldInterface' directory."
	exit;
fi

RMS="/var/www/rms"
if [ ! -d "$RMS" ]; then
	echo "ERROR: No RMS installation found in '$RMS'."
	exit;
fi

echo "Copying 'app' scripts to local RMS directory..."
cp app/Controller/*.php $RMS/Controller
cp -r app/View/* $RMS/View
mkdir -p $RMS/webroot/resources
cp -r app/webroot/resources/* $RMS/webroot/resources

echo "Installation complete!"
echo
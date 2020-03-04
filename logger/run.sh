#!/bin/bash

if [ $(dirname $0) = "." ];then
    scriptPath=$(pwd);
else
   scriptPath=$(dirname $0);
fi

cd ${scriptPath};

./logger
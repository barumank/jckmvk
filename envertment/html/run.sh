#!/bin/bash

if [ $(dirname $0) = "." ];then
    scriptPath=$(pwd);
else
   scriptPath=$(dirname $0);
fi

./bin/node.sh 'npm run dev'
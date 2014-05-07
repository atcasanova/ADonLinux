#!/bin/bash
. base

dos2unix $3
sed -i '1d' $3
login=$1
senha=$2

checkLogin

[ $needjoin -eq 1 ] && sudo net ads join -U$login%$senha -W $domain &> /dev/null

cat $3 | xargs -P5 -L1 ./criador2 $login $senha

data=$(date --rfc-3339=seconds | cut -f1-3 -d- | sed 's/ /-/g' | sed 's/:/-/g')
#mv $3 upload/criacao-$loginadm-$data

#!/bin/bash
echo "<meta charset="utf-8">"
[ $# -lt 1 ] && { echo "<p style='color:#c93434;'>Informe um login</p>"; exit 1; }
echo "<table style='width:770px;'><tr style='background:black;color:white'><td><h3>Data e Hora</h3></td><td><h3>Autor</h3></td><td><h3>Ação</h3></td><td><h3>Usuário</h3></td><td><h3>Arquivo</h3></td></tr>" > .tmp$$
for i in $(grep -iH "$*" upload/* | cut -f2 -d\/ | cut -f1 -d,)
do
	acao=$(echo $i | cut -f1 -d-)
	autor=$(echo $i | cut -f2 -d-)
	data=$(echo $i | cut -f3- -d- | cut -f1 -d:)
	login=$(echo $i | cut -f2 -d: | cut -f1 -d,)
	echo "$data;$autor;$acao;$login;${i/:*/}" >> .tmp2$$
done
cat .tmp2$$ | sort -nr | sed 's/^/\<tr\>\<td\>/g;s/\;/\<\/td\>\<td\>/g;s/$/\<\/td\>\<\/tr\>/g' >> .tmp$$
echo "</table>" >> .tmp$$
cat .tmp$$
rm .tmp$$ .tmp2$$

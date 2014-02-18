#!/bin/sh
#
#======================================================================
# Calcula qtd dias entre uma data passada como parametro e 01/01/1980.
# Se nao for passado nenhum parametro a data de hoje sera assumida.
# Obs. Para executar este programa sob o sh, trocar os $((..)) por expr
#======================================================================
# Leia: Linux - Programacao Shell ISBN: 85-7452-048-9
# Autor: Julio Cezar Neves <mailto:julio.neves@...>
# alterado por Alfredo Casanova para alterar a ordem de argumentos
#======================================================================
#
if [ $1 ]
then
	DFim=`echo $1 | cut -f3 -d"/"`
	MFim=`echo $1 | cut -f2 -d"/"`
	AFim=`echo $1 | cut -f1 -d"/"`
else
	DFim=`date +%d`
	MFim=`date +%m`
	AFim=`date +%Y`
fi

TotDias=$((1 + 365 * (AFim - 1980) + (AFim - 1980) / 4))
[ $((AFim % 4)) = 0 -a $MFim -le 2 ] && TotDias=$((TotDias - 1))

for i in `echo "31 28 31 30 31 30 31 31 30 31 30 31" | cut -f-$((MFim - 1)) -d" " 2> /dev/null`
do
	TotDias=$((TotDias + $i))
done
echo $((TotDias + DFim))

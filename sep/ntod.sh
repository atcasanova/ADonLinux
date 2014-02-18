#!/bin/sh
#
#==========================================================
# Recebe uma qtd de dias como parametro, e o transforma em
# uma data, que significa 1/1/1980 + parametro recebido.
# Obs. Para executar este programa sob o sh, trocar por
# $((..)) por expr
#==========================================================
# Leia: Linux - Programacao Shell ISBN: 85-7452-048-9
# Autor: Julio Cezar Neves <mailto:julio.neves@...>
#==========================================================
#
Num=$(($1 - 1))
AFim=$((1980 + (Num / 365)))
DFim=$((Num % 365 - Num / 1460))
MFim=1
for i in 31 28 31 30 31 30 31 31 30 31 30 31
do
[ $DFim -lt $i ] && break
DFim=$((DFim - i))
MFim=$((MFim + 1))
done
[ $DFim -eq 0 ] &&
{
DFim=29
MFim=2
}
[ $DFim -le 9 ] && echo "0$DFim/\c" || echo "$DFim/\c"
[ $MFim -le 9 ] && echo "0$MFim/\c" || echo "$MFim/\c"
echo $AFim

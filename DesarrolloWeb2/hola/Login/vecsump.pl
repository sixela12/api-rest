%programa que da una lista L de enteros, se veriicar que
%existe algun elemento en L que es igual a la suma de los demas
%elementos de L, en caso contrario devolver false
%[1,2,3,6]

%suma_resto(L) devolver cierto si existe un elemento
%              en la L que es la suma de todos 
%              en caso contrario devoler falso 

suma_resto(L):- append(L1,[X|L2],L), append(L1,L2,NewL), sum(NewL,X)

% sum (List,X) Xes la suma de los elementos de la list
sum([],0)
sum([X|L],R):- sum(L,R1), R is R1 + X 
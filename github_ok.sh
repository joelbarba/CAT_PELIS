echo "Procediment per commitar canvis al repositori local i exportar-los a GitHub :"
echo "Selecciona la branca correcte (C = casa / O = oficina) :"
read x
if [ $x == "O" ]; then
	branca="oficina"
else
	branca="casa"
fi

echo "--> GIT CHECKOUT $branca"
git checkout $branca

echo ""
echo "--> GIT STATUS (estat dels fitxers modificats)"
git status
echo "--> GIT ADD (afegir tots els fitxers a Staged) (S/n)"
read x
if [ $x == "n" ]; then
	echo "No s'han afegit els fitxers"
else
	git add *
fi
echo "--> GIT STATUS (fitxers preparats per commitar)"
git status
echo "--> GIT COMMIT : introduir el comentari"
read comentari
git commit -m '$comentari'

echo "Exportar branca $branca a GITHUB"
echo "--> GIT PUSH github $branca (usr=joelbarba, pwd=cocaina02)"
read x
git push github $branca

echo "Avançar branca MASTER a $branca"
echo "--> GIT CHECKOUT MASTER + GIT MERGE $branca"
read x
git checkout master
git merge $branca

echo "Exportar branca MASTER a GITHUB"
echo "--> GIT PUSH github master (usr=joelbarba, pwd=cocaina02)"
read x
git push github master

echo "Tornar a la branca de treball ($branca)"
read x
git checkout $branca

echo "Final"
exit 0;




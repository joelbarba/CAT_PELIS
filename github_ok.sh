echo "Procediment per commitar canvis al repositori local i exportar-los a GitHub :"
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
echo "--> GIT PUSH github master (exportar branca master a GitHub) (usr: joelbarba pwd: cocaina02)"
read x
git push github master



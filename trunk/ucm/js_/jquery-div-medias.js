$(document).ready(function(){ 	// le document est charge
   $("#medias a").click(function(){ 	// on selectionne tous les liens et on definit une action quand on clique dessus
	page=($(this).attr("href")); // on recuperer l' adresse du lien
	$.ajax({  // ajax
		url: page, // url de la page a charger
		cache: false, // pas de mise en cache
		success:function(html){ // si la requete est un succes
			afficher(html);	    // on execute la fonction afficher(donnees)
		},
		error:function(XMLHttpRequest, textStatus, errorThrows){ // erreur durant la requete
		}
	});
	return false; // on desactive le lien
   });
});

function afficher(donnees){ // pour remplacer le contenu du div contenu
	$("#contenu-medias").empty(); // on vide le div
	$("#contenu-medias").append(donnees); // on met dans le div le resultat de la requete ajax
}
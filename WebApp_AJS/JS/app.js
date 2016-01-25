(function() {
    
    var app = angular.module('Modul_Principal', ['door3.css']);

	angular.module('Modul_Principal').controller('CTRL_general', ['$http', '$scope', function($http, $scope) {
        var that = this;

        that.llista_pelis  = [];
        $scope.id_peli_sel = 0;
        $scope.info_peli = { "titol" : "eee"};

        this.loadLlistaPelis = function() {
            // Petició Ajax per la llista de pelis
            $http.get('data/llista_pelis.php').success(function(data) {
                if (data.result_code == 'ok') {
                    that.llista_pelis = data.output_data.llista_pelis;
                    if (that.llista_pelis.length > 0) that.selectPeli(that.llista_pelis[0].id);
                } else {
                    alert('error: ' + data.desc_error);
                }
            });
        }
        
        this.selectPeli = function(id_peli) {
            $scope.id_peli_sel = id_peli;
            that.loadPeli(id_peli);
        };
        

        this.loadPeli = function(id_peli) {
            $scope.id_peli_sel = id_peli;            
            // Petició Ajax per info peli
            $http.get('data/info_peli.php?id_peli=' + id_peli).success(function(data) {
                if (data.result_code == 'ok') {

                    // Carregar les dades de la peli
                    $scope.info_peli = data.output_data;
                    $scope.info_peli.titol_old = data.output_data.titol;
                    
                    if ($scope.info_peli.url_imdb == '') $scope.info_peli.img_imdb = 'img/imdb_logo2.png';
                    else                                 $scope.info_peli.img_imdb = 'img/imdb_logo.png';                    
                    
                    if ($scope.info_peli.url_film == '') $scope.info_peli.img_film = 'img/filmaffinity_logo2.png';
                    else                                 $scope.info_peli.img_film = 'img/filmaffinity_logo.png';
                    
                    if ($scope.info_peli.nom_imatge != '') {
                        nom_img = 'img/cataleg/' + $scope.info_peli.nom_imatge;
                        $('#id_div_box_info_peli_caratula').css('background-image', 'url("' + nom_img + '")');
                    } else {
                        $('#id_div_box_info_peli_caratula').css('background-image', '');
                    }
                    
                    
                } else {
                    alert('error: ' + data.desc_error);
                }
            });
            
        }
    /*
          "output_data"  : {
			      "id_peli"            :   "",
			      "titol"              :   "",
			      "titol_original"     :   "",
			      "nom_imatge"         :   "",
			      "idioma_audio"       :   "",
			      "idioma_subtitols"   :   "",
			      "qualitat_video"     :   "",
			      "qualitat_audio"     :   "",
			      "any_estrena"        :   "",
			      "director"           :   "",
			      "url_imdb"           :   "",
			      "url_film"           :   ""        
*/                  

	}]);
    
    

    angular.module('Modul_Principal').directive('llistaPelis', function() {
        return {
            restrict     : 'EA',
            templateUrl  : 'templates/llista_pelis.html',
            css          : 'templates/llista_pelis.css',
//            scope        : { var1 : "@", var2 : "=" },
            controller   : 'CTRL_general',
            controllerAs : 'CTRL_llista_pelis'
            
//            controllerAs : 'CTRL_llista_pelis',
//            controller   : [ '$http', function($http) {
//	        	var that = this;
//
//				that.llista_pelis = [];
//		        that.id_peli_sel  = 0;
//                
////                that.id_peli_llista = 10;
//
//		        $http.get('data/llista_pelis.php').success(function(data) {
//		        	that.llista_pelis = data;
//		        });
//
//                this.selectPeli = function(id_peli) {
//                     alert('seleccionant ' + id_peli);
////					that.id_peli_sel = id_peli;
////                    $scope.id_peli_sel = id_peli;
////                    id_peli_sel2 = 40;
//                    var1 = 200;
//                    
//                };
//
//            }]
        };
    });
    
    angular.module('Modul_Principal').directive('infoPeli', function() {

        return {
            restrict     : 'E',
            templateUrl  : 'templates/info_peli.html',
            css          : 'templates/info_peli.css',
            controller   : 'CTRL_general',
            controllerAs : 'CTRL_info_peli'
        };
    });       
    

    
//    angular.module('Modul_Principal').directive('infoPeli', function() {
//
//        return {
//            restrict     : 'E',
//            templateUrl  : 'templates/info_peli.html',
//            css          : 'templates/info_peli.css',
//            controllerAs : 'CTRL_info_peli',
//            controller   : [ '$http', function($http) {
//	        	var that = this;
//                this.id_peli_sel = 0;
//                
//                this.loadPeli = function(id_peli) {
//                    that.id_peli = id_peli;
//                    alert('load peli ' + id_peli);
//                }
//                
//            }]
//        };
//    });    

    
/*    
    angular.module('Modul_Pincipal').config(function($routeProvider) {
        $routeProvider
              .when('/peli',  { templateUrl: 'templates/detall_peli.html' })
              .when('/',      { templateUrl: 'templates/detall_peli.html' }) // Pàgina per defecte
              .otherwise(	  { redirectTo : '/' });
        });    
*/
    
    
  

    
/*    
    app.controller('ctrl_llista_pelis2', function() {
		this.id_sel = 0;
		this.llista_pelis = [{
			"id"	: 1,
			"titol"	: "primera peli",
			"link"	: "www.google.com"
		},{
			"id"	: 2,
			"titol"	: "segona peli",
			"link"	: "www.google.com"
		}];
	});

    angular.module('Modul_Principal').config(function($routeProvider) {
        $routeProvider
            .when('/peli', { 
                templateUrl: '/templates/detall_peli.html',
                controllerAs: 'CTRL_info_peli',
                controller: function() { 
                    var that = this;
                }
            })
//        .when('/',      { templateUrl: '/templates/detall_peli.html' }) // Pàgina per defecte
            .otherwise(	    { redirectTo: '/' });
	});    
*/


    



})();

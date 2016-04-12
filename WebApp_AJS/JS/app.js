(function() {
    
    angular.module('Modul_Principal', ['door3.css']);
    
    function CTRL_general($scope) {
        $scope.id_sel = null;
        $scope.index_sel = null;
        // $scope.load_info_peli     = function(elem, ind) { ... }              // Definida a la directiva infoPeli
        // $scope.reload_nom_peli    = function(id_peli, nou_titol) { ... }     // Definida a la directiva llistaPelis
        // $scope.add_new_peli       = function() { ... }                       // Definida a la directiva llistaPelis
        // $scope.delete_peli        = function() { ... }                       // Definida a la directiva llistaPelis
        // $scope.reloadLlistaPelis  = function() { ... }                       // Definida a la directiva llistaPelis
    }


    // Controlador de la llista
    function llistaPelis() {
        var directive = {};
        directive.restrict     = 'EA';
        directive.templateUrl  = 'templates/llista_pelis.html';
        directive.css          = 'templates/llista_pelis.css';
        directive.controllerAs = 'CTRL_llista_pelis';
        directive.controller   = function($scope, $http) {
            var vm = this;
            vm.llista_pelis  = [];
            
            vm.buscar_index_peli = function(id_peli) {
                for (t = 0; t < vm.llista_pelis.length; ++t) {
                    if (vm.llista_pelis[t].id_peli == id_peli) return t;
                }
                return null;                    
            }            
            
            vm.loadLlistaPelis = function() {
                // Petició Ajax per la llista de pelis
                // alert('loading');
                $http.get('data/llista_pelis.php').success(function(data) {
                    if (data.result_code == 'ok') {
                        vm.llista_pelis = data.output_data.llista_pelis;
                        if (vm.llista_pelis.length > 0 && $scope.id_sel == null) $scope.onSelPeli({elem: vm.llista_pelis[0], ind: 0});
                    } else {
                        alert('error: ' + data.desc_error);
                    }
                });
            };
            
            $scope.$parent.reload_nom_peli = function(id_peli, nou_titol) {
//                alert('reload nom peli. index_sel=' + $scope.$parent.index_sel + 'id=' + id_peli + ', titol=' + nou_titol);
                if (vm.llista_pelis[$scope.index_sel].id == id_peli) {
                    vm.llista_pelis[$scope.index_sel].titol = nou_titol;
                } else {
                    var ind = vm.buscar_index_peli(id_peli);
                    if (ind != null) vm.llista_pelis[ind].titol = nou_titol;
                }
            };
            

            
            
            $scope.$parent.add_new_peli = function(peli) {
//                alert('add_peli_llista');
                vm.llista_pelis.push({
                    id          : peli.id_peli,
                    titol       : peli.titol,
                    url_imdb    : peli.url_imdb,
                    url_film    : peli.url_film,
                });
                $scope.id_sel = peli.id_peli;
                $scope.index_sel = vm.llista_pelis.length - 1;
                
                // Baixar el scroll a baix de tot
                var container = $('#id_cont_llista_pelis');
                var scrollTo  = $('#id_content_list_pelis tr:last');
                container.animate({ scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop() });
//                container.scrollTop( scrollTo.offset().top - container.offset().top + container.scrollTop() );
//                $('#id_cont_llista_pelis').scrollTop($('#id_footer_list').top);
//                $('#id_cont_llista_pelis').scrollTop(document.getElementById('id_cont_llista_pelis').scrollHeight + 200); 
            };
            
            $scope.$parent.delete_peli = function(id) {
//                alert('delete_peli');
                if (vm.llista_pelis[$scope.index_sel].id == id) {
                    vm.llista_pelis.splice($scope.index_sel, 1);
                } else {
                    var ind = vm.buscar_index_peli(id);
                    if (ind != null) vm.llista_pelis.splice($scope.ind, 1);
                }
                
                var new_id = null;
                if (vm.llista_pelis.length > 0) {
                    if ($scope.index_sel >= vm.llista_pelis.length) $scope.index_sel = vm.llista_pelis.length - 1;
                    new_id = vm.llista_pelis[$scope.index_sel].id;
                }
                return new_id;
                
            }
            
            $scope.$parent.reloadLlistaPelis = function() {
                vm.loadLlistaPelis();
            };
            
        };
        directive.scope = {
            id_sel          : '=param1',
            index_sel       : '=param2',
            onSelPeli       : "&"
        };
        return directive;
    };
    

    
    // Controlador del info box peli
    function infoPeli() {
        var directive = {};
        directive.restrict     = 'EA';
        directive.templateUrl  = 'templates/info_peli.html';
        directive.css          = 'templates/info_peli.css';
        directive.scope = {
            id_sel    : '=param1',
            index_sel : '=param2',
            renomPeliLlista : '&',
            addPeliLlista   : '&',
            delPeliLlista   : '&'
        };        
        directive.controllerAs = 'CTRL_crud_box';
        directive.controller   = function($scope, $http) {
            var vm = this;
            vm.info_peli_lock = null;
            vm.info_peli = null;
            vm.mode = null;    // off, con, add, mod
            vm.watcher1 = null;	// Variable per guardar el watcher
            

            // Aquesta funció es definieix pel controlador pare CTRL_general,
            // i s'envia a la directiva llista per tal de vincular-la al onclick dels elements
            $scope.$parent.load_info_peli = function(peli, ind) { 
                // vm.contingut = 'loading id:' + peli.id + ', ' + peli.titol;
                $scope.id_sel = peli.id;
                $scope.index_sel = ind;
                vm.consulta_peli(peli);
            };
            
            

            
            
            // Funció per canviar de mode i controlar botons i accions
            vm.change_mode = function(new_mode) {
 
                if (new_mode == 'off') {
                    $scope.id_sel = null;
                    $scope.index_sel = null;
                    vm.info_peli = null;
                }
                
                if (new_mode == 'add') vm.info_peli = null;
                if (new_mode == 'cpy') new_mode = 'add';
                
                // Botons add/del/mod peli
                var botons_a_activar = '';
                if (new_mode == 'off') botons_a_activar = '#id_button_add';
                if (new_mode == 'con') botons_a_activar = '#id_button_add, #id_button_cop, #id_button_del, #id_button_mod';
                if (new_mode == 'add' || new_mode == 'mod') botons_a_activar = '#id_button_ok, #id_button_can';
                
                $('#id_button_add, #id_button_cop, #id_button_del, #id_button_mod, #id_button_ok, #id_button_can').addClass('disabled').css('opacity', '0.1');   
                $(botons_a_activar).removeClass('disabled').css('opacity', '1');
                
                $('#id_info_waiting').text('LLEST');
                if (new_mode == 'wtg') $('#id_info_waiting').text('WAITING');
                
                if (new_mode == 'con') vm.setWatcher1();
                
                vm.mode = new_mode;
            }
            
            
            
            // Carregar les dades de la peli
            vm.show_info_peli = function(peli) {
                if (vm.watcher1) vm.watcher1();

                vm.info_peli = angular.copy(peli);
                vm.info_peli_lock = angular.copy(peli);

                if (vm.info_peli.url_imdb == '') vm.info_peli.img_imdb = 'img/imdb_logo2.png';
                else                             vm.info_peli.img_imdb = 'img/imdb_logo.png';                    

                if (vm.info_peli.url_film == '') vm.info_peli.img_film = 'img/filmaffinity_logo2.png';
                else                             vm.info_peli.img_film = 'img/filmaffinity_logo.png';

                if (vm.info_peli.nom_imatge != '') {
                    nom_img = 'img/cataleg/' + vm.info_peli.nom_imatge;
                    $('#id_div_box_info_peli_caratula').css('background-image', 'url("' + nom_img + '")');
                } else {
                    $('#id_div_box_info_peli_caratula').css('background-image', '');
                }
                vm.change_mode('con');
            }
            


            // Listener per canviar a mode 'mod' automaticament 
            // quan estant en consulta es modifica algun valor
            vm.setWatcher1 = function() {
                // alert('fixant watcher');
                if (vm.watcher1) vm.watcher1();
                vm.watcher1 = $scope.$watch( 
                    function() { return vm.info_peli; }, 
                    function(newValue, oldValue) {
                        if ((newValue !== oldValue)  && (vm.mode == 'con')) {
                            vm.watcher1();
                            vm.change_mode('mod');
                        }
                    },
                    true
                );
                
            };
            

            
            
            vm.end_trans = function(end_ok) {
                
                if (!end_ok) {   // Cancel
                    if (vm.info_peli_lock) {
                        vm.show_info_peli(vm.info_peli_lock); // Recarrega l'objecte sense modificar
                    } else {
                        vm.change_mode('off');
                    }
                    // vm.consulta_peli({ id : vm.info_peli.id_peli });
                    
                } else {    // OK
                    // Enviar modificació al servidor
                    if (vm.mode == 'add') {
                        if (vm.watcher1) vm.watcher1();
                        vm.change_mode('wtg');
                        vm.insertar_peli();
                        
                    }
                    if (vm.mode == 'mod') {
                        if (vm.watcher1) vm.watcher1();
                        vm.change_mode('wtg');
                        vm.modificar_peli();
                        // alert('modificat');
                    }
                    // vm.info_peli_lock = angular.copy(vm.info_peli);
                    // vm.show_info_peli(vm.info_peli);
//                    vm.change_mode('con');
                }
            }
            

            // Carregar peli per crud
            vm.consulta_peli = function(peli) {
                // alert('consultar peli.id=' + peli.id);
                $http.get('data/info_peli.php?id_peli=' + peli.id).success(function(data) {
                    if (data.result_code == 'ok') {                      
                        vm.show_info_peli(data.output_data);
                    } else { alert('error: ' + data.desc_error); }
                });                  
            }            
            
            
            // Crida al servidor per modificar la peli
            vm.modificar_peli = function() {
//                alert('send post mod');
                $http({
                    method  : 'POST',
                    url     : 'data/modificar_peli.php',
                    data    : { 'info_peli': vm.info_peli },
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                }
                ).success(function(data, status) {
                    if (data.result_code == 'ok') {
//                        alert('oook mod. result_code=' + data.input_params.info_peli.titol);
//                        alert('oook mod. result_code=' + vm.info_peli_lock.titol);
                        
                        // Canviar el nom de la peli a la llista també (si cal)
                        if (data.input_params.info_peli.titol != vm.info_peli_lock.titol) {
//                            alert('$scope.renomPeliLlista');
                            $scope.renomPeliLlista({ id_peli   : data.input_params.info_peli.id_peli, 
                                                     nou_titol : data.input_params.info_peli.titol });
                        }
                        vm.show_info_peli(data.input_params.info_peli);
                        vm.change_mode('con');                        
                    } else { 
                        alert('error: ' + data.desc_error); 
                        vm.change_mode('con'); 
                    }
                    
                }).error(function(response) {
                        alert('error ' + response);
                        vm.change_mode('con'); 
                });
            }
            
            
            
            // Crida al servidor per insertar la peli
            vm.insertar_peli = function() {
//                alert('send post mod');
                $http({
                    method  : 'POST',
                    url     : 'data/insertar_peli.php',
                    data    : { 'info_peli': vm.info_peli },
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                }
                ).success(function(data, status) {
                    if (data.result_code == 'ok') {
//                        alert('oook add. result_code=' + data.output_data.id_peli);
                        data.input_params.info_peli.id_peli = data.output_data.id_peli;
                        vm.show_info_peli(data.input_params.info_peli);
                        $scope.addPeliLlista({ peli: vm.info_peli }); // Afegir a la llista
                        vm.change_mode('con');
                    } else { 
                        alert('error: ' + data.desc_error); 
                        vm.change_mode('con'); 
                    }
                    
                }).error(function(response) {
                        alert('error ' + response);
                        vm.change_mode('con'); 
                });
            }            
            
                        
            vm.eliminar = function() {
                if (!confirm('Estàs segur que vols eliminar la pel·lícula "' + vm.info_peli.titol + '"')) return;
//                alert('eliminar');
                $http({
                    method  : 'POST',
                    url     : 'data/eliminar_peli.php',
                    data    : { 'id_peli': vm.info_peli.id_peli },
                    headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                }
                ).success(function(data, status) {
                    if (data.result_code == 'ok') {
//                        alert('oook del. result_code=' + data.output_data.id_peli_seg);                        
                        $scope.id_sel = $scope.delPeliLlista({ id: vm.info_peli.id_peli }); // Eliminar a la llista
                        if ($scope.id_sel == null) vm.change_mode('off');
                        else  vm.consulta_peli({ id : $scope.id_sel });
                    } else { 
                        alert('error: ' + data.desc_error); 
                    }
                    
                }).error(function(response) {
                        alert('error ' + response);
                });                
                
                
                
            }
            
            
            vm.change_mode('off'); // Ini
        };


        return directive;

    };     
    

    
    
    angular.module('Modul_Principal')
        .controller('CTRL_general', CTRL_general)
        .directive('llistaPelis', llistaPelis)
        .directive('infoPeli', infoPeli);



})();

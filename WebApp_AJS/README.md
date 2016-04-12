# Catàleg de Películes

This is a personal project to handle a collection of movies files and their information.

It is developed under AngularJS on the front-end and PHP/PostgreSQL on the backend<br/>
This is a Simple Page Web application, so the index.html main page is load once (with an http request), and all other requests are launched and controlled via ajax.

## Front-End

The main page is fluid responsive, but there's only one layer for all devices (it doesn't change the format depending on the screen, it is just resized).

### Overview
<img height="500" src="https://raw.githubusercontent.com/joelbarba/DUB_BUS/master/sample.jpg"/>

### AngularJS tag templates

There are 2 custom tags to manage the front end:

* <b> \<llista-pelis> </b>: This tag contains the list of the items. There's an exclusive selection of 1 item.<br/> Their atributes are:<br/>
 * <b>param1</b> : This is the identifier to store the ID of the selected item in the list.
 * <b>param2</b> : This is the identifier to store the index of the selected item in the list.
 * <b>on_sel_peli</b> : To set a function to be launched when an item is selected.

* <b> \<info-peli> </b>: This tag contains the view with all the information related to an item from the list. It also contain the functionality to interact with the items as a CRUD. <br/> Their atributes are:<br/>
 * <b>param1</b> : This is the identifier of the ID of the current selected item.
 * <b>param2</b> : This is the identifier of the index (in the list) related with the selected item.
 * <b>renom_peli_llista</b> : To set a function to be launched when the title (also shown in the list) of the item changes. It is recomended to use it to update the title in the list too, to keep the coherence in the displayed information.
 * <b>add_peli_llista</b> : To set a function to be launched when an new item is created (and it is also needed to add it into the list, for example).
 * <b>del_peli_llista</b> : To set a function to be launched when an item is deleted (and it is needed to remove it from the list too).

Example:

    <llista-pelis 
        param1="id_sel" 
        param2="index_sel" 
        on_sel_peli="load_info_peli(elem, ind)">
    </llista-pelis>
    
    <info-peli 
        param1="id_sel" 
        param2="index_sel"
        renom_peli_llista="reload_nom_peli(id_peli, nou_titol)"
        add_peli_llista="add_new_peli(peli)"
        del_peli_llista="delete_peli(id)">
    </info-peli>

# Backend

## DataBase

The app runs a PostgreSQL database to store the data.<br/>
The model (Cataleg_Pelis DB) is composed by the following 3 tables:
* <b>Pelis_Down</b> : This is the main table with all the items displayed and their information.
* <b>Pelis_Down_History</b> : This table is a history record from the Pelis_Down table. It stores every change in the latter table, in order to keep all the history of the data manipulation and revert it if need be. It uses [a personal technique](https://blogdelbarba.wordpress.com/2013/05/13/analisi-per-mantenir-la-historia-completa-en-una-db-relacional/) I once defined to manage it. 
* <b>Arxius_Pelis</b> : This table stores all the files related to each item in the list. This part of the app is still under construction.

## Data requests

Once the app is loaded, there are 4 asyncronus requests (ajax with json response) to handle the data flow. Each one is supported for a PHP file.<br/>
* <b> php </b> fsfsfsfsfs
* <b> data/llista_pelis.php </b> : (GET method) It returns all the items to show in the list, but only with a few information (title/id).
* <b> data/info_peli.php?id_peli=999 </b> : (GET method) It returns all the information about the requested item (by ID).
* <b> data/modificar_peli.php </b> : (POST method) To modify the data of an item. 
* <b> data/insertar_peli.php </b> : (POST method) To add a new item.
* <b> data/eliminar_peli.php </b> : (POST method) To remove an item.

The response is always a JSON object with the pattern:<br/>

    { "result_code"  : "ok/ko",
      "desc_error"   : "description of the error (in case of)",
      "input_params" : { 
              [here is located the same parameters the request is launched with]
      },
      "output_data"  : {
        [here is located the data of the response]
      }        
    }

The next goal will be a RESTful API.









<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\backend\ProfileController;
use App\Http\Controllers\Api\backend\CollectionController;
use App\Http\Controllers\Api\backend\ArtistController;
use App\Http\Controllers\Api\backend\ArtMunchieController;
use App\Http\Controllers\Api\backend\BasicPageController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\Api\backend\CulturalIncubatorController;
use App\Http\Controllers\Api\backend\ArtWorkController;
use App\Http\Controllers\Api\backend\CommandeController;
use App\Http\Controllers\Api\backend\DocumentController;
use App\Http\Controllers\Api\backend\FavorisController;
use App\Http\Controllers\Api\backend\SubCollectionController;
use App\Http\Controllers\Api\frontEnd\PageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Front Routes

Route::middleware('localization')->group(
    function () {
        //User management Routes
        Route::post('register', [PassportAuthController::class, 'register']);
        Route::post('login', [PassportAuthController::class, 'login']);
        // Front Routes
        Route::prefix('menu')->group(function () {
            Route::get('/list', [PageController::class, 'getMenuList']);
        });

        //Cultural Incubator
        Route::get('/cultural_incubators/{slug}', [PageController::class, 'getCulturalIncubatorBySlug']);
        Route::post('/cultural-incubator-request', [PageController::class, 'addCulturalIncubatorRequest']);

        //Get Contact Page Info
        Route::post('/contact', [PageController::class, 'storeContact']);

        Route::middleware('auth:api')->group(
            function () {
                Route::get('logout', [PassportAuthController::class, 'logout']);
                Route::post('/confirm-email', [PassportAuthController::class, 'confirmEmail']);
            }
        );


        Route::prefix("/art_munchies")->group(
            function () {
                Route::get('/', [ArtMunchieController::class, 'getAllArtMunchies']);
            }
        );


        Route::prefix("/collectionsVisiteur")->group(
            function () {
                Route::get('/', [CollectionController::class, 'getCollectionPublic']);
            }
        );


        Route::prefix("/ArtistsPublic")->group(
            function () {
                Route::get('/', [ArtistController::class, 'getArtistsPublic']);
            }
        );

        Route::prefix("/AllCulturalIncubators")->group(
            function () {
                Route::get('/', [CulturalIncubatorController::class, 'getAllCulturalIncubatorPublic']);
            }
        );



        Route::prefix("/explore")->group(
            function () {
                //getArtworks in Explore
                Route::get('/artworks', [ArtWorkController::class, 'getArtworksExplore']);
                //getartworkexolorebyid
                Route::get('/art_work/{artwork_id}', [ArtWorkController::class, 'getArtWorkById']);
            }
        );


        Route::prefix("/basket")->group(
            function () {
                Route::post('/addToBasket/{artwork_id}', [ArtWorkController::class, 'AddArtworkToBasket']);
                Route::get('/artworksbasket', [ArtWorkController::class, 'getArtworksBasket']);
            }
        );



        Route::get('/{slug}', [PageController::class, 'getPageBySlug']);
    }
);


// Dashboard Routes
Route::middleware('auth:api', 'localization')->prefix("/dashboard")->group(
    function () {
        //Contact Module
        Route::prefix("/contacts")->group(
            function () {
                Route::any('/', [ContactController::class, 'getAllContact']);
                Route::get('/{contactId}', [ContactController::class, 'getContactById']);
                Route::get('/delete/{contactId}', [ContactController::class, 'deleteContact']);
            }
        );
        //User Profile
        Route::prefix("/user")->group(
            function () {
                Route::post('/edit/profile', [ProfileController::class, 'updateProfile']);
                Route::get('/profile', [ProfileController::class, 'showProfile']);
                //client 
                Route::get('/profile/favoris/{artwork_id}', [ProfileController::class, 'syncUserArtworkFavoris']);
                Route::get('/favoriteArtwork', [ProfileController::class, 'getFavoriteArtwork']);
                Route::get('/basketadd/{artwork_id}', [ProfileController::class, 'AddArtworkToBasket']);
                Route::get('/ArtworksInBasket', [ProfileController::class, 'getBasketContent']);
                Route::get('/basketArtwork/delete/{basket_id}', [ProfileController::class, 'deletefrombasket']);
            }
        );

        //Collections
        Route::prefix("/collections")->group(
            function () {
                Route::get('/', [CollectionController::class, 'getAllCollections']);
                Route::get('/user', [CollectionController::class, 'getCollectionByUser']);
                Route::post('/add', [CollectionController::class, 'addCollection']);
                Route::post('/update/{collection_id}', [CollectionController::class, 'updateCollection']);
                Route::get('/collection/{collection_id}', [CollectionController::class, 'getCollectionById']);
                //get all subcollections by collection 
                Route::get('/collections/{collection_id}', [CollectionController::class, 'getCollectionSubById']);
                Route::get('/collection/delete/{collection_id}', [CollectionController::class, 'deleteCollection']);
                Route::get('/logs', [CollectionController::class, 'getLogs']);
            }
        );



        //Artists
        Route::prefix("/artists")->group(
            function () {
                Route::get('/', [ArtistController::class, 'getAllArtists']);
                Route::get('/user', [ArtistController::class, 'getArtistByUser']);
                Route::post('/add', [ArtistController::class, 'addArtist']);
                Route::post('/update/{artist_id}', [ArtistController::class, 'updateArtist']);
                Route::get('/artist/{artist_id}', [ArtistController::class, 'getArtistById']);
                Route::get('/artist/delete/{artist_id}', [ArtistController::class, 'deleteArtist']);
                Route::get('/logs', [ArtistController::class, 'getLogs']);
            }
        );

        //Art Munchies
        Route::prefix("/art_munchies")->group(
            function () {
                Route::get('/', [ArtMunchieController::class, 'getAllArtMunchies']);
                Route::get('/user', [ArtMunchieController::class, 'getArtMunchiesByUser']);
                Route::post('/add', [ArtMunchieController::class, 'addArtMunchie']);
                Route::post('/update/{artMunchie_id}', [ArtMunchieController::class, 'updateArtMunchie']);
                Route::get('/art_munchie/{artMunchie_id}', [ArtMunchieController::class, 'getArtMunchieById']);
                Route::get('/art_munchie/delete/{artMunchie_id}', [ArtMunchieController::class, 'deleteArtMunchie']);
                Route::get('/logs', [ArtMunchieController::class, 'getLogs']);
            }
        );

        //Pages
        Route::middleware('auth:api')->prefix("/pages")->group(
            function () {
                Route::any('/', [BasicPageController::class, 'getAllPages']);
                Route::get('/page/{pageId}', [BasicPageController::class, 'getPageById']);
                Route::get('/delete/{pageId}', [BasicPageController::class, 'deletePage']);
                Route::post('/update/{pageId}', [BasicPageController::class, 'updatePage']);
                Route::post('/add', [BasicPageController::class, 'addPage']);
                Route::get('/logs', [BasicPageController::class, 'getLogs']);
            }
        );
        //Cultural Incubators
        Route::middleware('auth:api')->prefix("/cultural_incubators")->group(
            function () {
                Route::any('/', [CulturalIncubatorController::class, 'getAllCulturalIncubators']);
                Route::get('/cultural_incubator/{culturalId}', [CulturalIncubatorController::class, 'getCulturalIncubatorById']);
                Route::get('/delete/{culturalId}', [CulturalIncubatorController::class, 'deleteCulturalIncubator']);
                Route::post('/update/{culturalId}', [CulturalIncubatorController::class, 'updateCulturalIncubator']);
                Route::post('/add', [CulturalIncubatorController::class, 'addCulturalIncubator']);
                Route::get('/logs', [CulturalIncubatorController::class, 'getLogs']);
            }
        );
        //Cultural Incubators requests
        Route::middleware('auth:api')->prefix("/cultural_incubators_requests")->group(
            function () {
                Route::any('/', [CulturalIncubatorController::class, 'getAllCulturalIncubatorsRequests']);
                Route::get('/{culturalRequestId}', [CulturalIncubatorController::class, 'getCulturalIncubatorRequestById']);
                Route::get('/delete/{culturalRequestId}', [CulturalIncubatorController::class, 'deleteCulturalIncubatorRequest']);
            }
        );

        //Art Works
        Route::middleware('auth:api')->prefix("/art_works")->group(
            function () {
                Route::get('/', [ArtWorkController::class, 'getAllArtWorks']);
                Route::get('/artist/{artist_id}', [ArtWorkController::class, 'getArtWorkByArtist']);
                Route::post('/add', [ArtWorkController::class, 'addArtWork']);
                Route::post('/update/{artwork_id}', [ArtWorkController::class, 'updateArtWork']);
                //detailsArtworks with documents list
                Route::get('/art_work/{artwork_id}', [ArtWorkController::class, 'getArtWorkById']);
                //add artwork to explore
                Route::post('/addtoexplore/{artwork_id}', [ArtWorkController::class, 'AddArtWorktoExplore']);
                Route::get('/delete/{artwork_id}', [ArtWorkController::class, 'deleteArtWork']);
                Route::get('/restore/{artwork_id}', [ArtWorkController::class, 'restoreArtWork']);
                Route::get('/logs', [ArtWorkController::class, 'getLogs']);
                Route::get('/{artwork_id}/copy/{collection_id}', [ArtWorkController::class, 'copyArtWorkToCollection']);
            }
        );


        //DocumentArtwork
        Route::middleware('auth:api')->prefix("/documents")->group(
            function () {
                Route::post('/addDoc', [DocumentController::class, 'addDocument']);
                Route::post('/addSub', [DocumentController::class, 'addsub']);
                Route::get('/artwork/{artwork_id}', [DocumentController::class, 'getDocumentByArtwork']);
            }
        );



        //SubCollection
        Route::middleware('auth:api')->prefix("/subcollection")->group(
            function () {
                Route::post('/addsubcollection', [SubCollectionController::class, 'addsubCollection']);
                Route::get('/getsubcolbyid/{subcollection_id}', [SubCollectionController::class, 'getSubCollectionbyid']);
            }
        );




        //client

        Route::prefix("/art_munchies")->group(
            function () {
                Route::get('/', [ArtMunchieController::class, 'getAllArtMunchies']);
            }
        );


        Route::prefix("/collectionsVisiteur")->group(
            function () {
                Route::get('/', [CollectionController::class, 'getCollectionPublic']);
            }
        );


        Route::prefix("/ArtistsPublic")->group(
            function () {
                Route::get('/', [ArtistController::class, 'getArtistsPublic']);
            }
        );

        Route::prefix("/AllCulturalIncubators")->group(
            function () {
                Route::get('/', [CulturalIncubatorController::class, 'getAllCulturalIncubatorPublic']);
            }
        );

        Route::prefix("/checkout")->group(
            function () {
                Route::get('/', [CommandeController::class, 'checkout']);
            }
        );



        Route::prefix("/explore")->group(
            function () {
                //getArtworks in Explore
                Route::get('/artworks', [ArtWorkController::class, 'getArtworksExplore']);
                //getartworkexolorebyid
                Route::get('/art_work/{artwork_id}', [ArtWorkController::class, 'getArtWorkById']);
            }
        );


        //commande

        Route::prefix("/commande")->group(
            function () {
                Route::post('/add', [CommandeController::class, 'addcommande']);
            }
        );
    }







);

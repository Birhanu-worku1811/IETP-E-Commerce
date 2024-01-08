<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Artisan;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\FrontendController;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\MessageController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\WishlistController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\ProductReviewController;
    use App\Http\Controllers\PostCommentController;
    use App\Http\Controllers\PayPalController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\HomeController;
    use \UniSharp\LaravelFilemanager\Lfm;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    // CACHE CLEAR ROUTE
    Route::get('cache-clear', function () {
        Artisan::call('optimize:clear');
        request()->session()->flash('success', 'Successfully cache cleared.');
        return redirect()->back();
    })->name('cache.clear');


    // STORAGE LINKED ROUTE
    Route::get('storage-link',[AdminController::class,'storageLink'])->name('storage.link');


    Auth::routes(['register' => false]);

    Route::get('user/login', [FrontendController::class, 'login'])->name('login.form'); // done
    Route::post('user/login', [FrontendController::class, 'loginSubmit'])->name('login.submit'); // done
    Route::get('user/logout', [FrontendController::class, 'logout'])->name('user.logout'); // done

    Route::get('user/register', [FrontendController::class, 'register'])->name('register.form'); // done
    Route::post('user/register', [FrontendController::class, 'registerSubmit'])->name('register.submit'); // done
// Reset password
    Route::post('password-reset', [FrontendController::class, 'showResetForm'])->name('password.reset'); // queued
// Socialite
    Route::get('login/{provider}/', [LoginController::class, 'redirect'])->name('login.redirect'); // No idea
    Route::get('login/{provider}/callback/', [LoginController::class, 'Callback'])->name('login.callback'); // No idea

    Route::get('/', [FrontendController::class, 'home'])->name('home'); // done

// Frontend Routes
    Route::get('/home', [FrontendController::class, 'index']); // done
    Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us'); // done
    Route::get('/contact', [FrontendController::class, 'contact'])->name('contact'); // done
    Route::post('/contact/message', [MessageController::class, 'store'])->name('contact.store'); // done
    Route::get('product-detail/{slug}', [FrontendController::class, 'productDetail'])->name('product-detail'); // done
    Route::post('/product/search', [FrontendController::class, 'productSearch'])->name('product.search'); // done
    Route::get('/product-cat/{slug}', [FrontendController::class, 'productCat'])->name('product-cat'); //done
    Route::get('/product-sub-cat/{slug}/{sub_slug}', [FrontendController::class, 'productSubCat'])->name('product-sub-cat'); // done
// Cart section
    Route::get('/add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('user'); // done
    Route::post('/add-to-cart', [CartController::class, 'singleAddToCart'])->name('single-add-to-cart')->middleware('user'); // done
    Route::get('cart-delete/{id}', [CartController::class, 'cartDelete'])->name('cart-delete'); // done
    Route::post('cart-update', [CartController::class, 'cartUpdate'])->name('cart.update'); // done

    Route::get('/cart', function () {
        return view('frontend.pages.cart');
    })->name('cart'); // done
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('user'); // done
// Wishlist
    Route::get('/wishlist', function () {
        return view('frontend.pages.wishlist');
    })->name('wishlist'); // done
    Route::get('/wishlist/{slug}', [WishlistController::class, 'wishlist'])->name('add-to-wishlist')->middleware('user'); // done
    Route::get('wishlist-delete/{id}', [WishlistController::class, 'wishlistDelete'])->name('wishlist-delete'); // done
    Route::post('cart/order', [OrderController::class, 'store'])->name('cart.order'); // done
    Route::get('order/pdf/{id}', [OrderController::class, 'pdf'])->name('order.pdf'); // review
    Route::get('/income', [OrderController::class, 'incomeChart'])->name('product.order.income'); // may be
// Route::get('/user/chart',[AdminController::class, 'userPieChart'])->name('user.piechart');
    Route::get('/product-grids', [FrontendController::class, 'productGrids'])->name('product-grids'); // done
    Route::get('/product-lists', [FrontendController::class, 'productLists'])->name('product-lists'); // done
    Route::match(['get', 'post'], '/filter', [FrontendController::class, 'productFilter'])->name('shop.filter'); // done

// NewsLetter
    Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('subscribe'); // review

// Product Review
    Route::resource('/review', 'ProductReviewController'); // review
    Route::post('product/{slug}/review', [ProductReviewController::class, 'store'])->name('review.store');

// Post Comment
//    Route::post('post/{slug}/comment', [PostCommentController::class, 'store'])->name('post-comment.store'); // no idea
//    Route::resource('/comment', 'PostCommentController');
// Payment
//    Route::get('payment', [PayPalController::class, 'payment'])->name('payment');
//    Route::get('cancel', [PayPalController::class, 'cancel'])->name('payment.cancel');
//    Route::get('payment/success', [PayPalController::class, 'success'])->name('payment.success');


// Backend section start

    Route::group(['prefix' => '/admin', 'middleware' => ['auth', 'admin']], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin'); // done
        Route::get('/file-manager', function () {
            return view('backend.layouts.file-manager');
        })->name('file-manager'); // done
        // user route
        Route::resource('users', 'UsersController'); // done
        // Banner
        Route::resource('banner', 'BannerController'); // done
        // Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin-profile'); // done
        Route::post('/profile/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update'); // done
        // Category
        Route::resource('/category', 'CategoryController'); // done
        // Product
        Route::resource('/product', 'ProductController'); // done
        // Ajax for sub category
        Route::post('/category/{id}/child', 'CategoryController@getChildByParent');
        // POST category
//        Route::resource('/post-category', 'PostCategoryController');
        // Post tag
//        Route::resource('/post-tag', 'PostTagController');
        // Post
//        Route::resource('/post', 'PostController');
        // Message
        Route::resource('/message', 'MessageController'); // done
        Route::get('/message/five', [MessageController::class, 'messageFive'])->name('messages.five'); // done

        // Order
        Route::resource('/order', 'OrderController'); // done
        // Settings
        Route::get('settings', [AdminController::class, 'settings'])->name('settings'); // done
        Route::post('setting/update', [AdminController::class, 'settingsUpdate'])->name('settings.update'); // done

        // Notification
        Route::get('/notification/{id}', [NotificationController::class, 'show'])->name('admin.notification'); // done
        Route::get('/notifications', [NotificationController::class, 'index'])->name('all.notification'); // done
        Route::delete('/notification/{id}', [NotificationController::class, 'delete'])->name('notification.delete'); // done
        // Password Change
        Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form'); // done
        Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password'); // done
    });


// User section start
    Route::group(['prefix' => '/user', 'middleware' => ['user']], function () {
        Route::get('/', [HomeController::class, 'index'])->name('user'); // done
        // Profile
        Route::get('/profile', [HomeController::class, 'profile'])->name('user-profile'); // done
        Route::post('/profile/{id}', [HomeController::class, 'profileUpdate'])->name('user-profile-update'); // done
        //  Order
        Route::get('/order', "HomeController@orderIndex")->name('user.order.index'); // done
        Route::get('/order/show/{id}', "HomeController@orderShow")->name('user.order.show');
        Route::delete('/order/delete/{id}', [HomeController::class, 'userOrderDelete'])->name('user.order.delete');
        // Product Review
        Route::get('/user-review', [HomeController::class, 'productReviewIndex'])->name('user.productreview.index');
        Route::delete('/user-review/delete/{id}', [HomeController::class, 'productReviewDelete'])->name('user.productreview.delete');
        Route::get('/user-review/edit/{id}', [HomeController::class, 'productReviewEdit'])->name('user.productreview.edit');
        Route::patch('/user-review/update/{id}', [HomeController::class, 'productReviewUpdate'])->name('user.productreview.update');

        // Post comment
//        Route::get('user-post/comment', [HomeController::class, 'userComment'])->name('user.post-comment.index');
//        Route::delete('user-post/comment/delete/{id}', [HomeController::class, 'userCommentDelete'])->name('user.post-comment.delete');
//        Route::get('user-post/comment/edit/{id}', [HomeController::class, 'userCommentEdit'])->name('user.post-comment.edit');
//        Route::patch('user-post/comment/udpate/{id}', [HomeController::class, 'userCommentUpdate'])->name('user.post-comment.update');

        // Password Change
        Route::get('change-password', [HomeController::class, 'changePassword'])->name('user.change.password.form');
        Route::post('change-password', [HomeController::class, 'changPasswordStore'])->name('change.password');

    });

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        Lfm::routes();
    });


    <?php
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\PostController;
    use App\Http\Controllers\CategoryControlle;
    use App\Http\Controllers\RouteController;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Http;
    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "web" middleware group. Make something great!
    |
    */
    // Route::get('/route', [RouteController::class, 'index'])->name('route.index');
    Route::post('/route/find', [RouteController::class, 'findRoute'])->name('route.find');
    Route::get('/autocomplete',[RouteController::class, 'autocomplete'])->name('route.autocomplete');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth','verified'])->name('dashboard');
    Route::controller(PostController::class)->middleware(['auth'])->group(function(){
        Route::get('/','index')->name('index');
        Route::post('/posts','store')->name('store');
        Route::get('/posts/create','create')->name('create');
        Route::get('/posts/{post}','show')->name('show');
        Route::put('/posts/{post}','update')->name('update');
        Route::delete('/posts/{post}','delete')->name('delete');
        Route::get('/posts/{post}/edit','edit')->name('edit');
    });
    Route::get('/categories/{category}',[CategoryController::class,'index'])->middleware("auth");
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    require __DIR__ .'/auth.php';
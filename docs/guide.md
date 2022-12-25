# Thứ tự tạo 1 API:

## Tạo migration(tạo bảng trong DB):

` php artisan make:migration <tên bảng> --create`

-> sửa các table name trong file migration vừa được tạo VD:

```php
Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('price');
            $table->timestamp('created_at')->default(now()->toDateTimeString());
            $table->timestamp('updated_at')->default(now()->toDateTimeString());
        });
```

chạy `yarn migrate` hoặc `php artisan migrate:refresh` để lưu bảng vừa tạo trong code vào DB

## Tạo Model cho table vừa tạo

-   trong folder app/Models tạo file table-name.php
-   VD với file Product.php :

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
  protected $primaryKey = "id";

  protected $fillable = ["name","price"];

  public $timestamps = true;
}
```

## Tạo Controller

`php artisan make:controller <tênController> --resource`

-   VD ProductController.php :

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newProduct = Product::create($request->all());
        return $newProduct;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productUpdate = Product::find($id);
        $productUpdate->update($request->all());
        return $productUpdate;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productDelete = Product::find($id);
        $productDelete->delete();
        return $id;
    }
}
```

-   sau đó đăng ký với routes/api.php:

```php
//C1: Route::get('/product','App\Http\Controllers\ProductController@index');
//C2: Route::get('/product',[ProductController::class,'index']);
// tự tạo các path routing tương ứng
Route::resource('/product',ProductController::class);
```

-   chạy `yarn start` hoặc `php artisan serve`
-   --> 💣bùm! ta-da: Thế là có 1 api đầy đủ CRUD với path: http://localhost:8000/api/<tên resource đăng ký trong routes/api.php>

# Biết thêm cái này nữa nhé 😭:

## Tạo dữ liệu giả với seeder laravel:

-   Tạo factory :
    `php artisan make:factory <tênFactory> -model=<tênModel>`

VD với ProductFactory:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => fake()->word(),
            'price' => fake()->numberBetween(100, 1000),
        ];
    }
}
```

-   sửa lại Model Product:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
  use HasFactory;
  // code in here
}
```

-   sau đó tạo seeder để lưu vào DB:

`php artisan make:seed <TênSeeder>`

VD với ProductSeeder (muốn fake 10 bản ghi):

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory(10)->create();
    }
}
```

-   cuối cùng là thêm vào DatabaseSeeder.php:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductSeeder::class);
    }
}
```

-   để lưu vào DB chạy lệnh: `php artisan db:seed` (nếu muốn fake lại toàn bộ dữ liệu được call trong DatabaseSeeder) hoặc `php artisan db:seed --class=<TênSeeder>` (nếu chỉ muốn fake bảng mới tạo)

## Format dữ liệu trả về chuẩn JSON với Resource và ResourceCollection

-   Tạo resource:
    `php artisan make:resource <tênResource>`

-   VD với ProductResource:
    -   sửa lại ProductController: thêm new ProductResource vào data type Product được return;
    -   ```php
          public function show($id)
        {
            $product = Product::findOrFail($id);
            return new ProductResource($product);
        }
        ```
-   để custom các trường sẽ được trả về:
    -   sửa lại ProductResoure:
    -   ```php
        public function toArray($request)
        {
            return [
                'name_custom' => $this->name,
                'price_custom' => $this->price
            ];
        }
        ```

**<h3>paginate với ResourceCollection</h3>**

-   tạo collection
    `php artisan make:resource <tênCollection> --collection`
-   VD với ProductCollection:

    -   sửa lại ProductController:
    -   ```php
             public function index()
        {
            $products = Product::paginate(5);
            return new ProductCollection($products);
        }
        ```

    -> mở http://localhost:8000/api/product để thấy điều kì diệu 💩

=> file Controller được cập nhật hoàn chỉnh:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $products = Product::paginate(5);
       return new ProductCollection($products);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       //
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       // $request->validate([
       // 'name' => 'required',
       // 'price' => 'required',
       // ]);
       $newProduct = Product::create($request->all());
       return $newProduct;
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       $product = Product::findOrFail($id);
       return new ProductResource($product);
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
       //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
       $productUpdate = Product::find($id);
       $productUpdate->update($request->all());
       return new ProductResource($productUpdate);
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       $productDelete = Product::find($id);
       $productDelete->delete();
       return $id;
   }
}
```

## API Only/Except

-   Có thể thấy phương thức _create()_ và _edit()_ không được sử dụng (do đây là 2 phương thức để get trang chứa form để create và edit nếu sử dụng template engine Blade) -> ko cần thiết với REST API
-   để không đăng ký 2 phương thức này trong resource ta sử dụng only/except
-   ta sửa lại trong routes/api.php:

```php
    Route::resource('/product',ProductController::class)->only(['index','store','show','update','destroy']);
    //OR
    Route::resource('/product',ProductController::class)->except(['create','edit']);
```

_PS: Hướng dẫn đến đây là hết, cảm ơn các bạn đã đọc 🤡_

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  function AddProduct(Request $request)
  {
    if(Auth::id()){

    $validatedData = Validator::make($request->all(), [
      'name' => 'required|max:100',
      'description' => 'required|max:200',
      'price' => 'required|numeric',
      'image' => 'required|image',
  ]);

    if ($validatedData->fails()) {

         return response()->json([
        'validate_err' => $validatedData->messages(),

      ]);
    } 

      $product = new Product();
      $product->name = $request->name;
      $product->description = $request->description;
      $product->price =$request->price;

      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move('products', $imageName);

      $product->image = $imageName;
      $product->save();
      // return  $product;
      return response()->json([
        'status' => 200,
        'message' => 'Product Added Successfully!',
        'result' => $product
      ]);
    }
    else{
      return response()->json([
        'status' => 500,
        'message' => "Failed to add the item!",
        // 'result' => $product
      ]);
  }
}

  function Productlist()
  {
    $products = Product::all();
    return $products;
  }
  // function Delete($id){
  //   $result = Product::where('id',$id)->delete();

  //    if($result){
  //     return response()->json(["success" => "Product Deleted Successfully!"], 401);
  //    }

  //    else{
  //     return response()->json(["error" => "Error While Deleting the product!"], 401);
  //    }

  // }
  public function Delete($id)
  {
    if(Auth::id()){
    $product = Product::find($id);

    if (!$product) {
      return response()->json(["error" => "Product not found!"], 401);
    }

    // Delete the associated image from the public products table
    $imagePath = public_path('products/' . $product->image);
    if (File::exists($imagePath)) {
      File::delete($imagePath);
    }

    $result = $product->delete();

    if ($result) {
      return response()->json([
        "status" => 200,
        "success" => "Product deleted successfully!"
      ], 200);
    } else {
      return response()->json(["error" => "Error while deleting the product!"], 500);
    }
  }
  else{
    return response()->json(["error" => "Error while deleting the product!"], 401);
  }
  }
  function GetProduct($id)
  {
    if(Auth::id()){
    $product = Product::where('id', $id)->first();
    return $product;
    }
  }
  function UpdateProduct(Request $req, $id)
  {
  if(Auth::id()){

  
    $validatedData = Validator::make($req->all(), [
      'name' => 'required|max:100',
      'description' => 'required|max:200',
      'price' => 'required|numeric',
  ]);

    if ($validatedData->fails()) {
         return response()->json([
        'validate_err' => $validatedData->messages(),
      ]);
    } 

    $product = Product::where('id', $id)->first();
    $product->name = $req->name;
    $product->description = $req->description;
    $product->price = $req->price;

    if ($req->image) {
      $image = $req->image;
      $imagename = time() . '.' . $image->getClientOriginalExtension();
      $image->move('products', $imagename);
      $product->image = $imagename;
    } else {
      $product->image = $product->image;
    }

    $product->update();

    // return  $product;
    return response()->json([
      'status' => 200,
      'message' => 'Product Added Successfully!',
      'product' => $product
    ]);
  }
  else{
    return response()->json([
      'message' => 'Failed!',
    ],401);
  }
}
  public function Search($key)
  {
    if(Auth::id()){
      $products = Product::where('name', 'Like', "%$key%")
      ->orWhere('description', 'Like', "%$key%")
      ->orWhere('price', 'Like', "%$key%")->get();
    return  $products;
    }
    else{
      return response()->json([
        'message' => 'Failed!',
      ],401);
    }
   
  }
}

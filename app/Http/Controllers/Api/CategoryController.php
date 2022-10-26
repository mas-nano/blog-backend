<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $category = Category::where('name', 'LIKE', '%' . $request->input('search') . '%')->paginate(10);
        return $this->responseSuccessWithData("Category", $category);
    }

    public function updateOrCreateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:categories",
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        try {
            Category::updateOrCreate([
                'id' => $request->input('id')
            ], $validator->validated());

            return $this->responseCreated("Data berhasil ditambahkan atau diubah");
        } catch (QueryException $e) {
            return $this->responseError(
                "Invalid Server Error",
                500,
                $e->errorInfo
            );
        }
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();

        return $this->responseSuccess('Category deleted successfully');
    }
}

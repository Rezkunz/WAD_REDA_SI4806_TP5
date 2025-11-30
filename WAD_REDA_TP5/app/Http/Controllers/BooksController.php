<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Http\Resources\BookResource;

class BooksController extends Controller
{
    /**
     * ==========1===========
     * Tampilkan daftar semua buku
     */
    public function index()
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    /**
     * ==========2===========
     * Simpan buku baru ke dalam penyimpanan.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'published_year' => 'required|integer',
            'is_available' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['is_available'] = $data['is_available'] ?? 1;
        
        $book = Book::create($data);
        return new BookResource($book);
    }

    /**
     * =========3===========
     * Tampilkan detail buku tertentu.
     */
    public function show(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message'=> 'Book not found'],404);
        }
        return new BookResource($book);

    }

    /**
     * =========4===========
     * Fungsi untuk memperbarui data buku tertentu
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' =>'Book not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'author' => 'string',
            'published_year' => 'integer',
            'is_available' => 'integer'
        ]);
        if ($validator->fails()){
             return response()-> json($validator->errors(), 422);
        }
        $book->update($request->all());
        return new BookResource($book);
    }

    /**
     * =========5===========
     * Hapus buku tertentu dari penyimpanan.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'book not found'], 404);
        }
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    /**
     * =========6===========
     * Ubah status ketersediaan buku (ubah field is_available)
     */
    public function borrowReturn(string $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->is_available = $book->is_available ? 0 : 1;
        $book->save();
        
        return new BookResource($book);
    }
}
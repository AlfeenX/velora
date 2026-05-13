<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'gender' => $this->gender,
            'release_date' => $this->release_date,

            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),

            'collection' => new CollectionResource(
                $this->whenLoaded('collection')
            ),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),

            'variants' => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),

            'images' => ProductImageResource::collection(
                $this->whenLoaded('images')
            ),

            'created_at' => $this->created_at,
        ];
    }
}

<?php
namespace Modules\ClosureDate\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClosureResourceApi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "closure_date"=>$this->closure_date,
            "final_closure_date"=>$this->final_closure_date,
            "academic_year_id"=>$this->academic_year_id,
            "version"=>$this->version,
            "created_by"=>$this->created_by,
            "updated_by"=>$this->updated_by
        ];
    }
}

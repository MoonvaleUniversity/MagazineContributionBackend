<?php

namespace Modules\Authentication\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceApi extends JsonResource
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
            "username"=>$this->name,
            "academic_year_id"=>$this->academic_year_id,
            "faculty_id"=>$this->faculty_id,
            "email"=>$this->email,
            "is_email_verified"=>$this->is_email_verified,
            "role"=>$this->role,
            "is_suspended"=>$this->is_suspended,
            "version"=>$this->version
        ];
    }
}

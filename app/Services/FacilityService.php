<?php

namespace App\Services;

use App\Models\Facility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Log;
use App\Models\Role;

class FacilityService
{
    /**
     * Create a new facility.
     *
     * @param array $data
     * @return Facility
     * @throws \Exception
     */
        public function createFacility(array $data): Facility
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            $facility = new Facility();
            $facility->user_id = $user->id;
            $facility->business_category_id = $data['business_category_id'] ?? null;
            $facility->business_sector_id = $data['business_sector_id'] ?? null;
            $facility->is_active = $data['is_active'] ?? false;

            $this->saveTranslations($facility, $data['name']);

            $facility->save();

            // Attach the owner to the facility_user pivot table
            $facility->users()->attach($user->id);

            // Get the 'facility_owner' role ID
            $facilityOwnerRole = Role::whereHas('translations', function ($query) {
                $query->where('name', 'Facility Owner');
            })->first();

            // Assign the user the facility_owner role FOR THIS SPECIFIC FACILITY
            // by creating a record in the user_facility_role pivot table.
            if ($facilityOwnerRole) {
                DB::table('user_facility_role')->insert([
                    'user_id' => $user->id,
                    'facility_id' => $facility->id,
                    'role_id' => $facilityOwnerRole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $facility;
        });
    }

    /**
     * Update an existing facility.
     *
     * @param Facility $facility
     * @param array $data
     * @return Facility
     * @throws \Exception
     */
    public function updateFacility(Facility $facility, array $data): Facility
    {
        return DB::transaction(function () use ($facility, $data) {
            $facility->business_category_id = $data['business_category_id'];
            $facility->business_sector_id = $data['business_sector_id'];
            $facility->is_active = $data['is_active'] ?? false;

            $this->saveTranslations($facility, $data['name']);

            $facility->save();

            return $facility;
        });
    }

    /**
     * Delete a facility.
     *
     * @param Facility $facility
     * @return void
     */
    public function deleteFacility(Facility $facility): void
    {
        // In the future, if facilities have associated files (like logos),
        // the logic to delete them from storage would go here.
        $facility->delete();
    }

    /**
     * Save translations for the facility.
     *
     * @param Facility $facility
     * @param array $names
     */
    private function saveTranslations(Facility $facility, array $names): void
    {
        foreach ($names as $locale => $name) {
            if ($name) {
                $facility->translateOrNew($locale)->name = $name;
            }
        }
    }
}

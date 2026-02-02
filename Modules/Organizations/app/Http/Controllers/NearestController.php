<?php

namespace Modules\Organizations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller: NearestController
 *
 * Provides geospatial queries to retrieve opportunities
 * based on proximity to a given latitude/longitude.
 *
 * Methods:
 * - nearest(): Returns the closest opportunities to the user within a bounding radius.
 * - farther(): Returns the farthest opportunities from the user.
 */
class NearestController extends Controller
{
    /**
     * Get nearest opportunities to the user.
     *
     * Calculates distance using the Haversine formula with coordinates
     * stored in the locations table. Limits results to 10 closest opportunities.
     *
     * Input:
     * - lat: Latitude of the user (float)
     * - lng: Longitude of the user (float)
     *
     * Process:
     * - Joins opportunities with locations.
     * - Filters coordinates within a bounding box (lat/lng ± radius).
     * - Orders by calculated distance ascending.
     *
     * Output:
     * - JSON array of up to 10 nearest opportunities with fields:
     *   - opportunity attributes
     *   - latitude, longitude
     *   - distance (in meters)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 [
     *   {
     *     "id": 1,
     *     "title": "Volunteer Program",
     *     "latitude": 33.75,
     *     "longitude": -84.38,
     *     "distance": 1200.45
     *   },
     *   ...
     * ]
     */
    public function nearest(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = 0.5; // ~~55KM

        $opportunities = DB::table('opportunities')
            ->join('locations', 'opportunities.location_id', '=', 'locations.id')
            ->select(
                'opportunities.*',
                DB::raw("ST_Y(locations.coordinates) as latitude"),
                DB::raw("ST_X(locations.coordinates) as longitude"),
                DB::raw("
            (6371000 * acos(
                cos(radians($lat)) *
                cos(radians(ST_Y(locations.coordinates))) *
                cos(radians(ST_X(locations.coordinates)) - radians($lng)) +
                sin(radians($lat)) *
                sin(radians(ST_Y(locations.coordinates)))
            )) AS distance
        ")
            )
            ->whereBetween(DB::raw("ST_Y(locations.coordinates)"), [$lat - $radius, $lat + $radius])
            ->whereBetween(DB::raw("ST_X(locations.coordinates)"), [$lng - $radius, $lng + $radius])
            ->orderBy('distance')
            ->limit(10)
            ->get();

        return response()->json($opportunities);
    }

    /**
     * Get nearest opportunities to the user.
     *
     * Calculates distance using the Haversine formula with coordinates
     * stored in the locations table. Limits results to 10 closest opportunities.
     *
     * Input:
     * - lat: Latitude of the user (float)
     * - lng: Longitude of the user (float)
     *
     * Process:
     * - Joins opportunities with locations.
     * - Calculates distance between user coordinates and opportunity coordinates.
     * - Orders results by distance ascending (closest first).
     * - Limits to 10 nearest opportunities.
     *
     * Output:
     * - JSON array of up to 10 nearest opportunities with fields:
     *   - opportunity attributes
     *   - latitude, longitude
     *   - distance (in meters)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiResponse 200 [
     *   {
     *     "id": 1,
     *     "title": "Local Volunteering",
     *     "latitude": 33.75,
     *     "longitude": -84.38,
     *     "distance": 1200.45
     *   },
     *   {
     *     "id": 2,
     *     "title": "Community Training",
     *     "latitude": 33.76,
     *     "longitude": -84.39,
     *     "distance": 2500.00
     *   }
     * ]
     */
    public function farther(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $opportunities = DB::table('opportunities')
            ->join('locations', 'opportunities.location_id', '=', 'locations.id')
            ->select(
                'opportunities.*',
                DB::raw("ST_Y(locations.coordinates) as latitude"),
                DB::raw("ST_X(locations.coordinates) as longitude"),
                DB::raw("
                (6371000 * acos(
                    cos(radians($lat)) *
                    cos(radians(ST_Y(locations.coordinates))) *
                    cos(radians(ST_X(locations.coordinates)) - radians($lng)) +
                    sin(radians($lat)) *
                    sin(radians(ST_Y(locations.coordinates)))
                )) AS distance
            ")
            )
            ->orderBy('distance', 'asc') 
            ->limit(10)
            ->get();

        return response()->json($opportunities);
    }
}

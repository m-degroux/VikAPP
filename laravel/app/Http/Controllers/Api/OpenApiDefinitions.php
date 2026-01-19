<?php

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Raid",
 *     type="object",
 *     title="Raid",
 *     description="Raid event model",
 *     @OA\Property(property="raid_id", type="integer", example=1),
 *     @OA\Property(property="raid_name", type="string", example="Viking Trail 2026"),
 *     @OA\Property(property="raid_description", type="string", example="Annual mountain trail event"),
 *     @OA\Property(property="raid_start_date", type="string", format="date", example="2026-06-15"),
 *     @OA\Property(property="raid_location", type="string", example="Chamonix"),
 *     @OA\Property(property="raid_address", type="string", example="Place de l'Église, 74400 Chamonix"),
 *     @OA\Property(property="raid_gps_lat", type="number", format="float", example=45.9237),
 *     @OA\Property(property="raid_gps_long", type="number", format="float", example=6.8694),
 *     @OA\Property(property="type_id", type="integer", example=1),
 *     @OA\Property(property="diff_id", type="integer", example=2),
 *     @OA\Property(property="club_id", type="integer", example=5)
 * )
 *
 * @OA\Schema(
 *     schema="Race",
 *     type="object",
 *     title="Race",
 *     description="Race model",
 *     @OA\Property(property="race_id", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-abcd-ef1234567890"),
 *     @OA\Property(property="race_name", type="string", example="Trail 20km"),
 *     @OA\Property(property="race_description", type="string"),
 *     @OA\Property(property="race_length", type="number", format="float", example=20.5),
 *     @OA\Property(property="race_start_date", type="string", format="datetime", example="2026-06-15 09:00:00"),
 *     @OA\Property(property="race_max_num_runner", type="integer", example=200),
 *     @OA\Property(property="race_min_num_team_members", type="integer", example=1),
 *     @OA\Property(property="race_max_num_team_members", type="integer", example=5),
 *     @OA\Property(property="race_price", type="number", format="float", example=25.00),
 *     @OA\Property(property="race_time_limit", type="string", format="time", example="04:00:00"),
 *     @OA\Property(property="raid_id", type="integer", example=1),
 *     @OA\Property(property="type_id", type="integer", example=1),
 *     @OA\Property(property="diff_id", type="integer", example=2)
 * )
 *
 * @OA\Schema(
 *     schema="Team",
 *     type="object",
 *     title="Team",
 *     description="Team model",
 *     @OA\Property(property="team_id", type="string", format="uuid"),
 *     @OA\Property(property="team_name", type="string", example="Viking Runners"),
 *     @OA\Property(property="team_point", type="integer", example=150),
 *     @OA\Property(property="team_ranking", type="integer", example=3),
 *     @OA\Property(property="race_id", type="string", format="uuid"),
 *     @OA\Property(property="club_id", type="integer", example=5),
 *     @OA\Property(property="user_id", type="integer", example=42)
 * )
 *
 * @OA\Schema(
 *     schema="Club",
 *     type="object",
 *     title="Club",
 *     description="Sports club model",
 *     @OA\Property(property="club_id", type="integer", example=1),
 *     @OA\Property(property="club_name", type="string", example="Chamonix Trail Club"),
 *     @OA\Property(property="club_address", type="string", example="123 Mountain Road"),
 *     @OA\Property(property="club_mail", type="string", format="email", example="contact@club.com"),
 *     @OA\Property(property="club_phone", type="string", example="+33450123456"),
 *     @OA\Property(property="user_id", type="integer", example=42)
 * )
 *
 * @OA\Schema(
 *     schema="Member",
 *     type="object",
 *     title="Member",
 *     description="Member/User model",
 *     @OA\Property(property="user_id", type="integer", example=42),
 *     @OA\Property(property="user_username", type="string", example="john_doe"),
 *     @OA\Property(property="mem_firstname", type="string", example="John"),
 *     @OA\Property(property="mem_name", type="string", example="Doe"),
 *     @OA\Property(property="mem_sex", type="string", enum={"M", "F"}, example="M"),
 *     @OA\Property(property="mem_size", type="integer", example=180),
 *     @OA\Property(property="mem_weight", type="number", format="float", example=75.5),
 *     @OA\Property(property="mem_birth_year", type="integer", example=1990),
 *     @OA\Property(property="mem_mail", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="mem_phone", type="string", example="+33612345678"),
 *     @OA\Property(property="mem_nationality", type="string", example="FR")
 * )
 *
 * @OA\Schema(
 *     schema="Type",
 *     type="object",
 *     title="Type",
 *     description="Event type",
 *     @OA\Property(property="type_id", type="integer", example=1),
 *     @OA\Property(property="type_name", type="string", example="competition")
 * )
 *
 * @OA\Schema(
 *     schema="Difficulty",
 *     type="object",
 *     title="Difficulty",
 *     description="Difficulty level",
 *     @OA\Property(property="diff_id", type="integer", example=2),
 *     @OA\Property(property="diff_name", type="string", example="medium"),
 *     @OA\Property(property="diff_level", type="number", format="float", example=5.0)
 * )
 *
 * @OA\Schema(
 *     schema="AgeCategory",
 *     type="object",
 *     title="AgeCategory",
 *     description="Age category for races",
 *     @OA\Property(property="age_id", type="integer", example=1),
 *     @OA\Property(property="age_name", type="string", example="12-15"),
 *     @OA\Property(property="age_min", type="integer", example=12),
 *     @OA\Property(property="age_max", type="integer", example=15)
 * )
 *
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     title="Error",
 *     description="Error response",
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Success",
 *     type="object",
 *     title="Success",
 *     description="Success response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation successful")
 * )
 */
class OpenApiDefinitions
{
}

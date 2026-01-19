<?php

namespace App\Actions\Race;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportRaceResults
{
    public function execute(Request $request, string $raceId)
    {
        // 1. File validation
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // 2. Retrieve header and map column names
        $header = fgetcsv($handle, 0, ';');
        if (! $header) {
            fclose($handle);
            throw new \Exception('The CSV file is empty.');
        }

        // Header cleanup (removes Excel's invisible UTF-8 BOM if present)
        $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        $cols = array_flip(array_map('trim', $header));

        // Check for required column names
        if (! isset($cols['EQUIPE'], $cols['TEMPS'], $cols['PTS'])) {
            fclose($handle);
            throw new \Exception('Missing columns (EQUIPE, TEMPS, PTS are required).');
        }

        $updatedCount = 0;
        DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                if (count($data) < count($header)) {
                    continue;
                }

                // Name cleanup: remove whitespace and Excel non-breaking spaces
                $teamNameCsv = trim(str_replace("\xc2\xa0", ' ', $data[$cols['EQUIPE']]));
                $time = $this->sanitizeTime($data[$cols['TEMPS']]);
                $points = (int) $data[$cols['PTS']];

                if (empty($teamNameCsv)) {
                    continue;
                }

                /** * DATA SYNC LOGIC:
                 * Search for the team by its NAME (case-insensitive and trimmed)
                 * We bypass the race_id constraint on existing teams but update it during sync
                 */
                $affected = DB::table('vik_team')
                    ->whereRaw('LOWER(TRIM(team_name)) = ?', [strtolower($teamNameCsv)])
                    ->update([
                        'TEAM_TIME' => $time,
                        'TEAM_POINT' => $points,
                        'race_id' => $raceId, // Attach the team to the correct race ID during update
                    ]);

                if ($affected) {
                    $updatedCount++;
                }
            }

            DB::commit();
            fclose($handle);

            return "Importation réussi: $updatedCount équipes mises à jour.";

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            throw $e;
        }
    }

    /**
     * Cleans and formats time strings for MySQL (TIME type).
     * Handles durations exceeding 24h and cleans input errors.
     */
    private function sanitizeTime($time)
    {
        $time = trim($time);

        // 1. Handle negative times (common Excel export error) or empty values
        if (! $time || str_contains($time, '-')) {
            return '00:00:00';
        }

        // 2. Validate HH:MM:SS or H:M:S format
        // Regex: any number of hours : 00-59 minutes : 00-59 seconds
        if (preg_match('/^(\d+):([0-5]?[0-9]):([0-5]?[0-9])$/', $time, $matches)) {
            // Reconstruct properly padded string (e.g., 5:4:9 becomes 05:04:09)
            return sprintf('%02d:%02d:%02d', $matches[1], $matches[2], $matches[3]);
        }

        // 3. Fallback for unexpected formats
        return '00:00:00';
    }
}

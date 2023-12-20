<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $totalDuration = 0;

        foreach ($program->getSeasons() as $season) {
            foreach ($season->getEpisodes() as $episode) {
                $totalDuration += $episode->getDuration();
            }
        }

        $days = floor($totalDuration / 1440);
        $hours = floor(($totalDuration % 1440) / 60);
        $minutes = $totalDuration % 60;

        $durationString = '';

        if ($days > 0) {
            $durationString .= $days . ' jours ';
        }

        if ($hours > 0) {
            $durationString .= $hours . ' heures ';
        }

        if ($minutes > 0) {
            $durationString .= $minutes . ' minutes ';
        }

        return rtrim($durationString);
    }
}

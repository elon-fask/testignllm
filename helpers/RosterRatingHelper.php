<?php

namespace app\helpers;

use app\models\CandidateTrainingSession;

class RosterRatingHelper
{

    static public function dateconvert($date,$func)
    {
        if ($func == 1) {
            list($month, $day, $year) = preg_split('/[/.-]/', $date);
            $date = "$year-$month-$day";
            return $date;
        }
        if ($func == 2) {
            list($year, $month, $day) = preg_split('/[-.]/', $date);
            $date = "$month/$day/$year";
            return $date;
        }
    }

    static public function showEquivalentRating($rating)
    {
        if($rating == 1){
            return 'C';
        }else if($rating == 2){
            return 'B';
        }else if($rating == 3){
            return 'A';
        }
        return '';
    }

    static public function getCurrentRosterRating($rosterId, $testSessionId)
    {
        $rosterRatings = CandidateTrainingSession::findAll(array('candidate_id' => $rosterId, 'test_session_id' => $testSessionId));
        $rosterRatingId = false;
        foreach($rosterRatings as $rosterRating){
            if($rosterRating->checkin != null && $rosterRating->checkout == null){
                $rosterRatingId = $rosterRating->id;
                break;
            }
        }
        return $rosterRatingId;
    }

    static public function getRosterTotalSeconds($rosterId, $testSessionId)
    {
        $rosterRatings = CandidateTrainingSession::findAll(array('candidate_id' => $rosterId, 'test_session_id' => $testSessionId));
        $rosterRatingId = false;
        $totalTime = 0;
        foreach($rosterRatings as $rosterRating){
            if($rosterRating->checkin != null && $rosterRating->checkout != null){
                $totalTime += (strtotime($rosterRating->checkout) - strtotime($rosterRating->checkin));
            }
        }
        return $totalTime;
    }

    static public function getRosterAverageScore($rosterId, $testSessionId)
    {
        $rosterRatings = CandidateTrainingSession::findAll(array('candidate_id'=>$rosterId, 'test_session_id' => $testSessionId));
        $rosterRatingId = false;
        $totalScore = 0;
        $totalCheckIn = 0;
        foreach($rosterRatings as $rosterRating){
            if($rosterRating->checkin != null && $rosterRating->checkout != null){
                $totalScore += $rosterRating->rating;
                $totalCheckIn++;
            }
        }
        if($totalCheckIn != 0){
            return $totalScore / $totalCheckIn;
        }
        return 0;
    }

    static public function getRosterMaxScore($rosterId, $testSessionId) {
        $rosterRatings = CandidateTrainingSession::findAll(array('candidate_id'=>$rosterId, 'test_session_id' => $testSessionId));	    
        $rosterRatingId = false;
        $totalScore = 0;
        $totalCheckIn = 0;
        $maxRating = false;
        foreach($rosterRatings as $rosterRating){
            if($rosterRating->checkout != null){
                if($maxRating !== false && $maxRating->rating <= $rosterRating->rating){
                    $maxRating = $rosterRating;
                }else if($maxRating === false){
                    $maxRating = $rosterRating;
                }
            }
        }
        if($maxRating !== false){
            return $maxRating->rating.'( '.date('m/d/Y', strtotime($maxRating->checkout)).')';
        }
        return 'N/A';
    }

    static public function getRosterMinScore($rosterId, $testSessionId) {
        $rosterRatings = CandidateTrainingSession::findAll([
            'candidate_id'=>$rosterId,
            'test_session_id' => $testSessionId
        ]);
        $rosterRatingId = false;
        $totalScore = 0;
        $totalCheckIn = 0;
        $minRating = false;
        foreach($rosterRatings as $rosterRating){
            if($rosterRating->checkout != null){
                if($minRating !== false && $minRating->rating >= $rosterRating->rating){
                    $minRating = $rosterRating;
                }else if($minRating === false){
                    $minRating = $rosterRating;
                }
            }
        }
        if($minRating !== false){
            return $minRating->rating.'( '.date('m/d/Y', strtotime($minRating->checkout)).')';
        }
        return 'N/A';
    }

    static public function showRatingCheckoutDate($checkOutDate)
    {
        if ($checkOutDate == '') {
            return '';
        }
        list($date, $time) = preg_split('/ /', $checkOutDate);
        list($year, $month, $day) = preg_split('/[-.]/', $date);
        $dateDisplay = "$month/$day/$year $time";
        return $dateDisplay.' '.date('A', strtotime($checkOutDate));
    }

    static public function showClassDate($class)
    {
        if ($class->start_date != null && $class->end_date != null) {
            return date('m/d/Y', strtotime($class->start_date)).' - '.date('m/d/Y', strtotime($class->end_date));
        }
        return 'N/A';
    }

    static public function showNumberOfActiveRoster($class)
    {
        return count($class->getActiveRosters()->all());
    }
}

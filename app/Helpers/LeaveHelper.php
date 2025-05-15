<?php

namespace App\Helpers;

class LeaveHelper
{
    /**
     * Create a new class instance.
     */
   public static function hasOverlap($userId, $startDate, $endDate): bool
    {
        return LeaveRequest::where('user_id', $userId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();
    }
}

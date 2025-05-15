<?php

class LeaveRequestFormRequest{
    public function rules()
{
    return [
        'leave_type' => 'required|in:Sick,Casual',
        'start_date' => 'required|date|before_or_equal:end_date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'nullable|string|max:1000',
    ];
}

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        if ($this->hasOverlappingLeave()) {
            $validator->errors()->add('start_date', 'Leave dates overlap with existing requests.');
        }

        if ($this->exceedsMaxDays()) {
            $validator->errors()->add('start_date', 'Leave cannot exceed 30 days.');
        }
    });
}
}

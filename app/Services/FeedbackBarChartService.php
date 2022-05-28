<?php

namespace App\Services;

use App\Models\TypeFeedback;
use Coroowicaksono\ChartJsIntegration\BarChart;

class FeedbackBarChartService
{
    public static function getBarChart($user, $type)
    {
        if ($user->isUniversityDecider()) {
            return FeedbackBarChartService::universityDeciderBarChart($user, $type);
        }
        if ($user->isResidenceDecider()) {
            return FeedbackBarChartService::ResidenceDeciderBarChart($user, $type);
        }
        return FeedbackBarChartService::MinisterBarChart($type);
    }

    public static function universityDeciderBarChart($user, $type)
    {
        return (new BarChart())
            ->title('Positive Feedback vs Negative Feedback')
            ->model('\App\Models\Feedback')
            ->join('users', 'users.id', '=', 'feedback.user_id')
            ->series(array([
                'label' => 'Positive',
                'filter' => [
                    'key' => 'is_positive',
                    'value' => true
                ],
                'backgroundColor' => '#388E3C',
            ], [
                'label' => 'Negative',
                'filter' => [
                    'key' => 'is_positive',
                    'value' => 'false'
                ],
                'backgroundColor' => '#F51A24',
            ]))
            ->options([
                'queryFilter' => array(
                    [
                        'key' => 'type_feedback_id',
                        'operator' => '=',
                        'value' => $type
                    ], [
                        'key' => 'users.establishment_id',
                        'operator' => '=',
                        'value' => $user->establishment_id
                    ]
                ),
                'uom' => 'day',
                'btnFilter' => true,
                'btnFilterDefault' => 7,
                'btnFilterList' => [
                    7 => __('week'),
                    30 => __('30 Days'),
                    60 => __('60 Days'),
                    365 => __('Year'),
                    'MTD' => __('Month To Date'),
                    'YTD' => __('Year To Date'),
                ],
                'showTotal' => false,
            ])
            ->width('full');
    }

    public static function ResidenceDeciderBarChart($user, $type)
    {
        return (new BarChart())
            ->title('Positive Feedback vs Negative Feedback')
            ->model('\App\Models\Feedback')
            ->join('residents', 'residents.user_id', '=', 'feedback.user_id')
            ->series(array([
                'label' => 'Positive',
                'filter' => [
                    'key' => 'is_positive',
                    'value' => true
                ],
                'backgroundColor' => '#388E3C',
            ], [
                'label' => 'Negative',
                'filter' => [
                    'key' => 'is_positive',
                    'value' => 'false'
                ],
                'backgroundColor' => '#F51A24',
            ]))
            ->options([
                'queryFilter' => array(
                    [
                        'key' => 'type_feedback_id',
                        'operator' => '=',
                        'value' => $type
                    ], [
                        'key' => 'residents.establishment_id',
                        'operator' => '=',
                        'value' => $user->establishment_id
                    ]
                ),
                'uom' => 'day',
                'btnFilter' => true,
                'btnFilterDefault' => 7,
                'btnFilterList' => [
                    7 => __('week'),
                    30 => __('30 Days'),
                    60 => __('60 Days'),
                    365 => __('Year'),
                    'MTD' => __('Month To Date'),
                    'YTD' => __('Year To Date'),
                ],
                'showTotal' => false,
            ])
            ->width('full');
    }

    public static function MinisterBarChart($type)
    {
        return (new BarChart())
            ->title('Positive Feedback vs Negative Feedback')
            ->model('\App\Models\Feedback')
            ->series(array([
                'label' => 'Positive',
                'filter' => [
                    'key' => 'is_positive',
                    'value' => true
                ],
                'backgroundColor' => '#388E3C',
            ], [
                'label' => 'Negative',
                'filter' => [
                    'key' => 'is_positive',
                    'value' => 'false'
                ],
                'backgroundColor' => '#F51A24',
            ]))
            ->options([
                'queryFilter' => array([
                    'key' => 'type_feedback_id',
                    'operator' => '=',
                    'value' => $type
                ]),
                'uom' => 'day',
                'btnFilter' => true,
                'btnFilterDefault' => 7,
                'btnFilterList' => [
                    7 => __('week'),
                    30 => __('30 Days'),
                    60 => __('60 Days'),
                    365 => __('Year'),
                    'MTD' => __('Month To Date'),
                    'YTD' => __('Year To Date'),
                ],
                'showTotal' => false,
            ])
            ->width('full');
    }
}

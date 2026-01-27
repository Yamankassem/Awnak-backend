<?php

namespace Modules\Applications\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\CalendarEvent;
use Modules\Applications\Http\Requests\CalendarRequest\IndexCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\StoreCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\UpdateCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\UpcomingCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\RemindersCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\UpdateStatusCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\SearchCalendarRequest;
use Modules\Applications\Http\Requests\CalendarRequest\StatisticsCalendarRequest;

class CalendarController extends Controller
{
    public function index(IndexCalendarRequest $request): JsonResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        $query = CalendarEvent::where('user_id', $user->id)
            ->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
            ->orderBy('start_date', 'asc');

        if (isset($validated['type']) && $validated['type'] !== 'all') {
            $query->where('type', $validated['type']);
        }

        if (isset($validated['related_type']) && isset($validated['related_id'])) {
            $query->where('related_type', $validated['related_type'])
                  ->where('related_id', $validated['related_id']);
        }

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $events = $query->get();

        return $this->success([
            'events' => $events,
            'total' => $events->count(),
            'date_range' => [
                'start' => $validated['start_date'],
                'end' => $validated['end_date'],
            ],
        ], 'messages.events_retrieved');
    }

    public function store(StoreCalendarRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        if (!isset($validated['end_date'])) {
            $validated['end_date'] = $validated['start_date'];
        }

        $event = CalendarEvent::create($validated);

        if (isset($validated['reminder_minutes']) && $validated['reminder_minutes'] > 0) {
            $this->scheduleReminder($event);
        }

        return $this->success($event, 'messages.event_created', 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth()->user();
        $event = CalendarEvent::where('user_id', $user->id)->findOrFail($id);

        return $this->success($event, 'messages.event_retrieved');
    }

    public function update(UpdateCalendarRequest $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $event = CalendarEvent::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validated();
        $oldReminder = $event->reminder_minutes;
        
        $event->update($validated);

        if ($event->wasChanged('reminder_minutes') || $event->wasChanged('start_date')) {
            $this->rescheduleReminder($event, $oldReminder);
        }

        return $this->success($event, 'messages.event_updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $user = auth()->user();
        $event = CalendarEvent::where('user_id', $user->id)->findOrFail($id);

        $event->delete();
        $this->cancelReminder($event);

        return $this->success(null, 'messages.event_deleted');
    }

    public function upcoming(UpcomingCalendarRequest $request): JsonResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        $days = $validated['days'] ?? 7;
        $limit = $validated['limit'] ?? 20;
        
        $startDate = now();
        $endDate = now()->addDays($days);

        $query = CalendarEvent::where('user_id', $user->id)
            ->where('start_date', '>=', $startDate)
            ->where('start_date', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date', 'asc')
            ->limit($limit);

        if (isset($validated['type']) && $validated['type'] !== 'all') {
            $query->where('type', $validated['type']);
        }

        $events = $query->get();

        return $this->success([
            'events' => $events,
            'total' => $events->count(),
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
        ], 'messages.upcoming_events_retrieved');
    }

    public function reminders(RemindersCalendarRequest $request): JsonResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        $hours = $validated['hours'] ?? 24;
        $reminderTime = now()->addHours($hours);

        $events = CalendarEvent::where('user_id', $user->id)
            ->where('start_date', '<=', $reminderTime)
            ->where('start_date', '>=', now())
            ->where('status', 'pending')
            ->whereNotNull('reminder_minutes')
            ->where('reminder_sent', false)
            ->orderBy('start_date', 'asc')
            ->get();

        return $this->success([
            'events' => $events,
            'total' => $events->count(),
            'next_reminder_time' => $reminderTime->format('Y-m-d H:i:s'),
        ], 'messages.reminders_retrieved');
    }

    public function updateStatus(UpdateStatusCalendarRequest $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $event = CalendarEvent::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validated();
        $oldStatus = $event->status;
        $event->update(['status' => $validated['status']]);

        if ($validated['status'] === 'cancelled') {
            $this->cancelReminder($event);
        }

        return $this->success($event, 'messages.event_status_updated');
    }

    public function search(SearchCalendarRequest $request): JsonResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 15;

        $query = CalendarEvent::where('user_id', $user->id)
            ->where(function ($q) use ($validated) {
                $q->where('title', 'like', '%' . $validated['query'] . '%')
                  ->orWhere('description', 'like', '%' . $validated['query'] . '%')
                  ->orWhere('location', 'like', '%' . $validated['query'] . '%');
            });

        if (isset($validated['start_date'])) {
            $query->where('start_date', '>=', $validated['start_date']);
        }

        if (isset($validated['end_date'])) {
            $query->where('start_date', '<=', $validated['end_date']);
        }

        if (isset($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        $events = $query->orderBy('start_date', 'desc')->paginate($perPage);

        return $this->paginated($events, 'messages.events_search_results');
    }

    public function statistics(StatisticsCalendarRequest $request): JsonResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        $month = $validated['month'] ?? now()->month;
        $year = $validated['year'] ?? now()->year;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        $events = CalendarEvent::where('user_id', $user->id)
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->get();

        $stats = [
            'total' => $events->count(),
            'by_type' => $events->groupBy('type')->map->count(),
            'by_status' => $events->groupBy('status')->map->count(),
            'busiest_day' => $events->groupBy(function ($event) {
                return $event->start_date->format('Y-m-d');
            })->map->count()->sortDesc()->first(),
            'month' => $month,
            'year' => $year,
        ];

        return $this->success($stats, 'messages.calendar_statistics_retrieved');
    }

    private function scheduleReminder(CalendarEvent $event): void
    {
        if ($event->reminder_minutes > 0) {
            $reminderTime = Carbon::parse($event->start_date)->subMinutes($event->reminder_minutes);
            
            if ($reminderTime->isFuture()) {
                \Log::info('Event reminder scheduled', [
                    'event_id' => $event->id,
                    'reminder_time' => $reminderTime,
                    'user_id' => $event->user_id,
                ]);
            }
        }
    }

    private function rescheduleReminder(CalendarEvent $event, ?int $oldReminder): void
    {
        $this->cancelReminder($event);
        $this->scheduleReminder($event);
    }

    private function cancelReminder(CalendarEvent $event): void
    {
        \Log::info('Event reminder cancelled', [
            'event_id' => $event->id,
            'user_id' => $event->user_id,
        ]);
    }
}
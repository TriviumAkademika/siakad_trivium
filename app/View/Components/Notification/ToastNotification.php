<?php

namespace App\View\Components\Notification;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToastNotification extends Component
{
    public $message;
    public $type;
    public $classes;
    public $iconClass;

    public function __construct()
    {
        $this->message = session('message');
        $this->type = session('type') ?? 'info';

        $colorMap = [
            'error' => 'bg-merah-100 text-error',
            'info' => 'bg-biru-100 text-info',
            'success' => 'bg-hijau-100 text-success',
            'warning' => 'bg-kuning-100 text-warning',
        ];

        $iconMap = [
            'error' => 'ph ph-info',
            'info' => 'ph ph-info',
            'success' => 'ph ph-info',
            'warning' => 'ph ph-warning',
        ];

        $this->classes = $colorMap[$this->type] ?? $colorMap['info'];
        $this->iconClass = $iconMap[$this->type] ?? $iconMap['info'];
    }

    public function render(): View|Closure|string
    {
        return view('components.notification.toast-notification');
    }
}

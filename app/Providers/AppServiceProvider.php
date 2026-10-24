<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Merge the guest cart into the user's cart after login / registration / Google sign-in
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            app(\App\Services\CartService::class)->transferSessionCartToUserCart($event->user);
        });

        // Custom branded Arabic verification email for Saja Specialized
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('تأكيد البريد الإلكتروني - مؤسسة سجى المتخصصة')
                ->greeting('مرحباً بك ' . ($notifiable->name ?? '') . ' في مؤسسة سجى المتخصصة!')
                ->line('شكراً لانضمامك إلى منصة مؤسسة سجى المتخصصة للكتب والمناهج التعليمية.')
                ->line('يرجى النقر على الزر أدناه لتأكيد وتفعيل بريدك الإلكتروني والبدء في التسوق والطلب بكل سهولة:')
                ->action('تأكيد وتفعيل البريد الإلكتروني', $url)
                ->line('رابط التفعيل هذا آمن وصالح للاستخدام لمرة واحدة فقط.')
                ->line('إذا لم تقم بإنشاء هذا الحساب، فلا داعي لاتخاذ أي إجراء.')
                ->salutation("مع أطيب التحيات،\nفريق مؤسسة سجى المتخصصة");
        });
    }
}

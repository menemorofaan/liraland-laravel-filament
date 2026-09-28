<?php

namespace App\Providers\Filament;

use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
			->renderHook(
    PanelsRenderHook::BODY_END,
    fn (): string => Blade::render('
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                let isMouseDown = false;
                let isDragging = false;
                let startCheckbox = null;
                let targetState = true;

                function setCheckbox(cb, state) {
                    if (cb && cb.checked !== state) {
                        cb.checked = state;
                        cb.dispatchEvent(new Event("input", { bubbles: true }));
                        cb.dispatchEvent(new Event("change", { bubbles: true }));
                    }
                }

                function findCheckbox(target) {
                    if (!target) return null;
                    return target.matches("input[type=\"checkbox\"]")
                        ? target
                        : target.closest("label")?.querySelector("input[type=\"checkbox\"]");
                }

                document.addEventListener("mousedown", (e) => {
                    if (e.button !== 0) return;
                    const cb = findCheckbox(e.target);
                    if (cb) {
                        isMouseDown = true;
                        isDragging = false;
                        startCheckbox = cb;
                        targetState = !cb.checked;
                    }
                });

                document.addEventListener("mouseover", (e) => {
                    if (!isMouseDown) return;

                    const cb = findCheckbox(e.target);
                    if (!cb) return;

                    // Если курсор перешёл на другой чекбокс — активируем режим перетаскивания
                    if (cb !== startCheckbox) {
                        if (!isDragging) {
                            isDragging = true;
                            // Применяем состояние к первому чекбоксу
                            setCheckbox(startCheckbox, targetState);
                        }
                        setCheckbox(cb, targetState);
                    }
                });

                document.addEventListener("mouseup", () => {
                    isMouseDown = false;
                    isDragging = false;
                    startCheckbox = null;
                });
            });
        </script>
    ')
)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

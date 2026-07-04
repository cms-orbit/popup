import { useEffect, useState } from 'react';
import { useT } from '@cms-orbit/core/lib/i18n';

export interface OrbitPopup {
    id: number;
    title: string | null;
    content: string | null;
    show_title: boolean;
    ignore_days: number | null;
    styles: Record<string, unknown>;
}

interface ActivePopupsResponse {
    popups: OrbitPopup[];
}

const STORAGE_PREFIX = 'orbit-popup-dismissed:';

function isDismissed(popup: OrbitPopup): boolean {
    if (typeof window === 'undefined') {
        return false;
    }

    const raw = window.localStorage.getItem(STORAGE_PREFIX + popup.id);

    if (raw === null) {
        return false;
    }

    const until = Number.parseInt(raw, 10);

    return Number.isFinite(until) && until > Date.now();
}

function dismiss(popup: OrbitPopup): void {
    if (typeof window === 'undefined') {
        return;
    }

    const days = popup.ignore_days ?? 0;

    if (days <= 0) {
        window.sessionStorage.setItem(STORAGE_PREFIX + popup.id, '1');

        return;
    }

    const until = Date.now() + days * 24 * 60 * 60 * 1000;
    window.localStorage.setItem(STORAGE_PREFIX + popup.id, String(until));
}

export interface OrbitPopupsProps {
    /** Endpoint returning the active popups. Defaults to the package route. */
    endpoint?: string;
}

/**
 * Fetches the currently active popups and renders them as dismissible
 * overlays. Drop this component into a host layout to enable site-wide popups.
 */
export function OrbitPopups({ endpoint = '/popups/active' }: OrbitPopupsProps) {
    const t = useT();
    const [popups, setPopups] = useState<OrbitPopup[]>([]);

    useEffect(() => {
        let cancelled = false;

        fetch(endpoint, { headers: { Accept: 'application/json' } })
            .then((response) => (response.ok ? response.json() : { popups: [] }))
            .then((data: ActivePopupsResponse) => {
                if (cancelled) {
                    return;
                }

                setPopups((data.popups ?? []).filter((popup) => !isDismissed(popup)));
            })
            .catch(() => {
                if (!cancelled) {
                    setPopups([]);
                }
            });

        return () => {
            cancelled = true;
        };
    }, [endpoint]);

    const close = (popup: OrbitPopup, remember: boolean) => {
        if (remember) {
            dismiss(popup);
        }

        setPopups((current) => current.filter((item) => item.id !== popup.id));
    };

    if (popups.length === 0) {
        return null;
    }

    return (
        <div className="pointer-events-none fixed inset-0 z-50 flex items-center justify-center p-4">
            <div className="pointer-events-none absolute inset-0 bg-black/40" />
            <div className="pointer-events-auto flex max-h-full flex-col gap-4 overflow-auto">
                {popups.map((popup) => (
                    <div
                        key={popup.id}
                        className="w-[420px] max-w-full rounded-lg bg-white shadow-xl dark:bg-neutral-900"
                        style={popup.styles as React.CSSProperties}
                    >
                        {popup.show_title && popup.title && (
                            <div className="border-b border-neutral-200 px-5 py-3 font-medium text-neutral-900 dark:border-neutral-800 dark:text-neutral-100">
                                {popup.title}
                            </div>
                        )}

                        {popup.content && (
                            <div
                                className="prose prose-neutral max-w-none px-5 py-4 dark:prose-invert"
                                dangerouslySetInnerHTML={{ __html: popup.content }}
                            />
                        )}

                        <div className="flex items-center justify-between border-t border-neutral-200 px-5 py-3 text-sm dark:border-neutral-800">
                            <button
                                type="button"
                                className="text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200"
                                onClick={() => close(popup, true)}
                            >
                                {t('Do not show again today')}
                            </button>
                            <button
                                type="button"
                                className="rounded bg-neutral-900 px-3 py-1 text-white dark:bg-neutral-100 dark:text-neutral-900"
                                onClick={() => close(popup, false)}
                            >
                                {t('Close')}
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default OrbitPopups;

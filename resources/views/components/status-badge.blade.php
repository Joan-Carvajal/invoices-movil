@props(['invoice'])

<span @class([
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
    'bg-neutral-soft text-muted' => $invoice->status === 'draft',
    'bg-warning-soft text-warning' => $invoice->status === 'sent',
    'bg-success-soft text-success' => $invoice->status === 'paid',
])>{{ $invoice->status_label }}</span>
